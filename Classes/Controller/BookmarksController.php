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
 * Class implementing bookmarks controller
 *
 * @package Controller
 * @author Michael Knoll 
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
    
    
    
    /**
     * Holds an instance of persistence manager
     *
     * @var Tx_Extbase_Persistence_Manager
     */
    protected $persistenceManager = null;
    
    
    
    /**
     * Holds an instance of boomark manager
     *
     * @var Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager
     */
    protected $bookmarkManager = null;
    
    
    
    /**
     * Holds configuration object for bookmark settings
     *
     * @var Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig
     */
    protected $bookmarkConfiguration = null;

    
    
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
    	parent::injectSettings($settings);
        
        // TODO we create feUserRepository here to be able to set one manually when testing
        $this->initDependencies();
    }
    
    
    
    /**
     * Initializes and sets dependent objects
     *
     */
    protected function initDependencies() {
        $this->configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($this->settings);
        $this->feUserRepository  = t3lib_div::makeInstance('Tx_Extbase_Domain_Repository_FrontendUserRepository'); /* @var $feUserRepository Tx_Extbase_Domain_Repository_FrontendUserRepository */
        $this->feUser = $this->feUserRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        
        // TODO create bookmark repository in bookmark manager and let it do the job
    	$this->bookmarksRepository = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository');
    	$this->bookmarksRepository->setBookmarksStoragePid($this->settings['bookmarks']['bookmarksPid']);

    	$this->bookmarkConfiguration = $this->configurationBuilder->buildBookmarksConfiguration();
    	
    	$this->bookmarkManager = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManagerFactory::getInstanceByConfigurationBuilder($this->configurationBuilder);
    	
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
    	$allBookmarks = new Tx_Extbase_Persistence_ObjectStorage();
    	
    	if ($this->bookmarkConfiguration->getShowPublicBookmarks()) {
	    	$publicBookmarks = $this->bookmarksRepository->findPublicBookmarksByListIdentifier($this->listIdentifier);
	    	$this->addObjectsToObjectStorageByArray($allBookmarks, $publicBookmarks);
	    	$this->view->assign('publicBookmarks', $publicBookmarks);
    	}
    	
    	if ($this->bookmarkConfiguration->getShowUserBookmarks() && $this->feUser != null) {
    	    $userBookmarks = $this->bookmarksRepository->findBookmarksByFeUserAndListIdentifier($this->feUser, $this->listIdentifier);
    	    $this->addObjectsToObjectStorageByArray($allBookmarks, $userBookmarks);
    	    $this->view->assign('userBookmarks', $userBookmarks);
    	}
    	
    	if ($this->bookmarkConfiguration->getShowGroupBookmarks() && $this->feUser != null && count($this->feUser->getUsergroups()) > 0) {
    		$groupBookmarks = $this->bookmarksRepository->findBookmarksByFeUserGroupIdsAndListIdentifier($this->feUser, $this->bookmarkConfiguration->getGroupIdsToShowBookmarksFor(), $this->listIdentifier);
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
    	 * Bookmark is not directly processed here but in DataBackend as we need to manipulate
    	 * session data which is too late here as session is already loaded and processed.
    	 */
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
    	// Assign groups for group bookmarks
    	$groups = array('0' => ' ');
    	if ($this->feUser != null && $this->userIsAllowedToCreateGroupBookmarks()) {
    	   foreach($this->feUser->getUsergroups() as $userGroup) { /* @var $userGroup Tx_Extbase_Domain_Model_FrontendUserGroup */ 
    	   	   $groups[$userGroup->getUid()] = $userGroup->getTitle();
    	   }
    	   $this->view->assign('groups', $groups);
    	}
    	
    	$this->view->assign('allowedToStorePublicBookmark', $this->userIsAllowedToCreatePublicBookmarks());
        $this->view->assign('bookmark', $bookmark);	
    }
    
    
    
    /**
     * Creates a new bookmark and forwards to show action
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark
     */
    public function createAction(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark) {
    	// Check whether user is allowed to create public bookmarks 
    	if ($this->request->hasArgument('isPublic') && $this->request->getArgument('isPublic') == '1') {
    		if ($this->userIsAllowedToCreatePublicBookmarks()) {
    		    $bookmark->setIsPublic(true);
    		} else {
    			// TODO show some message, that user is not allowed to create public bookmarks
    			$this->forward('show');
    		}
    	}
    	
    	
    	// Check, whether user is allowed to create group bookmarks
    	if ($this->request->hasArgument('feGroup') && $this->request->getArgument('feGroup') > 0) {
    		if ($this->userIsAllowedToCreateGroupBookmarks()) {
    			$bookmark->setFeGroup($this->request->getArgument('feGroup'));
    		} else {
    			$this->forward('show');
    		}
    	}
    	
    	
    	$bookmark->setPid($this->settings['bookmarks']['bookmarksPid']);
    	$this->bookmarkManager->addContentToBookmark($bookmark);
    	
    	$this->bookmarksRepository->add($bookmark);
    	$this->persistenceManager->persistAll();
    	
        $this->forward('show');
    }
    
    
    
    /**
     * Action for updating a bookmark
     */
    public function updateAction() {
    	// TODO implement me, if you have the time
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
    	// TODO implement me, if you have the time
    }
    
    
    
    /*****************************************************************************
     * Helper methods
     *****************************************************************************/
    
    /**
     * Returns a comma seperated list of group ids to show bookmarks for
     *
     * @return string Comma-seperated list of group ids
     */
    protected function getGroupIdsToShowBookmarksFor() {
    	return $this->settings['bookmarks']['groupIdsToShowBookmarksFor'];
    }
    
    
    
    /**
     * Adds given elements of an array to given object storage
     *
     * @param Tx_Extbase_Persistence_ObjectStorage $objectStorage
     * @param array $arrayToBeAdded
     */
    protected function addObjectsToObjectStorageByArray(Tx_Extbase_Persistence_ObjectStorage $objectStorage, $arrayToBeAdded) {
    	foreach ($arrayToBeAdded as $key => $value) {
    		$objectStorage->attach($value, $key);
    	}
    }
    
    
    
    /**
     * Returns true, if user is allowed to create public bookmarks
     *
     * @return bool
     */
    protected function userIsAllowedToCreatePublicBookmarks() {
    	if ($this->feUser == null) {
    		return false;
    	}
    	
    	// User UID is allowed to store public bookmarks?
    	$userUid = $this->feUser->getUid();
    	$userUidsAllowedToStorePublicBookmarks = explode(',', $this->bookmarkConfiguration->getFeUsersAllowedToEditPublic());
    	if (in_array($userUid, $userUidsAllowedToStorePublicBookmarks)) {
    		return true;
    	}

    	// group UID is allowed to store public bookmarks?
    	$groupUidsAllowedToStorePublicBookmarks = explode(',', $this->bookmarkConfiguration->getFeGroupsAllowedToEditPublic());
    	foreach($this->feUser->getUsergroups() as $usergroup) {
    		if (in_array($usergroup->getUid(), $groupUidsAllowedToStorePublicBookmarks)) {
    			return true;
    		}
    	}

    	return false;
    }
    
    
    
    /**
     * Returns true, if a user is allowed to create group bookmarks
     *
     * @return bool
     */
    protected function userIsAllowedToCreateGroupBookmarks() {
    	if ($this->feUser == null) {
    		return false;
    	}
    	
    	// User UID is allowed to store group bookmarks?
        $userUid = $this->feUser->getUid();
        $userUidsAllowedToStoreGroupBookmarks = explode(',', $this->bookmarkConfiguration->getFeUsersAllowedToEdit());
        if (in_array($userUid, $userUidsAllowedToStoreGroupBookmarks)) {
            return true;
        }
        
    	// group UID is allowed to store public bookmarks?
    	$groupUidsAllowedToStoreGroupBookmarks = explode(',', $this->bookmarkConfiguration->getFeGroupsAllowedToEdit());
    	
        $groupUids = $this->feUser->getUsergroups();
        foreach($this->feUser->getUsergroups() as $usergroup) {
            if (in_array($usergroup->getUid(), $groupUidsAllowedToStoreGroupBookmarks)) {
                return true;
            }
        }

        return false;
    }
    
}
?>