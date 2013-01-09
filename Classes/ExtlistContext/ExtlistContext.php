<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Class implements ExtListContext
 *
 * @package ExtlistContext
 * @author Daniel Lienert
 */
class Tx_PtExtlist_ExtlistContext_ExtlistContext {


	/**
	 * @var Tx_PtExtlist_Extbase_ExtbaseContext
	 */
	protected $extBaseContext;



	/**
	 * Holds an instance of list configuration for this context
	 *
	 * @var array
	 */
	protected $listSettings;



	/**
	 * Holds list identifier for list kept in context
	 *
	 * @var string
	 */
	protected $listIdentifier;



	/**
	 * Holds data backend for list identifier
	 *
	 * @var Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 */
	protected $dataBackend = null;



	/**
	 * Holds an instance of renderer chain for this list
	 *
	 * @var Tx_PtExtlist_Domain_Renderer_RendererChain
	 */
	protected $rendererChain = null;



	/**
	 * Cached rendered list data
	 *
	 * @var
	 */
	protected $renderedListData;



	/**
	 * Cached pager collection
	 *
	 * @var
	 */
	protected $pagerCollection;


	/**
	 * @var Tx_PtExtlist_Domain_Model_List_List
	 */
	protected $list = NULL;



	/**
	 * Initialize the extlistContext
	 */
	public function init() {
	}


	/**
	 * @param Tx_PtExtlist_Extbase_ExtbaseContext $extBaseContext
	 */
	public function injectExtBaseContext(Tx_PtExtlist_Extbase_ExtbaseContext $extBaseContext) {
		$this->extBaseContext = $extBaseContext;
	}


	/**
	 * Inject the Databackend
	 *
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 */
	public function _injectDataBackend(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend) {
		$this->dataBackend = $dataBackend;
	}


	/**
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	public function getConfigurationBuilder() {
		return $this->dataBackend->getConfigurationBuilder();
	}



	/**
	 * Returns renderer chain
	 *
	 * @return Tx_PtExtlist_Domain_Renderer_RendererChain
	 */
	public function getRendererChain() {

		if($this->rendererChain === NULL) {
			$this->rendererChain = Tx_PtExtlist_Domain_Renderer_RendererChainFactory::getRendererChain($this->dataBackend->getConfigurationBuilder()->buildRendererChainConfiguration());
		}

		return $this->rendererChain;
	}



	/**
	 * Returns data backend of list context
	 *
	 * @return Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 */
	public function getDataBackend() {
		return $this->dataBackend;
	}



	/**
	 * Returns pager collection fo databacken for this list context
	 *
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerCollection
	 */
	public function getPagerCollection() {
		if ($this->pagerCollection === NULL) {
			$this->pagerCollection = $this->dataBackend->getPagerCollection();
			$this->pagerCollection->setItemCount($this->dataBackend->getTotalItemsCount());
		}
		return $this->pagerCollection;
	}



	/**
	 * Get a pager object - if pagerIdentifier is null, get default pager
	 *
	 * @param string $pagerIdentifier
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerInterface
	 */
	public function getPager($pagerIdentifier = '') {
		$pagerIdentifier = $pagerIdentifier ? $pagerIdentifier : 'default';
		return $this->dataBackend->getPagerCollection()->getPagerByIdentifier($pagerIdentifier);
	}



	/**
	 * Returns list object of this list context
	 *
	 * @param $buildNew boolean
	 * @return Tx_PtExtlist_Domain_Model_List_List
	 */
	public function getList($buildNew = false) {
		if($this->list == NULL || $buildNew) {
			$this->list = Tx_PtExtlist_Domain_Model_List_ListFactory::createList($this->dataBackend, $this->dataBackend->getConfigurationBuilder(), $buildNew);
		}

		return $this->list;
	}



	/**
	 * Returns list data for this list context
	 *
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getListData() {
		return $this->getList()->getListData();
	}



	/**
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getIterationListData() {
		return $this->dataBackend->getIterationListData();
	}



	/**
	 * Returns rendered list data for this list context
	 *
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getRenderedListData() {
		return $this->getList()->getRenderedListData();
	}



	/**
	 * Returns the filterboxCollection
	 *
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection
	 */
	public function getFilterBoxCollection() {
		return $this->dataBackend->getFilterboxCollection();
	}



	/**
	 * Returns filter for given full filter name (filterboxIdentifier.filterIdentifier)
	 *
	 * TODO refactor me, use filterboxCollection->getFilterByFullFiltername()
	 *
	 * @param $fullFilterName
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterInterface
	 */
	public function getFilterByFullFiltername($fullFilterName) {
		list($filterboxIdentifier, $filterIdentifier) = explode('.', $fullFilterName);
		$filterbox = $this->getFilterBoxCollection()->getFilterboxByFilterboxIdentifier($filterboxIdentifier);
		$filter = $filterbox->getFilterByFilterIdentifier($filterIdentifier);
		return $filter;
	}



	/**
	 * @return Tx_PtExtlist_Domain_Model_List_Row
	 */
	public function getRenderedCaptions() {
		return $this->getList()->getRenderedListHeader();
	}



	/**
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getRenderedAggregateRows() {
		return $this->getList()->getRenderedAggregateListData();
	}



	/**
	 * Return an array with all parts to display an integrated list
	 *
	 * @return array
	 */
	public function getAllListTemplateParts() {
		$viewParts = array(
			'config' => $this->getConfigurationBuilder(),
			'listHeader' => $this->getList()->getListHeader(),
			'listCaptions' => $this->getRenderedCaptions(),
			'listData' => $this->getRenderedListData(),
			'aggregateRows' => $this->getRenderedAggregateRows(),
			'pagerCollection' => $this->getPagerCollection(),
			'pager' => $this->getPager(),
			'filterbox' => $this->getFilterBoxCollection()->getItemByIndex(0),
		);

		return $viewParts;
	}



	/**
	 * Resets pager collection for list context
	 */
	public function resetPagerCollection() {
		$this->dataBackend->getPagerCollection()->reset();
	}



	/**
	 * Resets filterbox collection for list context
	 */
	public function resetFilterboxCollection() {
		$this->dataBackend->getFilterboxCollection()->reset();
	}


	/**
	 * @return \Tx_PtExtlist_Extbase_ExtbaseContext
	 */
	public function getExtBaseContext() {
		return $this->extBaseContext;
	}

}
?>