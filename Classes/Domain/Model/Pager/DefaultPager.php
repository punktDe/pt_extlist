<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>, Christoph Ehscheidt <ehscheidt@punkt.de>
*  All rights reserved
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
 * Class implements default pager for pt_extlist
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 */
class Tx_PtExtlist_Domain_Model_Pager_DefaultPager implements Tx_PtExtlist_Domain_Model_Pager_PagerInterface, Tx_PtExtlist_Domain_DataBackend_DataSource_DataSourceObserverInterface {
	
	/**
	 * TODO add some comment!
	 *
	 * @var int
	 */
	protected $currentPage = 1;
	
	
	
	/**
	 * TODO Add some comment
	 *
	 * @var array
	 */
	protected $settings = array();
	
	
	
	/**
	 * TODO add some comment
	 *
	 * @var int
	 */
	protected $itemsPerPage;
	
	
	
	/**
	 * TODO add some comment!
	 *
	 * @var int
	 */
	protected $totalItemCount;

	
	
	/**
     * TODO add some comment!
     * 
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::init()
	 *
	 */
	public function init() {}
	
	
	
	/**
     * TODO add some comment!
     * 
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getCurrentPage()
	 */
	public function getCurrentPage() {
		return $this->currentPage;
	}
	
	
	
	/**
     * TODO add some comment!
     * 
	 * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::setCurrentPage()
	 */
	public function setCurrentPage($page) {
		$this->currentPage = $page;
	}
	
	
	
	/**
     * TODO add some comment!
     * 
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::isEnabled()
	 *
	 */
	public function isEnabled() {
		if($this->settings[enabled] == 1) {
			return true;
		}	
		
		return false;
	}
	
	
	
	/**
     * TODO add some comment!
     * 
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getItemsPerPage()
	 *
	 */
	public function getItemsPerPage() {
		return $this->itemsPerPage;
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getPages()
	 */
	public function getPages() {
		$pages = array();

		$pageCount = ceil(($this->totalItemCount/$this->itemsPerPage));
		
		for($i=1; $i <= $pageCount; $i++) {
			$pages[$i] = $i;
		}

		return $pages;
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getFirstItemIndex()
	 */
	public function getFirstItemIndex() {
		return ( ($this->currentPage-1) * $this->itemsPerPage) + 1;
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getLastItemIndex()
	 */
	public function getLastItemIndex() {
		return (($this->currentPage-1) * $this->itemsPerPage) + $this->itemsPerPage;
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::injectSettings()
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
		
		if(array_key_exists('itemsPerPage', $settings)) {
			$this->itemsPerPage = $settings['itemsPerPage'];
		}
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_StateAdapter_GetPostVarInjectableInterface::injectGPVars()
	 */
	public function injectGPVars($GPVars) {
		$page = $GPVars['page'];
		
		// TODO: Check if $page is in a valid range
		// Problem: We don't know the totalItemCount yet
		
		$this->currentPage = (!$page ? 1 : (int)$page);
			
	}
	
	
	
	/**
     * TODO add some comment!
     * 
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::setItemsCount()
	 */
	public function setItemCount($itemCount) {
		$this->totalItemCount = $itemCount;
	}
	
	
	
	/**
     * TODO add some comment!
	 * 
	 * @see Tx_PtExtlist_Domain_DataBackend_DataSource_DataSourceObserverInterface
	 */
	public function updateItemCount($itemCount) {
		$this->setItemCount($itemCount);
	}
	
	
	
	/**
     * TODO add some comment!
     * 
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getItemCount()
	 */
	public function getItemCount() {
		return $this->totalItemCount;
	}
	
	
	
	/**
     * TODO add some comment!
     * 
	 * @see Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface::getSessionNamespace()
	 */
	public function getObjectNamespace() {
		return 'tx_ptextlist_pi1';
	}
	
	
	
	/**
     * TODO add some comment!
     * 
	 * @see Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface::loadFromSession()
	 */
	public function injectSessionData(array $sessionData) {
		if(array_key_exists('currentPage', $sessionData)) {
			$this->currentPage = $sessionData['currentPage'];
		}
		if(array_key_exists('totalItemCount', $sessionData)) {
			$this->totalItemCount = $sessionData['totalItemCount'];
		}
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface::persistToSession()
	 */
	public function persistToSession() {
		return array('currentPage' => $this->currentPage,
						'totalItemCount' => $this->totalItemCount);
	}
	
	
	
	/**
	 * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getShowFirstLink()
	 */
	public function getShowFirstLink() {
		if($this->settings['showFirstLink'] == 1)
			return true;
		return false;
	}
	
	
	
	/**
	 * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getShowLastLink()
	 */
	public function getShowLastLink() {
		if($this->settings['showLastLink'] == 1)
			return true;
		return false;
	}
	
	
	
	/**
	 * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getShowNextLink()
	 */
	public function getShowNextLink() {
		if($this->settings['showNextLink'] == 1)
			return true;
		return false;
	}
	
	
	
	/**
	 * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getShowPreviousLink()
	 */
	public function getShowPreviousLink() {
		if($this->settings['showPreviousLink'] == 1)
			return true;
		return false;
	}
	
	
	
	/**
     * TODO add some comment!
     * 
	 * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getLastPage()
	 */
	public function getLastPage() {
		return ceil(($this->totalItemCount/$this->itemsPerPage));
	}
	
	/**
     * TODO add some comment!
     * 
	 * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getFirstPage()
	 */
	public function getFirstPage() {
		return 1;
	}
	
	
	
	/**
     * TODO add some comment!
     * 
	 * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getPreviousPage()
	 */
	public function getPreviousPage() {
		if($this->currentPage > $this->getFirstPage()) {			
			return $this->currentPage - 1;
		}
		
		return $this->getFirstPage();
	}
	
	
	
	/**
     * TODO add some comment!
     * 
	 * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getNextPage()
	 */
	public function getNextPage() {
		if($this->currentPage < $this->getLastPage()) {
			return $this->currentPage + 1;
		}
		
		return $this->getLastPage();
	}

}
 
 
 ?>