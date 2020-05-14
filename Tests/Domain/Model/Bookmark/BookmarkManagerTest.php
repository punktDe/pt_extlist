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
 * Testcase for bookmark manager
 *
 * @package Tests
 * @subpackage Domain\Model\Bookmark
 * @author Michael Knoll
 * @see BookmarkManager
 */
class Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * Holds an array of settings
     *
     * @var array
     */
    protected $settings = [

                'listIdentifier' => 'Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerTest',

                'prototype' => [

                    'backend' => [
                        'mysql' => [
                            'dataBackendClass' => 'MySqlDataBackend_MySqlDataBackend',
                            'dataMapperClass' => 'Mapper_ArrayMapper',
                            'queryInterpreterClass' => 'MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',


                        ]
                    ],
                ],
                
                'listConfig' => [
                     'Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerTest' => [
                        
                        'backendConfig' => [
                                'dataBackendClass' => 'Typo3DataBackend_Typo3DataBackend',
                                'dataMapperClass' => 'Mapper_ArrayMapper',
                                'queryInterpreterClass' => 'MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
                        ],
                            
                        'bookmarks' => [
                            'bookmarkPid' => 30,
                            'createPublicBookmarks' => 1,
                            'createPrivateBookmarks' => 1,
                            'createGroupBookmarks' => 1,
                            'showPublicBookmarks' => 1,
                            'showPrivateBookmarks' => 1,
                            'showGroupBookmarks' => 1,
                            'userCanDeleteAll' => 0,
                            'groupIdsToShowBookmarksFor' => '4,5,6'
                        ],
                     ]
                ]
    ];



    protected $bookmarkUid = 5;


    /**
     * @var string
     */
    protected $proxyClass;



    /**
     * @var BookmarkManager
     */
    protected $proxy;



    public function setUp()
    {
        $this->proxyClass = $this->buildAccessibleProxy('BookmarkManager');
        $this->proxy = new $this->proxyClass($this->settings['listIdentifier']);
        //$this->proxy->_set('listIdentifier', $this->settings['listIdentifier']);
        $this->initDefaultConfigurationBuilderMock();
    }
            


    /**
     * @test
     */
    public function classExists()
    {
        $this->assertTrue(class_exists('BookmarkManager'));
    }



    /**
     * @test
     * @expectedException   InvalidArgumentException
     * @expectedExceptionMessage No bookmark could be found for Bookmark-UID 123
     */
    public function restoreBookmarkByUidThrowsInvalidArgumentExceptionForNonExistingBookmarkUid()
    {
        $bookmarkRepositoryMock = $this->getMockBuilder('Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository')
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $bookmarkRepositoryMock->expects($this->once())
            ->method('findByUid')
            ->will($this->returnValue(null));

        $this->proxy->_set('bookmarkRepository', $bookmarkRepositoryMock);
        $this->proxy->restoreBookmarkByUid(123);
    }



    /**
     * @test
     */
    public function restoreBookmarkByUidRestoresFoundBookmark()
    {
        $bookmarkMock = $this->getMockBuilder('Bookmark')
            ->disableOriginalClone()
            ->getMock();

        $bookmarkRepositoryMock = $this->getMockBuilder('Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository')
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $bookmarkRepositoryMock->expects($this->once())
            ->method('findByUid')
            ->with($this->bookmarkUid)
            ->will($this->returnValue($bookmarkMock));

        $proxyMock = $this->getMockBuilder($this->proxyClass)
            ->setMethods(['restoreBookmark'])
            ->disableOriginalConstructor()
            ->getMock();
        $proxyMock->expects($this->once())
            ->method('restoreBookMark')
            ->with($bookmarkMock);

        $proxyMock->_set('bookmarkRepository', $bookmarkRepositoryMock);
        $proxyMock->restoreBookmarkByUid($this->bookmarkUid);
    }



    /**
     * @test
     */
    public function restoreBookmarkRestoresBookmark()
    {
        $bookmarkMock = $this->getMockBuilder('Bookmark')
        ->disableOriginalClone()
         ->getMock();

        $sessionPersistenceManagerMock = $this->getMockBuilder('PunktDe_PtExtbase_State_Session_SessionPersistenceManager')
            ->setMethods(['init', 'getSessionData', 'setSessionData'])
            ->disableOriginalConstructor()
            ->getMock();
        $sessionPersistenceManagerMock->expects($this->once())
            ->method('init');
        $sessionPersistenceManagerMock->expects($this->once())
            ->method('getSessionData')
            ->will($this->returnValue(['sessionData']));
        $sessionPersistenceManagerMock->expects($this->once())
            ->method('setSessionData')
            ->with(['mergedSessionData']);

        $bookmarkStrategyMock = $this->getMockBuilder('BookmarkStrategy')
            ->setMethods(['mergeSessionAndBookmark'])
            ->getMock();
        $bookmarkStrategyMock->expects($this->once())
            ->method('mergeSessionAndBookmark')
            ->with($bookmarkMock, ['sessionData'])
            ->will($this->returnValue(['mergedSessionData']));

        $this->proxy->_set('sessionPersistenceManager', $sessionPersistenceManagerMock);
        $this->proxy->_set('bookmarkStrategy', $bookmarkStrategyMock);

        $this->proxy->restoreBookmark($bookmarkMock);
        $this->assertEquals(true, $this->proxy->_get('bookmarkIsRestored'));
    }



    /**
     * @test
     */
    public function processRequestWithoutArgumentActionDoesNotCallBookmarkRepository()
    {
        $requestMock = $this->getMockBuilder('\TYPO3\CMS\Extbase\Mvc\Request')
            ->setMethods(['hasArgument'])
            ->disableOriginalConstructor()
            ->getMock();
        $requestMock->expects($this->once())
            ->method('hasArgument')
            ->with('action')
            ->will($this->returnValue(false));

        $proxyMock = $this->getMockBuilder($this->proxyClass)
            ->setMethods(['restoreBookmarkByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $proxyMock->expects($this->never())
            ->method('restoreBookmarkByUid');

        $proxyMock->processRequest($requestMock);
    }


    /**
     * @test
     */
    public function processRequestWithoutArgumentControllerDoesNotCallBookmarkRepository()
    {
        $requestMock = $this->getMockBuilder('\TYPO3\CMS\Extbase\Mvc\Request')
            ->setMethods(['hasArgument'])
            ->disableOriginalConstructor()
            ->getMock();
        $requestMock->expects($this->exactly(2))
            ->method('hasArgument')
            ->with($this->logicalOr('action', 'controller'))
            ->will($this->returnCallback([$this, 'hasArgumentReturnFalseForController']));

        $proxyMock = $this->getMockBuilder($this->proxyClass)
            ->setMethods(['restoreBookmarkByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $proxyMock->expects($this->never())
            ->method('restoreBookmarkByUid');

        $proxyMock->processRequest($requestMock);
    }



    /**
     * @test
     */
    public function processRequestWithWrongArgumentActionDoesNotCallBookmarkRepository()
    {
        $requestMock = $this->getMockBuilder('\TYPO3\CMS\Extbase\Mvc\Request')
            ->setMethods(['hasArgument', 'getArgument'])
            ->disableOriginalConstructor()
            ->getMock();
        $requestMock->expects($this->exactly(2))
            ->method('hasArgument')
            ->with($this->logicalOr('action', 'controller'))
            ->will($this->returnValue(true));
        $requestMock->expects($this->once())
            ->method('getArgument')
            ->with('action')
            ->will($this->returnValue('notRestore'));

        $proxyMock = $this->getMockBuilder($this->proxyClass)
            ->setMethods(['restoreBookmarkByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $proxyMock->expects($this->never())
            ->method('restoreBookmarkByUid');

        $proxyMock->processRequest($requestMock);
    }



    /**
     * @test
     */
    public function processRequestWithWrongArgumentControllerDoesNotCallBookmarkRepository()
    {
        $requestMock = $this->getMockBuilder('\TYPO3\CMS\Extbase\Mvc\Request')
            ->setMethods(['hasArgument', 'getArgument'])
            ->disableOriginalConstructor()
            ->getMock();
        $requestMock->expects($this->exactly(2))
            ->method('hasArgument')
            ->with($this->logicalOr('action', 'controller'))
            ->will($this->returnValue(true));
        $requestMock->expects($this->exactly(2))
            ->method('getArgument')
            ->with($this->logicalOr('action', 'controller'))
            ->will($this->returnCallback([$this, 'getArgumentReturnWrongArgumentForController']));

        $proxyMock = $this->getMockBuilder($this->proxyClass)
            ->setMethods(['restoreBookmarkByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $proxyMock->expects($this->never())
            ->method('restoreBookmarkByUid');

        $proxyMock->processRequest($requestMock);
    }



    /**
     * @test
     */
    public function processRequestWithAlreadyRestoredBookmarkDoesNotCallBookmarkRepository()
    {
        $requestMock = $this->getMockBuilder('\TYPO3\CMS\Extbase\Mvc\Request')
            ->setMethods(['hasArgument', 'getArgument'])
            ->disableOriginalConstructor()
            ->getMock();
        $requestMock->expects($this->exactly(2))
            ->method('hasArgument')
            ->with($this->logicalOr('action', 'controller'))
            ->will($this->returnValue(true));
        $requestMock->expects($this->exactly(2))
            ->method('getArgument')
            ->with($this->logicalOr('action', 'controller'))
            ->will($this->returnCallback([$this, 'getArgumentReturn']));

        $proxyMock = $this->getMockBuilder($this->proxyClass)
            ->setMethods(['restoreBookmarkByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $proxyMock->expects($this->never())
            ->method('restoreBookmarkByUid');

        $proxyMock->_set('bookmarkIsRestored', true);
        $proxyMock->processRequest($requestMock);
    }



    /**
     * @test
     */
    public function processRequestWithWithoutArgumentBookmarkDoesNotCallBookmarkRepository()
    {
        $requestMock = $this->getMockBuilder('\TYPO3\CMS\Extbase\Mvc\Request')
            ->setMethods(['hasArgument', 'getArgument'])
            ->disableOriginalConstructor()
            ->getMock();
        $requestMock->expects($this->exactly(3))
            ->method('hasArgument')
            ->with($this->logicalOr($this->logicalOr('action', 'controller'), 'bookmark'))
            ->will($this->returnCallback([$this, 'hasArgumentReturnFalseForBookmark']));
        $requestMock->expects($this->exactly(2))
            ->method('getArgument')
            ->with($this->logicalOr('action', 'controller'))
            ->will($this->returnCallback([$this, 'getArgumentReturn']));

        $proxyMock = $this->getMockBuilder($this->proxyClass)
            ->setMethods(['restoreBookmarkByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $proxyMock->expects($this->never())
            ->method('restoreBookmarkByUid');

        $proxyMock->processRequest($requestMock);
    }



    /**
     * @test
     */
    public function processRequestProcessesRequest()
    {
        $requestMock = $this->getMockBuilder('\TYPO3\CMS\Extbase\Mvc\Request')
            ->setMethods(['hasArgument', 'getArgument'])
            ->disableOriginalConstructor()
            ->getMock();
        $requestMock->expects($this->exactly(3))
            ->method('hasArgument')
            ->with($this->logicalOr($this->logicalOr('action', 'controller'), 'bookmark'))
            ->will($this->returnValue(true));
        $requestMock->expects($this->exactly(3))
            ->method('getArgument')
            ->with($this->logicalOr($this->logicalOr('action', 'controller'), 'bookmark'))
            ->will($this->returnCallback([$this, 'getArgumentReturn']));

        $proxyMock = $this->getMockBuilder($this->proxyClass)
            ->setMethods(['restoreBookmarkByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $proxyMock->expects($this->once())
            ->method('restoreBookmarkByUid')
            ->with($this->bookmarkUid);

        $proxyMock->processRequest($requestMock);
    }



    /**
     * @test
     */
    public function addContentToBookmark()
    {
        $bookmarkMock = $this->getMockBuilder('Bookmark')
            ->disableOriginalClone()
            ->getMock();

        $sessionPersistenceManagerMock = $this->getMockBuilder('PunktDe_PtExtbase_State_Session_SessionPersistenceManager')
            ->setMethods(['getSessionData'])
            ->disableOriginalConstructor()
            ->getMock();
        $sessionPersistenceManagerMock->expects($this->once())
            ->method('getSessionData')
            ->will($this->returnValue(['sessionData']));

        $bookmarkStrategyMock = $this->getMockBuilder('BookmarkStrategy')
            ->setMethods(['addContentToBookmark'])
            ->getMock();
        $bookmarkStrategyMock->expects($this->once())
            ->method('addContentToBookmark')
            ->with($bookmarkMock, $this->configurationBuilderMock, ['sessionData']);

        $this->proxy->_set('sessionPersistenceManager', $sessionPersistenceManagerMock);
        $this->proxy->_set('bookmarkStrategy', $bookmarkStrategyMock);
        $this->proxy->_set('configurationBuilder', $this->configurationBuilderMock);

        $this->proxy->addContentToBookmark($bookmarkMock);
    }



    /**
     * @test
     */
    public function injectSessionPersistenceManagerInjectsSessionPersistenceManager()
    {
        $sessionAdapterMock = new PunktDe_PtExtbase_Tests_Unit_State_Stubs_SessionAdapterMock();
        $sessionPersistenceManagerMock = $this->getMockBuilder('PunktDe_PtExtbase_State_Session_SessionPersistenceManager')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->proxy->_injectSessionPersistenceManager($sessionPersistenceManagerMock);
        $this->assertEquals($sessionPersistenceManagerMock, $this->proxy->_get('sessionPersistenceManager'));
    }



    /**
     * @test
     */
    public function injectBookmarkRepositoryInjectsBookmarkRepository()
    {
        $bookmarkRepositoryMock = $this->getMockBuilder('Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->proxy->injectBookmarkRepository($bookmarkRepositoryMock);
        $this->assertEquals($bookmarkRepositoryMock, $this->proxy->_get('bookmarkRepository'));
    }



    /**
     * @test
     */
    public function injectBookmarkStrategyInjectsBookmarkStrategy()
    {
        $bookmarkStrategyMock = $this->getMock('BookmarkStrategy');

        $this->proxy->injectBookmarkStrategy($bookmarkStrategyMock);
        $this->assertEquals($bookmarkStrategyMock, $this->proxy->_get('bookmarkStrategy'));
    }



    /**
     * @test
     */
    public function injectConfigurationBuilderInjectsConfigurationBuilder()
    {
        $this->proxy->_injectConfigurationBuilder($this->configurationBuilderMock);
        $this->assertEquals($this->configurationBuilderMock, $this->proxy->_get('configurationBuilder'));
    }



    //Callback-Functions for Mocks

    public function hasArgumentReturnFalseForController($argument)
    {
        if ($argument == 'controller') {
            return false;
        }
        return true;
    }



    public function hasArgumentReturnFalseForBookmark($argument)
    {
        if ($argument == 'bookmark') {
            return false;
        }
        return true;
    }



    public function getArgumentReturnWrongArgumentForController($argument)
    {
        if ($argument == 'controller') {
            return 'notBookmark';
        } elseif ($argument == 'action') {
            return 'restore';
        }
        return null;
    }



    public function getArgumentReturn($argument)
    {
        if ($argument == 'controller') {
            return 'Bookmark';
        } elseif ($argument == 'action') {
            return 'restore';
        } elseif ($argument == 'bookmark') {
            return $this->bookmarkUid;
        }
        return null;
    }
}
