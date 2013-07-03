<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
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
 * @author David Vogt
 */
class Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager {
	
    /**
	 * Holds identifier of list
	 *
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * Holds an instance of a session persistence manager
	 *
	 * @var Tx_PtExtbase_State_Session_SessionPersistenceManager
	 */
	protected $sessionPersistenceManager;
	
	
	
	/**
	 * Holds an instance of a bookmark repository
	 *
	 * @var Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository
	 */
	protected $bookmarkRepository;



	/**
	 * Holds an instance of a BookmarkStrategy
	 *
	 * @var Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkStrategyInterface
	 */
	protected $bookmarkStrategy;



	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;



	/**
	 * @var bool
	 */
	protected $bookmarkIsRestored = FALSE;



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
	public function _injectSessionPersistenceManager(Tx_PtExtbase_State_Session_SessionPersistenceManager $sessionPersistenceManager) {
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
	 * Injector for bookmark strategy
	 *
	 * @param Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkStrategyInterface $bookmarkStrategy
	 */
	public function injectBookmarkStrategy(Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkStrategyInterface $bookmarkStrategy) {
		$this->bookmarkStrategy = $bookmarkStrategy;
	}



	/**
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function _injectConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->configurationBuilder = $configurationBuilder;
	}



	/**
	 * Determines if request holds a bookmark to restore and in case there is forwards it to restoreBookmark
	 *
	 * @param Tx_Extbase_MVC_RequestInterface $request
	 */
	public function processRequest(Tx_Extbase_MVC_RequestInterface $request){
		if ($request->getArgument('action') == 'restore' && $request->getArgument('controller') == 'Bookmarks' && $this->bookmarkIsRestored === FALSE){
			if($request->hasArgument('bookmark')){
				$this->restoreBookmarkByUid($request->getArgument('bookmark'));
			}
		}
	}


	/**
	 * @param int $bookmarkUid
	 * @throws InvalidArgumentException
	 */
	public function restoreBookmarkByUid($bookmarkUid){
		$bookmark = $this->bookmarkRepository->findByUid($bookmarkUid);
		if($bookmark != NULL){
			$this->restoreBookmark($bookmark);
		} else {
			throw new InvalidArgumentException('No bookmark could be found for Bookmark-UID '.$bookmarkUid, 1372836569);
		}
	}



	/**
	 * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark
	 */
	public function restoreBookmark(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark){
		//TODO: That smells to hell. CHANGE!
		$this->sessionPersistenceManager->init();
		$sessionData = $this->sessionPersistenceManager->getSessionData();
		$mergedSessionData = $this->bookmarkStrategy->mergeSessionAndBookmark($bookmark, $sessionData);
		$this->sessionPersistenceManager->setSessionData($mergedSessionData);
		$this->bookmarkIsRestored = TRUE;
	}

	
	
	/**
	 * Adds content to bookmark which has to be stored in bookmark
	 *
	 * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark
	 */
	public function addContentToBookmark(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark) {
		$this->bookmarkStrategy->addContentToBookmark($bookmark, $this->configurationBuilder, $this->sessionPersistenceManager->getSessionData());
	}
	
}