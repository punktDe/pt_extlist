<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
 *  All rights reserved
 *
 *  For further information: http://extlist.punkt.de <extlist@punkt.de>
 *
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class implements a tree select filter
 * 
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_TreeSelectFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter {
	
	/**
	 * SingleSelect or multi-select checkbox tree
	 * @var integer
	 */
	protected $multiple;


	/**
	 * @var string
	 */
	protected $treeNodeRepository;


	/**
	 * @var string
	 */
	protected $treeNamespace;


	/**
	 * @var int
	 */
	protected $treeMaxDepth;


	/**
	 * @var array
	 */
	protected $options;



	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilterByTsConfig()
	 *
	 */
	protected function initFilterByTsConfig() {
		parent::initFilterByTsConfig();

		if ($this->filterConfig->getSettings('multiple')) {
			$this->multiple = $this->filterConfig->getSettings('multiple');
		}

		$this->treeNodeRepository = $this->filterConfig->getSettings('treeNodeRepository');
		if(!$this->treeNodeRepository || !class_exists($this->treeNodeRepository)) {
			throw new Exception('The treeNodeRepository with className ' . $this->treeRepository . ' could no be found. ', 1328459171);
		}

		$this->treeNamespace = trim($this->filterConfig->getSettings('treeNamespace'));

		if ($this->filterConfig->getSettings('treeMaxDepth')) {
			$this->treeMaxDepth = $this->filterConfig->getSettings('treeMaxDepth');
		}

		if ($this->filterConfig->getSettings('treeUidFieldIdentifier')) {
			$fieldIdentifierName = $this->filterConfig->getSettings('treeUidFieldIdentifier');
			$this->treeUidFieldIdentifier = $this->fieldIdentifierCollection->getFieldConfigByIdentifier($fieldIdentifierName);
		} else {
			$this->treeUidFieldIdentifier = $this->fieldIdentifierCollection->getItemByIndex(0);
		}
	}



	/**
	 * Transforms the comma separated values into an array
	 */
 	public function initFilter() {
		 if($this->multiple) {
			 $this->filterValues = t3lib_div::trimExplode(',', array_shift($this->filterValues));
		 }
	 }



	/**
	 * @return string
	 */
	public function getTreeNodes() {
		$this->options = $this->getOptions();
		return $this->buildTreeNodes();
	}



	/**
	 * @return string
	 */
	protected function buildTreeNodes() {

		$treeRepositoryBuilder = Tx_PtExtbase_Tree_TreeRepositoryBuilder::getInstance();
		$treeRepositoryBuilder->setNodeRepositoryClassName($this->treeNodeRepository);

		$treeRepository = $treeRepositoryBuilder->buildTreeRepository();

		$tree = $treeRepository->loadTreeByNamespace($this->treeNamespace);
		if ($this->filterConfig->getSettings('treeRootNode')) {
			$subTreeRootNode = $tree->getNodeByUid($this->filterConfig->getSettings('treeRootNode'));
			$tree = Tx_PtExtbase_Tree_Tree::getInstanceByRootNode($subTreeRootNode);
		}


		if (isset($this->treeMaxDepth)) {
			$tree->setRestrictedDepth($this->treeMaxDepth);
			$tree->setRespectRestrictedDepth(TRUE);
		}


		$arrayWriterVisitor = new Tx_PtExtbase_Tree_ExtJsJsonWriterVisitor();
		$arrayWriterVisitor->registerFirstVisitCallback($this, 'alterNodeArray');
		$arrayWriterVisitor->setMultipleSelect($this->getMultiple());
		$arrayWriterVisitor->setSelection($this->getSelection());

		$jsonTreeWriter = new Tx_PtExtbase_Tree_JsonTreeWriter(array($arrayWriterVisitor), $arrayWriterVisitor);

		return $jsonTreeWriter->writeTree($tree);
	}



	/**
	 * Return the selection as single value or comma separated string of values
	 *
	 * @return array / string
	 */
	protected function getSelection() {
		$selection = array();

		foreach($this->options as $key => $option) {
			if($option['selected']) $selection[] = $key;
		}

		if($this->multiple) {
			return $selection;
		} else {
			return count($selection) > 0 ? $selection[0] : '';
		}
	}



	/**
	 * @param $node Tx_PtExtbase_Tree_Node
	 * @param $nodeArray array
	 * @return array
	 */
	public function alterNodeArray($node, $nodeArray) {
		if(array_key_exists($node->getUid(), $this->options)) {
			$nodeArray['text'] .= $this->options[$node->getUid()]['value'];
		}

		return $nodeArray;
	}




	/**
	 * Returns value of selected option, return an array always
	 *
	 * @return array
	 */
	public function getValue() {
		// Set internal pointer to first element of array
		reset($this->filterValues);
		return $this->multiple ? $this->filterValues : current($this->filterValues);
	}
	
	
	
	/**
	 * 
	 * Multiple or dropdown select
	 * @return integer
	 */
	public function getMultiple() {
		return $this->multiple;
	}
	
}

?>