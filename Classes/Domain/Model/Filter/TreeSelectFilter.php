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
	 * @var Tx_PtExtbase_Tree_Tree
	 */
	protected $tree;


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
	 * @var int
	 */
	protected $treeRootNode = NULL;


	/**
	 * @var array
	 */
	protected $options;


	/**
	 * @var bool
	 */
	protected $treeRespectEnableFields = TRUE;


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

		if(array_key_exists('treeRootNode', $this->filterConfig->getSettings())) {
			$this->treeRootNode = (int) $this->filterConfig->getSettings('treeRootNode');
		}


		if(array_key_exists('treeRespectEnableFields', $this->filterConfig->getSettings())) {
			$this->treeRespectEnableFields = (int) $this->filterConfig->getSettings('treeRespectEnableFields') === 1 ? TRUE : FALSE;
		}


		$this->buildTree();
	}



	/**
	 * Build the criteria for a single field
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier
	 * @return Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
	 */
	protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {
		$fieldName = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($fieldIdentifier);
		$singleCriteria = NULL;

		$singleCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::in($fieldName, $this->getFilterNodeUIds());

		return $singleCriteria;
	}



	/**
	 * @return array
	 */
	protected function getFilterNodeUIds() {
		$treeNodeUIds = array();

		foreach($this->filterValues as $filterValue) {
			$treeNodeUIds = array_merge($treeNodeUIds, $this->getSubTreeUIDs($filterValue));
			$treeNodeUIds[] = $filterValue;
		}

		return array_unique($treeNodeUIds);
	}



	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilterByGpVars()
	 *
	 */
	protected function initFilterByGpVars() {
		if (array_key_exists('filterValues', $this->gpVarFilterData)) {
			$this->filterValues = t3lib_div::trimExplode(',', $this->gpVarFilterData['filterValues']);
		}
	}



	/**
	 * @return array
	 */
	public function persistToSession() {
		return array(
			'filterValues' => $this->filterValues,
			'invert' => $this->invert
		);
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

		if ($this->treeRootNode) {
			$subTreeRootNode = $this->tree->getNodeByUid($this->treeRootNode);
			$this->tree = Tx_PtExtbase_Tree_Tree::getInstanceByRootNode($subTreeRootNode);
		}


		if (isset($this->treeMaxDepth)) {
			$this->tree->setRestrictedDepth($this->treeMaxDepth);
			$this->tree->setRespectRestrictedDepth(TRUE);
		}


		$arrayWriterVisitor = new Tx_PtExtbase_Tree_ExtJsJsonWriterVisitor();
		$arrayWriterVisitor->registerFirstVisitCallback($this, 'alterNodeArrayOnFirstVisit');
		$arrayWriterVisitor->registerLastVisitCallBack($this, 'alterNodeArrayOnLastVisit');
		$arrayWriterVisitor->setMultipleSelect($this->getMultiple());
		$arrayWriterVisitor->setSelection($this->filterValues);

		$jsonTreeWriter = new Tx_PtExtbase_Tree_JsonTreeWriter(array($arrayWriterVisitor), $arrayWriterVisitor);

		return $jsonTreeWriter->writeTree($this->tree);
	}



	/**
	 * @return Tx_PtExtbase_Tree_Tree
	 */
	protected function buildTree() {
		$treeRepositoryBuilder = Tx_PtExtbase_Tree_TreeRepositoryBuilder::getInstance();
		$treeRepositoryBuilder->setNodeRepositoryClassName($this->treeNodeRepository);

		$treeRepository = $treeRepositoryBuilder->buildTreeRepository();
		$treeRepository->setRespectEnableFields($this->treeRespectEnableFields);

		$this->tree = $treeRepository->loadTreeByNamespace($this->treeNamespace);
	}



	/**
	 * @param $node Tx_PtExtbase_Tree_Node
	 * @param $nodeArray array
	 * @return array
	 */
	public function alterNodeArrayOnFirstVisit($node, $nodeArray) {
		$nodeArray['rowCount'] = $this->options[$node->getUid()]['rowCount'];

		return $nodeArray;
	}



	/**
	 * @param $node Tx_PtExtbase_Tree_Node
	 * @param $currentNode array
	 * @return array
	 */
	public function alterNodeArrayOnLastVisit($node, $currentNode) {

		foreach($currentNode['children'] as $child) {
			$currentNode['rowCount'] += $child['rowCount'];
		}

		$currentNode['text'] .= sprintf(' (%s)',(int) $currentNode['rowCount']);

		return $currentNode;
	}


	/**
	 * Returns value of selected option, return an array always
	 *
	 * @return array
	 */
	public function getValue() {
		if(count($this->filterValues) > 1) {
			return implode(', ', $this->filterValues);
		} else {
			reset($this->filterValues);
			return(current($this->filterValues));
		}
	}
	
	
	
	/**
	 * 
	 * Multiple or dropdown select
	 * @return integer
	 */
	public function getMultiple() {
		return $this->multiple;
	}



	/**
	 * Used for breadcrumbs or for the header in exports
	 *
	 * @return string
	 */
	public function getDisplayValue() {
		$displayValues = array();

		foreach($this->filterValues as $value) {
			$displayValues[] = $this->tree->getNodeByUid($value)->getLabel();
		}

		return implode(', ', $displayValues);
	}



	/**
	 * Get a list of subtree Uids
	 *
	 * @param $nodeUid
	 * @return array
	 */
	protected function getSubTreeUIDs($nodeUid) {

		$subtreeNodeIdArray = array();

		$treeNode = $this->tree->getNodeByUid($nodeUid);

		if($treeNode instanceof Tx_PtExtbase_Tree_Node) {

			$subTreeNodes = $treeNode->getSubNodes();
			$subtreeNodeIdArray = array();

			foreach($subTreeNodes as $subTreeNode) { /** @var Tx_PtExtbase_Tree_Node $subTreeNode */
				$subtreeNodeIdArray[] = $subTreeNode->getUid();
			}
		} else {
			$subtreeNodeIdArray[] = -1;
		}

		return $subtreeNodeIdArray;
	}

}

?>