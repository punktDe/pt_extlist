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
 * Class implementing bookmarks controller
 *
 * @package pt_extlist
 * @subpackage Controller
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Controller_BookmarksController extends Tx_PtExtlist_Controller_AbstractController {
    
    /**
     * Holds an instance of fe user repository
     *
     * @var Tx_Extbase_Domain_Repository_FrontendUserRepository
     */
    protected $feUserRepository = null;
    
    
    
    /**
     * Holds an instance of bookmarks repository
     *
     * @var Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository
     */
    protected $bookmarksRepository = null;
    
    
    
    /**
     * Holds an instance of currently logged in fe user
     *
     * @var Tx_Extbase_Domain_Model_FrontendUser
     */
    protected $feUser = null;
    
    
    
    protected $persistenceManager = null;
    

    
    /**
     * Constructor for bookmarks controller
     * 
     * @return void
     */
    public function __construct() {
    	parent::__construct();
    }
    
    
    
    /**
     * Injects the settings of the extension.
     *
     * @param array $settings Settings container of the current extension
     * @return void
     */
    public function injectSettings(array $settings) {
        /**
         * We cannot use parent::injectSettings here, as we have to 
         * create an instance of configuration builder and
         * data backend AFTER bookmark data has been written to session
         */
    	parent::injectSettings($settings, FALSE, FALSE);
        
        // TODO we create feUserRepository here to be able to set one manually when testing
        $this->initDependencies();
    }
    
    
    
    /**
     * Inits configuration builder and data backend
     *
     */
    protected function initConfiBuilderAndDataBackend() {
        $this->configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($this->settings);
        $this->dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilder);
    }
    
    
    
    /**
     * Initializes and sets dependent objects
     *
     */
    protected function initDependencies() {
        $this->feUserRepository  = t3lib_div::makeInstance('Tx_Extbase_Domain_Repository_FrontendUserRepository'); /* @var $feUserRepository Tx_Extbase_Domain_Repository_FrontendUserRepository */   
    	$this->bookmarksRepository = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository');
    	$this->bookmarksRepository->setBookmarksStoragePid($this->settings['bookmarks']['bookmarksPid']);

    	$this->persistenceManager = t3lib_div::makeInstance('Tx_Extbase_Persistence_Manager'); /* @var $persistenceManager Tx_Extbase_Persistence_Manager */
    }
    
    
    
    /**
     * Initializes controller
     * 
     * @return void
     */
    protected function initializeAction() {
        $feUserUid = $GLOBALS['TSFE']->fe_user->user['uid'];
        if ($feUserUid > 0) {
            $this->feUser = $this->feUserRepository->findByUid(intval($feUserUid));
        } 
    }
    
    
    
    /**
     * Renders show action for bookmark controller
     * 
     * @return string The rendered HTML source for this action
     */
    public function showAction() {
    	$this->initConfiBuilderAndDataBackend();
    	$allBookmarks = new Tx_Extbase_Persistence_ObjectStorage();
    	if ($this->showPublicBookmarks()) {
	    	$publicBookmarks = $this->bookmarksRepository->findPublicBookmarksByListIdentifier($this->listIdentifier);
	    	$this->addObjectsToObjectStorageByArray($allBookmarks, $publicBookmarks);
	    	$this->view->assign('publicBookmarks', $publicBookmarks);
    	}
    	if ($this->showUserBookmarks() && $this->feUser != null) {
    	    $userBookmarks = $this->bookmarksRepository->findBookmarksByFeUserAndListIdentifier($this->feUser, $this->listIdentifier);
    	    $this->addObjectsToObjectStorageByArray($allBookmarks, $userBookmarks);
    	    $this->view->assign('userBookmarks', $userBookmarks);
    	}
    	if ($this->showGroupBookmarks() && $this->feUser != null && count($this->feUser->getUsergroups()) > 0) {
    		$groupBookmarks = $this->bookmarksRepository->findBookmarksByFeUserGroupIdsAndListIdentifier($this->feUser, $this->getGroupIdsToShowBookmarksFor(), $this->listIdentifier);
    		$this->addObjectsToObjectStorageByArray($allBookmarks, $groupBookmarks);
    		$this->view->assign('groupBookmarks', $groupBookmarks);
    	}
    	$this->view->assign('allBookmarks', $allBookmarks);
    }
    
    
    
    /**
     * Makes a bookmark being processed
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark Bookmark to be processed
     */
    public function processAction(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark) {
        /**
         * Note: To make this work here, we change the order of
         * initializing data backend and configuration manager, 
         * as those objects require consistent session data, which can't be
         * changed afterwards!
         */
    	$bookmarkManager = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager::getInstanceByListIdentifier($this->listIdentifier);
        $bookmarkManager->injectSessionPersistenceManager(Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManagerFactory::getInstance());
        $bookmarkManager->setCurrentBookmark($bookmark);

        /**
         * Only now, session data is completed and we can initialize
         * configuration builder and data backend
         */
        $this->initConfiBuilderAndDataBackend();
        
    	$this->view->assign('processedBookmark', $bookmark);
    	$this->forward('show');
    }
    
    
    
    /**
     * Action for creating a new bookmark
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark
     * @dontvalidate $bookmark
     * @return string The rendered new action
     */
    public function newAction(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark=null) {
        $this->view->assign('bookmark', $bookmark);	
    }
    
    
    
    /**
     * Creates a new bookmark and forwards to show action
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark
     */
    public function createAction(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark) {
    	$this->initConfiBuilderAndDataBackend();
    	
    	$bookmark->setIsPublic(true);
    	$bookmark->setListId($this->listIdentifier);
    	
    	$bookmarkManager = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager::getInstanceByListIdentifier($this->listIdentifier);
    	$bookmarkManager->injectSessionPersistenceManager(Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManagerFactory::getInstance());
    	$bookmarkManager->addContentToBookmark($bookmark);
    	
    	$this->bookmarksRepository->add($bookmark);
    	$this->persistenceManager->persistAll();
    	
        $this->forward('show');
    }
    
    
    
    /**
     * Action for updating a bookmark
     */
    public function updateAction() {
    	
    }
    
    
    
    /**
     * Action for deleting a bookmark
     * 
     * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark Bookmark to be deleted
     */
    public function deleteAction(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark) {
    	if ($this->request->hasArgument('reallyDelete')) {
    		$this->bookmarksRepository->remove($bookmark);
    		$this->persistenceManager->persistAll();
    		$this->forward('show');
    	} else {
    		$this->view->assign('bookmark', $bookmark);
    	}
    }
    
    
    
    /**
     * Action for editing a bookmark
     */
    public function editAction() {
    	
    }
    
    
    
    protected function showUserBookmarks() {
    	// TODO read out settings here!
    	return true;
    }
    
    
    
    protected function showGroupBookmarks() {
    	// TODO read out settings here!
    	return true;
    }
    
    
    
    protected function showPublicBookmarks() {
    	// TODO read out settings here!
    	return true;
    }
    
    
    
    protected function getGroupIdsToShowBookmarksFor() {
    	// TODO read out settings here!
    	return '1,2,3,4,5';
    }
    
    
    
    /**
     * Adds given elements of an array to given object storage
     *
     * @param Tx_Extbase_Persistence_ObjectStorage $objectStorage
     * @param array $arrayToBeAdded
     */
    protected function addObjectsToObjectStorageByArray(Tx_Extbase_Persistence_ObjectStorage $objectStorage, array $arrayToBeAdded) {
    	foreach ($arrayToBeAdded as $key => $value) {
    		$objectStorage->attach($value, $key);
    	}
    }
    
}
?>