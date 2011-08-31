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
 * Bookmark manager
 *
 * @package Domain
 * @subpackage Model\Bookmarks
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager {
	
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
	 * @var Tx_PtExtbase_State_Session_SessionPersistenceManager
	 */
	protected $sessionPersistenceManager = null;
	
	
	
	/**
	 * Holds an instance of a bookmark repository
	 *
	 * @var Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository
	 */
	protected $bookmarkRepository = null;
	
	
	
	/**
	 * Constructor for bookmark manager
	 *
	 * @param string $listIdentifier
	 */
	public function __construct($listIdentifier) {
		$this->listIdentifier = $listIdentifier;
	}
	
	
	
	/**
	 * Injector for session persistence manager
	 *
	 * @param Tx_PtExtbase_State_Session_SessionPersistenceManager $sessionPersistenceManager
	 */
	public function injectSessionPersistenceManager(Tx_PtExtbase_State_Session_SessionPersistenceManager $sessionPersistenceManager) {
		$this->sessionPersistenceManager = $sessionPersistenceManager;
	}
	
	
	
	/**
	 * Injector for bookmark repository
	 *
	 * @param Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository $bookmarkRepository
	 */
	public function injectBookmarkRepository(Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository $bookmarkRepository) {
		$this->bookmarkRepository = $bookmarkRepository;
	}
	
	
	
	/**
	 * Processes bookmark from GP vars given for current request
	 *
	 */
	public function processBookmark() {
		$gpVarAdapter = Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory::getInstance();
		$gpVars = $gpVarAdapter->extractGpVarsByNamespace('tx_ptextlist_pi1');
		if ($gpVars['controller'] == 'Bookmarks' && $gpVars['action'] == 'process') {
			$bookmarkId = $gpVars['bookmark'];
			$bookmark = $this->bookmarkRepository->findByUid($bookmarkId);
			if (!is_null($bookmark)) {
			    $this->setCurrentBookmark($bookmark);
			}
		}
	}
	
	
	
	
	/**
	 * Sets bookmark which is currently applied to list
	 *
	 * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $currentBookmark
	 */
	public function setCurrentBookmark(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $currentBookmark) {
		$this->currentBookmark = $currentBookmark;
		$this->sessionPersistenceManager->processBookmark($this->currentBookmark);
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
		// TODO use object instead of array to save session data in bookmark
        $filterboxesContent = serialize(array('filters' => $this->sessionPersistenceManager->getSessionDataByNamespace($this->getFilterboxCollectionNamespace())));
		$bookmark->setContent($filterboxesContent);
	}
	
	
	
	/**
	 * Returns session namespace string for filters
	 *
	 * @return string
	 */
	protected function getFilterboxCollectionNamespace() {
		return 'tx_ptextlist_pi1.' . $this->listIdentifier . '.filters';
	}
	
}
 
?>