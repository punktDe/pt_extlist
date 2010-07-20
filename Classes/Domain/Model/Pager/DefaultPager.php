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
 */
class Tx_PtExtlist_Domain_Model_Pager_DefaultPager implements Tx_PtExtlist_Domain_Model_Pager_PagerInterface {
	
	protected $currentPage = 1;
	protected $settings = array();
	protected $itemsPerPage;
	protected $totalItemCount;
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getCurrentPage()
	 *
	 */
	public function getCurrentPage() {
		return $this->currentPage;
	}
	
	public function setCurrentPage($page) {
		$this->currentPage = $page;
	}
	
	public function isEnabled() {
		if($this->settings[enabled] == 1) {
			return true;
		}	
		
		return false;
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getItemsPerPage()
	 *
	 */
	public function getItemsPerPage() {
		return $this->itemsPerPage;
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getPages()
	 *
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
	 *
	 */
	public function getFirstItemIndex() {
		return ( ($this->currentPage-1) * $this->itemsPerPage) + 1;
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getLastItemIndex()
	 *
	 */
	public function getLastItemIndex() {
		return (($this->currentPage-1) * $this->itemsPerPage) + $this->itemsPerPage;
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::init()
	 *
	 */
	public function init() {
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::injectSettings()
	 *
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
		
		if(array_key_exists('itemsPerPage', $settings)) {
			$this->itemsPerPage = $settings['itemsPerPage'];
		}
	}
	
	/**
	 * 
	 * @see Tx_PtExtlist_Domain_StateAdapter_GetPostVarInjectableInterface::injectGPVars()
	 */
	public function injectGPVars($GPVars) {
		$page = $GPVars['page'];
	$old = $this->currentPage;
		switch($page) {
			case 'first':
				$this->currentPage = 1;
				break;
			case 'last':
				$this->currentPage = 4;
				break;
			case 'previous':
				$this->currentPage = ($this->currentPage > 1 ? $this->currentPage-1 : 1);
				break;
			case 'next':
				$this->currentPage = ($this->currentPage < 4 ? $this->currentPage+1 : 4);
				break;
			default:
				$this->currentPage = (!$page ? 1 : (int)$page);
			
		}

	}
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::setItemsCount()
	 *
	 */
	public function setItemCount($itemCount) {
		$this->totalItemCount = $itemCount;
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getItemCount()
	 *
	 */
	public function getItemCount() {
		return $this->totalItemCount;
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface::getSessionNamespace()
	 *
	 */
	public function getObjectNamespace() {
		return 'tx_ptextlist_pi1';
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface::loadFromSession()
	 *
	 * @param array $sessionData
	 */
	public function injectSessionData(array $sessionData) {
		if(array_key_exists('currentPage', $sessionData)) {
			$this->currentPage = $sessionData['currentPage'];
		}
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface::persistToSession()
	 *
	 */
	public function persistToSession() {
		return array('currentPage' => $this->currentPage);
	}
	
	public function getShowFirstLink() {
		if($this->settings['showFirstLink'] == 1)
			return true;
		return false;
	}
	public function getShowLastLink() {
		if($this->settings['showLastLink'] == 1)
			return true;
		return false;
	}
	public function getShowNextLink() {
		if($this->settings['showNextLink'] == 1)
			return true;
		return false;
	}
	public function getShowPreviousLink() {
		if($this->settings['showPreviousLink'] == 1)
			return true;
		return false;
	}
	
	

}
 
 
 ?>