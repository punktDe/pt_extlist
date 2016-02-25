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
 * @see Tx_PtExtlist_Tests_Controller_BookmarkControllerTest
 */
class Tx_PtExtlist_Controller_BookmarkController extends Tx_PtExtlist_Controller_AbstractController
{
    /**
     * Holds an instance of bookmarks repository
     *
     * @var Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository
     */
    protected $bookmarkRepository;
    
    
    
    /**
     * Holds an instance of persistence manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager;
    
    
    
    /**
     * Holds an instance of bookmark manager
     *
     * @var Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManager
     */
    protected $bookmarkManager;



    /**
     * Holds an instance of bookmark manager factory
     *
     * @var Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManagerFactory
     */
    protected $bookmarkManagerFactory;

    
    
    /**
     * Holds configuration object for bookmark settings
     *
     * @var Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig
     */
    protected $bookmarkConfiguration;



    /**
     * Holds an instance of the FE-UserGroup Repository
     *
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository
     */
    protected $feUserGroupRepository;



    /**
     * @param Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository $bookmarkRepository
     */
    public function injectBookmarkRepository(Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository $bookmarkRepository)
    {
        $this->bookmarkRepository = $bookmarkRepository;
    }



    /**
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager
     */
    public function injectPersistenceManager(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }



    /**
     * @param \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository $feUserGroupRepository
     */
    public function injectFeUserGroupRepository(\TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository $feUserGroupRepository)
    {
        $this->feUserGroupRepository = $feUserGroupRepository;
    }



    /**
     * Initializes and sets dependent objects
     *
     */
    public function initializeAction()
    {
        parent::initializeAction();

        $this->bookmarkConfiguration = $this->configurationBuilder->buildBookmarkConfiguration();
    }


    
    /**
     * Renders show action for bookmark controller
     * 
     * @return string The rendered HTML source for this action
     */
    public function showAction()
    {
        $this->view->assign('bookmarkConfig', $this->bookmarkConfiguration);

        $feGroupsQuery = $this->feUserGroupRepository->createQuery();
        $feGroupsQuery->getQuerySettings()->setRespectStoragePage(false);
        $feGroups = $feGroupsQuery->execute();

        $this->view->assign('feGroups', $feGroups);

        $allBookmarks = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        
        if ($this->bookmarkConfiguration->getShowPublicBookmarks()) {
            $publicBookmarks = $this->bookmarkRepository->findPublicBookmarksByListIdentifier($this->listIdentifier);
            $this->addObjectsToObjectStorageByArray($allBookmarks, $publicBookmarks);
        }
        
        if ($this->bookmarkConfiguration->getShowPrivateBookmarks() && $this->feUser != null) {
            $privateBookmarks = $this->bookmarkRepository->findPrivateBookmarksByFeUserAndListIdentifier($this->feUser, $this->listIdentifier);
            $this->addObjectsToObjectStorageByArray($allBookmarks, $privateBookmarks);
        }
        
        if ($this->bookmarkConfiguration->getShowGroupBookmarks() && $this->feUser != null && count($this->feUser->getUsergroup()) > 0) {
            $groupBookmarks = $this->bookmarkRepository->findGroupBookmarksByFeUserAndListIdentifier($this->feUser, $this->listIdentifier);
            $this->addObjectsToObjectStorageByArray($allBookmarks, $groupBookmarks);
        }

        $userLoggedIn = 0;
        if ($this->feUser != null) {
            $userLoggedIn = 1;
        }

        $this->view->assign('userLoggedIn', $userLoggedIn);
        $this->view->assign('bookmarks', $allBookmarks);
    }


    /**
     * Saves the current session state as a bookmark
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $newBookmark
     * @return void
     */
    public function saveAction(Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $newBookmark)
    {
        if ($newBookmark->getType()!=Tx_PtExtlist_Domain_Model_Bookmark_Bookmark::PTEXTLIST_BOOKMARK_GROUP) {
            $newBookmark->setFeGroup(null);
        }

        $newBookmark->setFeUser($this->feUser);
        $newBookmark->setCreateDate(time());
        $newBookmark->setListId($this->bookmarkConfiguration->getListIdentifier());
        //TODO:get ExtbasePersistanceManager to use pid from bookmark-plugin configuration
        $newBookmark->setPid($this->bookmarkConfiguration->getBookmarkPid());
        $this->bookmarkManager->addContentToBookmark($newBookmark);
        $this->bookmarkRepository->add($newBookmark);
        $this->persistenceManager->persistAll();
        $this->forward('show');
    }


    /**
     * Action for deleting a bookmark
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark Bookmark to be deleted
     */
    public function deleteAction(Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark)
    {
        if ($bookmark->getFeUser()->getUid() == $this->feUser->getUid() || $this->bookmarkConfiguration->getUserCanDeleteAll()) {
            $this->bookmarkRepository->remove($bookmark);
            $this->persistenceManager->persistAll();
        } else {
            $this->addFlashMessage('You are not allowed to delete this bookmark.');
        }
        $this->forward('show');
    }



    /**
     * Action for restoring saved bookmarks
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark
     */
    public function restoreAction(Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark)
    {
        $this->forward('show');
    }
    
    














    /**
     * Action for creating a new bookmark
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark
     * @@ignorevalidation $bookmark
     * @return string The rendered new action
     */
    public function newAction(Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark=null)
    {
        // Assign groups for group bookmarks
        $groups = array('0' => ' ');
        if ($this->feUser != null && $this->userIsAllowedToCreateGroupBookmarks()) {
            foreach ($this->feUser->getUsergroups() as $userGroup) { /* @var $userGroup \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup */
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
     * @param Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark
     */
    public function createAction(Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark)
    {
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
    protected function getGroupIdsToShowBookmarksFor()
    {
        return $this->settings['bookmarks']['groupIdsToShowBookmarksFor'];
    }
    
    
    
    /**
     * Adds given elements of an array to given object storage
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $objectStorage
     * @param array $arrayToBeAdded
     */
    protected function addObjectsToObjectStorageByArray(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $objectStorage, $arrayToBeAdded)
    {
        foreach ($arrayToBeAdded as $key => $value) {
            $objectStorage->attach($value, $key);
        }
    }
    
    
    
    /**
     * Returns true, if user is allowed to create public bookmarks
     *
     * @return bool
     */
    protected function userIsAllowedToCreatePublicBookmarks()
    {
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
        foreach ($this->feUser->getUsergroups() as $usergroup) {
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
    protected function userIsAllowedToCreateGroupBookmarks()
    {
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
        foreach ($this->feUser->getUsergroups() as $usergroup) {
            if (in_array($usergroup->getUid(), $groupUidsAllowedToStoreGroupBookmarks)) {
                return true;
            }
        }

        return false;
    }
}
