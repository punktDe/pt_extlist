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
 * @author David "Dex" Vogt
 * @see Tx_PtExtlist_Tests_Controller_BookmarksControllerTest
 */
class Tx_PtExtlist_Controller_BookmarksController extends Tx_PtExtlist_Controller_AbstractController {
    
    /**
     * Holds an instance of bookmarks repository
     *
     * @var Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository
     */
    protected $bookmarkRepository;
    
    
    
    /**
     * Holds an instance of persistence manager
     *
     * @var Tx_Extbase_Persistence_Manager
     */
    protected $persistenceManager;
    
    
    
    /**
     * Holds an instance of bookmark manager
     *
     * @var Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager
     */
    protected $bookmarkManager;



    /**
     * Holds an instance of bookmark manager factory
     *
     * @var Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManagerFactory
     */
    protected $bookmarkManagerFactory;

    
    
    /**
     * Holds configuration object for bookmark settings
     *
     * @var Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig
     */
    protected $bookmarkConfiguration;



	/**
	 * Holds an instance of the FE-UserGroup Repository
	 *
	 * @var Tx_Extbase_Domain_Repository_FrontendUserGroupRepository
	 */
	protected $feUserGroupRepository;



    /**
     * @param Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository $bookmarkRepository
     */
    public function injectBookmarkRepository (Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository $bookmarkRepository){
        $this->bookmarkRepository = $bookmarkRepository;
    }



	/**
	 * @param Tx_Extbase_Persistence_Manager $persistenceManager
	 */
	public function injectPersistenceManager (Tx_Extbase_Persistence_Manager $persistenceManager){
		$this->persistenceManager = $persistenceManager;
	}



	/**
	 * @param Tx_Extbase_Domain_Repository_FrontendUserGroupRepository $feUserGroupRepository
	 */
	public function injectFeUserGroupRepository (Tx_Extbase_Domain_Repository_FrontendUserGroupRepository $feUserGroupRepository){
		$this->feUserGroupRepository = $feUserGroupRepository;
	}



    /**
     * Initializes and sets dependent objects
     *
     */
    public function initializeAction() {

        parent::initializeAction();

        $this->bookmarkConfiguration = $this->configurationBuilder->buildBookmarksConfiguration();
    }


    
    /**
     * Renders show action for bookmark controller
     * 
     * @return string The rendered HTML source for this action
     */
    public function showAction() {

		$this->view->assign('bookmarksConfig', $this->bookmarkConfiguration);

		$feGroupsQuery = $this->feUserGroupRepository->createQuery();
		$feGroupsQuery->getQuerySettings()->setRespectStoragePage(FALSE);
		$feGroups = $feGroupsQuery->execute();

		$this->view->assign('feGroups', $feGroups);

        $allBookmarks = new Tx_Extbase_Persistence_ObjectStorage();
        
        if ($this->bookmarkConfiguration->getShowPublicBookmarks()) {
            $publicBookmarks = $this->bookmarkRepository->findPublicBookmarksByListIdentifier($this->listIdentifier);
            $this->addObjectsToObjectStorageByArray($allBookmarks, $publicBookmarks);
            $this->view->assign('publicBookmarks', $publicBookmarks);
        }
        
        if ($this->bookmarkConfiguration->getShowPrivateBookmarks() && $this->feUser != NULL) {
            $userBookmarks = $this->bookmarkRepository->findPrivateBookmarksByFeUserAndListIdentifier($this->feUser, $this->listIdentifier);
            $this->addObjectsToObjectStorageByArray($allBookmarks, $userBookmarks);
            $this->view->assign('userBookmarks', $userBookmarks);
        }
        
        if ($this->bookmarkConfiguration->getShowGroupBookmarks() && $this->feUser != NULL && count($this->feUser->getUsergroup()) > 0) {
            $groupBookmarks = $this->bookmarkRepository->findGroupBookmarksByFeUserAndListIdentifier($this->feUser, $this->listIdentifier);
            $this->addObjectsToObjectStorageByArray($allBookmarks, $groupBookmarks);
            $this->view->assign('groupBookmarks', $groupBookmarks);
        }
        
        $this->view->assign('bookmarks', $allBookmarks);

    }


	/**
	 * Saves the current session state as a bookmark
	 *
	 * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $newBookmark
	 * @return void
	 */
    public function saveAction(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $newBookmark){

		if($newBookmark->getType()!=Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark::PTEXTLIST_BOOKMARK_GROUP){
			$newBookmark->setFeGroup(NULL);
		}

		$newBookmark->setFeUser($this->feUser);
        $newBookmark->setCreateDate(time());
        $newBookmark->setListId($this->bookmarkConfiguration->getListIdentifier());
        //TODO:get ExtbasePersistanceManager to use pid from bookmark-plugin configuration
        $newBookmark->setPid($this->bookmarkConfiguration->getBookmarksPid());
        $this->bookmarkManager->addContentToBookmark($newBookmark);

        $this->bookmarkRepository->add($newBookmark);
        $this->persistenceManager->persistAll();
        $this->forward('show');
    }


    /**
     * Action for deleting a bookmark
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark Bookmark to be deleted
     */
    public function deleteAction(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark) {

		if($bookmark->getFeUser()->getUid() == $this->feUser->getUid() || $this->bookmarkConfiguration->getUserCanDeleteAll()) {
			$this->bookmarkRepository->remove($bookmark);
			$this->persistenceManager->persistAll();
		} else {
			$this->flashMessageContainer->add('You are not allowed to delete this bookmark.');
		}
		$this->forward('show');
	}



    /**
     * Action for restoring saved bookmarks
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark
     */
    public function restoreAction(Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark $bookmark){
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