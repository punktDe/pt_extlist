<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
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
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getItemsPerPage()
	 *
	 */
	public function getItemsPerPage() {
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getPages()
	 *
	 */
	public function getPages() {
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
	public function injectSettings() {
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::setItemsCount()
	 *
	 */
	public function setItemsCount() {
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface::getSessionNamespace()
	 *
	 */
	public function getSessionNamespace() {
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface::loadFromSession()
	 *
	 * @param array $sessionData
	 */
	public function loadFromSession(array $sessionData) {
	}
	
	/**
	 * @see Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface::persistToSession()
	 *
	 */
	public function persistToSession() {
	}

}
 
 
 ?>