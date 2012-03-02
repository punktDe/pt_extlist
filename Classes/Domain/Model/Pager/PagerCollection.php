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
 * A collection to manage a bunch of pagers.
 *
 * @author Daniel Lienert
 * @author Christoph Ehscheidt
 * @package Domain
 * @subpackage Model\Pager
 */
class Tx_PtExtlist_Domain_Model_Pager_PagerCollection extends Tx_PtExtbase_Collection_Collection 
			implements Tx_PtExtbase_State_Session_SessionPersistableInterface, Tx_PtExtbase_State_GpVars_GpVarsInjectableInterface {


	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;



	/**
	 * Holds the current page index.
	 * New pagers need to know the current page.
	 *
	 * @var int
	 */
	protected $currentPage = 1;



	/**
	 * Shows if one of the pagers is enabled.
	 *
	 * @var boolean
	 */
	protected $enabled = false;



	/**
	 * Holds a instance of the persitence manager.
	 *
	 * @var Tx_PtExtbase_State_Session_SessionPersistenceManager
	 */
	protected $sessionPersistenceManager;



	/**
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->configurationBuilder = $configurationBuilder;
	}

	
	
	/**
	 * @param Tx_PtExtbase_State_Session_SessionPersistenceManager $sessionPersistanceManager
	 */
	public function injectSessionPersistenceManager(Tx_PtExtbase_State_Session_SessionPersistenceManager $sessionPersistenceManager) {
		$this->sessionPersistenceManager = $sessionPersistenceManager;		
	}
	
	

	/**
	 * Adds a pager to the collection.
	 *
	 * @param Tx_PtExtlist_Domain_Model_Pager_PagerInterface $pager
	 */
	public function addPager(Tx_PtExtlist_Domain_Model_Pager_PagerInterface $pager) {

		$pager->setCurrentPage($this->currentPage);

		// As if one pager is enabled, the collection is marked a enabled.
		if($pager->isEnabled()) {
			$this->enabled = true;
		}

		$this->addItem($pager, $pager->getPagerIdentifier());
	}



	/**
	 * Sets the current page index to all pagers.
	 *
	 * @param int $pageIndex
	 */
	public function setCurrentPage($pageIndex) {
		$this->currentPage = (int)$pageIndex;

		foreach($this->itemsArr as $id => $pager) { /* @var $pager Tx_PtExtlist_Domain_Model_Pager_PagerInterface */
			$pager->setCurrentPage($pageIndex);
		}
	}



	/**
	 * Resets every pager to start page.
	 *
	 */
	public function reset() {
		$this->setCurrentPage(1);
	}



	/**
	 * Returns the current page which is valid for all pagers.
	 *
	 * @return int
	 */
	public function getCurrentPage() {
		// If number of items has changed between to requests, we can check here, whether we still have enough items to be on recent page
		if ($this->currentPage > $this->getLastPage()) {
			$this->reset();
		}
		return $this->currentPage;
	}



	/**
	 * Returns true if any of the pages is enabled.
	 * @return boolean
	 */
	public function isEnabled() {
		return $this->enabled;
	}



	/**
	 * @param bool $enabled
	 */
	public function setEnabled($enabled) {
		$this->enabled = $enabled;
	}



	/**
	 * Sets the total item count for each pager in the collection.
	 * Could be used by a list to inject the amount of rows.
	 *
	 * @param int $itemCount The amount of items.
	 */
	public function setItemCount($itemCount) {
		foreach($this as $pagerId => $pager) {
			$pager->setItemCount($itemCount);
		}
	}


	/**
	 * (non-PHPdoc)
	 * @see Tx_PtExtbase_State_IdentifiableInterface::getObjectNamespace()
	 */
	public function getObjectNamespace() {
		return $this->configurationBuilder->getListIdentifier() . '.pagerCollection';
	}



	/**
	 * (non-PHPdoc)
	 * @see Tx_PtExtbase_State_GpVars_GpVarsInjectableInterface::injectGPVars()
	 */
	public function injectGPVars($GPVars) {
		if(array_key_exists('page', $GPVars)) {
			$this->currentPage = (int)$GPVars['page'];
		}
	}



	/**
	 * (non-PHPdoc)
	 * @see Tx_PtExtbase_State_Session_SessionPersistableInterface::injectSessionData()
	 */
	public function injectSessionData(array $sessionData) {
		if(array_key_exists('page', $sessionData)) {
			$this->currentPage = (int)$sessionData['page'];
		}
	}



	/**
	 * (non-PHPdoc)
	 * @see Tx_PtExtbase_State_Session_SessionPersistableInterface::persistToSession()
	 */
	public function persistToSession() {
		if($this->currentPage > 1) {
			return array('page' => $this->currentPage);
		} else {
			/*
			 *  Page 1 is default therefore we dont need it in the sesssion
			 *
			 *  Don't change this, this belongs to RealUrl configuration if everything is
			 *  put into URL.
			 */
			$this->sessionPersistenceManager->removeSessionDataByNamespace($this->getObjectNamespace());
		}
	}



	/**********************************************************
	 *
	 * Implementing parts of the pager interface.
	 * Delegate to the first pager in this collection.
	 *
	 **********************************************************/

	/**
	 * Returns the index of the first page.
	 * @return int Index of first page
	 */
	public function getFirstItemIndex() {
		return $this->getItemByIndex(0)->getFirstItemIndex();
	}



	/**
	 * Returns the index of the last page.
	 * @return int Index of last page
	 */
	public function getLastItemIndex() {
		return $this->getItemByIndex(0)->getLastItemIndex();
	}





	/**
	 * Returns the total item count.
	 * @return int The total item count.
	 */
	public function getItemCount() {
		return $this->getItemByIndex(0)->getItemCount();
	}



	/**
	 * Returns the items per page.
	 * @return int Amount of items per page.
	 */
	public function getItemsPerPage() {
		return $this->getItemByIndex(0)->getItemsPerPage();
	}



	/**
	 * Set itmesPerPage
	 *
	 * @param int $itemsPerPage
	 */
	public function setItemsPerPage($itemsPerPage) {
		foreach($this->itemsArr as $pager) {
			$pager->setItemsPerPage($itemsPerPage);
		}
	}



	/**
	 * Set the page by row index
	 *
	 * @param int $rowIndex
	 */
	public function setPageByRowIndex($rowIndex) {
		$rowIndex++; // rowindex = 0 means item 1
		$pageIndex = ceil($rowIndex / $this->getItemsPerPage());
		$this->setCurrentPage($pageIndex);
	}



	/**
	 * Returns an array with the index=>pageNumber pairs
	 * @return array PageNumbers
	 */
	public function getPages() {
		return $this->getItemByIndex(0)->getPages();
	}


	/**
	 * Return pager by Identifier
	 *
	 * @param string $pagerIdentifier
	 * @throws Exception
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerInterface
	 */
	public function getPagerByIdentifier($pagerIdentifier) {
		if(!$this->hasItem($pagerIdentifier)) {
			throw  new Exception('No pager configuration with id '.$pagerIdentifier.' found. 1282216891');
		}

		return $this->getItemById($pagerIdentifier);
	}


	/**
	 * Returns the last page index
	 * @return int Index of last page
	 */
	public function getLastPage() {
		return $this->getItemByIndex(0)->getLastPage();
	}



	/**
	 * Returns the first page index
	 * @return int Index of first page
	 */
	public function getFirstPage() {
		return $this->getItemByIndex(0)->getFirstPage();
	}



	/**
	 * Returns the previous page index
	 * @return int Index of previous page
	 */
	public function getPreviousPage() {
		return $this->getItemByIndex(0)->getPreviousPage();
	}



	/**
	 * Returns the last next index
	 * @return int Index of next page
	 */
	public function getNextPage() {
		return $this->getItemByIndex(0)->getNextPage();
	}

}
?>