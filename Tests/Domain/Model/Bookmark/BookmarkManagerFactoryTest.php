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
 * Testcase for bookmark manager factory
 *
 * @package Tests
 * @subpackage Domain\Model\Bookmark
 * @author Michael Knoll
 * @see BookmarkManagerFactory
 */
class Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * Holds an array of settings
     *
     * @var array
     */
    protected $settings = [

        'listIdentifier' => 'Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerFactoryTest',

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
            'Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerFactoryTest' => [

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


    /**
     * @var string
     */
    protected $proxyClass;



    /**
     * @var BookmarkManagerFactory
     */
    protected $proxy;



    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $bookmarkManagerMock;




    public function setUp()
    {
        $this-> proxyClass = $this->buildAccessibleProxy('BookmarkManagerFactory');
        $this->proxy = new $this->proxyClass;
        $this->initDefaultConfigurationBuilderMock();

        $this->bookmarkManagerMock = $this->getMockBuilder('BookmarkManager')
            ->setMethods(['_injectConfigurationBuilder', '_injectSessionPersistenceManager'])
            ->setConstructorArgs(['Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerFactoryTest'])
            ->getMock();
    }


    /**
     * @test
     */
    public function classExists()
    {
        $this->assertTrue(class_exists('BookmarkManagerFactory'));
    }



    /**
     * @test
     */
    public function injectBookmarkRepository()
    {
        $bookmarkRepositoryMock = $this->getMock('Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository', $methods = [], $arguments = [], $mockClassName = '', $callOriginalConstructor = false);

        $this->proxy->injectBookmarkRepository($bookmarkRepositoryMock);
        $this->assertEquals($bookmarkRepositoryMock, $this->proxy->_get('bookmarkRepository'));
    }



    /**
     * @test
     */
    public function getInstanceByConfigurationBuilderReturnsExistingInstanceOfBookmarkManager()
    {
        $instances = ['Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerFactoryTest' => $this->bookmarkManagerMock];
        $this->proxy->_set('instances', $instances);

        $actual = $this->proxy->getInstanceByConfigurationBuilder($this->configurationBuilderMock);
        $this->assertEquals($this->bookmarkManagerMock, $actual);
    }



    //TODO: fix and reactivate test
    public function getInstanceByConfigurationBuilderBuildsNotYetExistingBookmarkManager()
    {
        $bookmarkConfigMock = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig')
            ->setMethods(['getBookmarkPid'])
            ->disableOriginalConstructor()
            ->getMock();
        $bookmarkConfigMock->expects($this->once())
            ->method('getBookmarkPid')
            ->will($this->returnValue(30));

        $configurationBuilderMock = $this->getMockBuilder('ConfigurationBuilder')
            ->setMethods(['buildBookmarkConfiguration', 'getListIdentifier'])
            ->disableOriginalConstructor()
            ->getMock();
        $configurationBuilderMock->expects($this->once())
            ->method('buildBookmarkConfiguration')
            ->will($this->returnValue($bookmarkConfigMock));
        $configurationBuilderMock->expects($this->atLeastOnce())
            ->method('getListIdentifier')
            ->will($this->returnValue('BookmarkManagerFactory'));

        $bookmarkRepositoryMock = $this->getMockBuilder('Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository')
            ->setMethods(['setBookmarkStoragePid'])
            ->getMock();

        $bookmarkRepositoryMock->expects($this->once())
            ->method('setBookmarkStoragePid')
            ->with(30);

        $objectManagerMock = $this->getMockBuilder('\TYPO3\CMS\Extbase\Object\ObjectManager')
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerMock->expects($this->once())
            ->method('get')
            ->with('BookmarkManager', 'BookmarkManagerFactory')
            ->will($this->returnValue($this->bookmarkManagerMock));

        $this->bookmarkManagerMock->expects($this->once())
            ->method('_injectConfigurationBuilder')
            ->with($configurationBuilderMock);

        $sessionPersistenceManagerMock = $this->getMockBuilder('PunktDe_PtExtbase_State_Session_SessionPersistenceManager')
            ->disableOriginalConstructor()
            ->getMock();

        $sessionPersistenceManagerBuilderMock = $this->getMockBuilder('PunktDe_PtExtbase_State_Session_SessionPersistenceManagerBuilder')
            ->setMethods(['getInstance'])
            ->disableOriginalConstructor()
            ->getMock();

        $sessionPersistenceManagerBuilderMock->expects($this->once())
            ->method('getInstance')
            ->will($this->returnValue($sessionPersistenceManagerMock));

        $this->bookmarkManagerMock->expects($this->once())
            ->method('_injectSessionPersistenceManager')
            ->with($sessionPersistenceManagerMock);

        $this->proxy->_set('bookmarkRepository', $bookmarkRepositoryMock);
        $this->proxy->_set('objectManager', $objectManagerMock);
        $this->proxy->_set('sessionPersistenceManagerBuilder', $sessionPersistenceManagerBuilderMock);
        $actual = $this->proxy->getInstanceByConfigurationBuilder($configurationBuilderMock);
        $this->assertEquals($this->bookmarkManagerMock, $actual);
    }
}
