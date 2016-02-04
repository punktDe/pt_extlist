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
 * @subpackage Model\Bookmark
 * @author David Vogt
 */
class Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManager
{
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
     * @var Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository
     */
    protected $bookmarkRepository;



    /**
     * Holds an instance of a BookmarkStrategy
     *
     * @var Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategyInterface
     */
    protected $bookmarkStrategy;



    /**
     * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
     */
    protected $configurationBuilder;



    /**
     * @var bool
     */
    protected $bookmarkIsRestored = false;



    /**
     * @var Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig
     */
    protected $bookmarkConfiguration;



    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $feUser;



    /**
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     */
    protected $feUserRepository;



    /**
     * Constructor for bookmark manager
     *
     * @param string $listIdentifier
     */
    public function __construct($listIdentifier)
    {
        $this->listIdentifier = $listIdentifier;
    }

    
    
    /**
     * Injector for session persistence manager
     *
     * @param Tx_PtExtbase_State_Session_SessionPersistenceManager $sessionPersistenceManager
     */
    public function _injectSessionPersistenceManager(Tx_PtExtbase_State_Session_SessionPersistenceManager $sessionPersistenceManager)
    {
        $this->sessionPersistenceManager = $sessionPersistenceManager;
    }
    
    
    
    /**
     * Injector for bookmark repository
     *
     * @param Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository $bookmarkRepository
     */
    public function injectBookmarkRepository(Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository $bookmarkRepository)
    {
        $this->bookmarkRepository = $bookmarkRepository;
    }



    /**
     * Injector for bookmark strategy
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategyInterface $bookmarkStrategy
     */
    public function injectBookmarkStrategy(Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategyInterface $bookmarkStrategy)
    {
        $this->bookmarkStrategy = $bookmarkStrategy;
    }



    /**
     * @param \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository $feUserRepository
     */
    public function injectFeUserRepository(\TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository $feUserRepository)
    {
        $this->feUserRepository = $feUserRepository;
    }



    /**
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     */
    public function _injectConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder)
    {
        $this->configurationBuilder = $configurationBuilder;
    }



    /**
     * Determines if request holds a bookmark to restore and in case there is forwards it to restoreBookmark
     *
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
     */
    public function processRequest(\TYPO3\CMS\Extbase\Mvc\RequestInterface $request)
    {
        if ($request->hasArgument('action') && $request->hasArgument('controller')) {
            if ($request->getArgument('action') == 'restore' && $request->getArgument('controller') == 'Bookmark' && $this->bookmarkIsRestored === false) {
                if ($request->hasArgument('bookmark')) {
                    $this->restoreBookmarkByUid($request->getArgument('bookmark'));
                }
            }
        }
    }


    /**
     * @param integer $bookmarkUid
     * @throws InvalidArgumentException
     */
    public function restoreBookmarkByUid($bookmarkUid)
    {
        $bookmark = $this->bookmarkRepository->findByUid($bookmarkUid);        /* @var $bookmark Tx_PtExtlist_Domain_Model_Bookmark_Bookmark */
        if ($bookmark != null) {
            $this->restoreBookmark($bookmark);
        } else {
            throw new InvalidArgumentException('No bookmark could be found for Bookmark-UID '.$bookmarkUid, 1372836569);
        }
    }



    /**
     * @param Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark
     */
    public function restoreBookmark(Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark)
    {
        //TODO: That smells to hell. CHANGE!
        $this->sessionPersistenceManager->init();
        $sessionData = $this->sessionPersistenceManager->getSessionData();
        $mergedSessionData = $this->bookmarkStrategy->mergeSessionAndBookmark($bookmark, $sessionData);
        $this->sessionPersistenceManager->setSessionData($mergedSessionData);
        $this->bookmarkIsRestored = true;
    }

    
    
    /**
     * Adds content to bookmark which has to be stored in bookmark
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark
     */
    public function addContentToBookmark(Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark)
    {
        $this->bookmarkStrategy->addContentToBookmark($bookmark, $this->configurationBuilder, $this->sessionPersistenceManager->getSessionData());
    }



    /**
     * @param Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark
     */
    public function storeBookmark(Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark)
    {
        $bookmark->setPid($this->bookmarkConfiguration->getBookmarkPid());
        $bookmark->setFeUser($this->feUser);
        $this->addContentToBookmark($bookmark);
        $this->bookmarkRepository->add($bookmark);
    }



    public function buildBookmarkConfig()
    {
        $this->bookmarkConfiguration = $this->configurationBuilder->buildBookmarkConfiguration();
    }



    public function initFeUser()
    {
        $userUid = $GLOBALS['TSFE']->fe_user->user['uid'];
        $this->feUser = $this->feUserRepository->findByUid($userUid);
    }



    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getBookmarksForCurrentConfigAndFeUser()
    {
        $allBookmarks = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

        if ($this->bookmarkConfiguration->getShowPrivateBookmarks() && $this->feUser != null) {
            $privateBookmarks = $this->bookmarkRepository->findPrivateBookmarksByFeUserAndListIdentifier($this->feUser, $this->listIdentifier);
            $this->addObjectsToObjectStorageByArray($allBookmarks, $privateBookmarks);
        }

        if ($this->bookmarkConfiguration->getShowGroupBookmarks() && $this->feUser != null && count($this->feUser->getUsergroup()) > 0) {
            $groupBookmarks = $this->bookmarkRepository->findGroupBookmarksByFeUserAndListIdentifier($this->feUser, $this->listIdentifier);
            $this->addObjectsToObjectStorageByArray($allBookmarks, $groupBookmarks);
        }
        if ($this->bookmarkConfiguration->getShowPublicBookmarks()) {
            $publicBookmarks = $this->bookmarkRepository->findPublicBookmarksByListIdentifier($this->listIdentifier);
            $this->addObjectsToObjectStorageByArray($allBookmarks, $publicBookmarks);
        }

        return $allBookmarks;
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


    public function removeBookmark(Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark)
    {
        $this->bookmarkRepository->remove($bookmark);
    }
}
