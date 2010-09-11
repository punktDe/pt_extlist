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
 * Bookmark manager
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager {
	
	/**
	 * TODO: Refactor me --> use factory for bookmark managers
	 * Holds an array of instances for each list identifier
	 *
	 * @var array<Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager>
	 */
	protected static $instancesArray;
	
	
	
	/**
	 * Holds identifier of list
	 *
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * Holds an instance of a bookmark being currently applied to list
	 *
	 * @var Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark
	 */
	protected $currentBookmark;
	
	
	
	/**
	 * Holds an instance of a session persistence manager
	 *
	 * @var Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager
	 */
	protected $sessionPersistenceManager = null;
	
	
	
	
	/**
	 * Factory method for bookmark manager. 
	 * TODO: Refactor this, use factory for bookmark manager
	 *
	 * @param string $listIdentifier
	 * @return Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager
	 */
	public static function getInstanceByListIdentifier($listIdentifier) {
		tx_pttools_assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1284039926'));
		if (self::$instancesArray[$listIdentifier] === NULL) {
			self::$instancesArray[$listIdentifier] = new self($listIdentifier);
		}
		return self::$instancesArray[$listIdentifier];
	}
	
	
	
	/**
	 * Constructor for bookmark manager
	 *
	 * @param string $listIdentifier
	 */
	protected function __construct($listIdentifier) {
		$this->listIdentifier = $listIdentifier;
	}
	
	
	
	/**
	 * Injector for session persistence manager
	 *
	 * @param Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager $sessionPersistenceManager
	 */
	public function injectSessionPersistenceManager(Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager $sessionPersistenceManager) {
		$this->sessionPersistenceManager = $session;
	}
	
	
	
	/**
	 * Sets bookmark which is currently applied to list
	 *
	 * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $currentBookmark
	 */
	public function setCurrentBookmark(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $currentBookmark) {
		$this->currentBookmark = $currentBookmark;
	}
	
	
	
	/**
	 * Returns bookmark which is currently applied to list
	 *
	 * @return Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark
	 */
	public function getCurrentBookmark() {
		return $this->currentBookmark;
	}
	
	
	
	/**
	 * Adds content to bookmark which has to be stored in bookmark
	 *
	 * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark
	 */
	public function addContentToBookmark(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark) {
        
		$bookmark->setContent('');
	}
	
}
 
?>