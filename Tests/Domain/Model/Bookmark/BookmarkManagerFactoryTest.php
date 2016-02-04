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
 * @see Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManagerFactory
 */
class Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * Holds an array of settings
     *
     * @var array
     */
    protected $settings = array(

        'listIdentifier' => 'Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerFactoryTest',

        'prototype' => array(

            'backend' => array(
                'mysql' => array(
                    'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend',
                    'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
                    'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',


                )
            ),
        ),

        'listConfig' => array(
            'Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerFactoryTest' => array(

                'backendConfig' => array(
                    'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend',
                    'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
                    'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
                ),

                'bookmarks' => array(
                    'bookmarkPid' => 30,
                    'createPublicBookmarks' => 1,
                    'createPrivateBookmarks' => 1,
                    'createGroupBookmarks' => 1,
                    'showPublicBookmarks' => 1,
                    'showPrivateBookmarks' => 1,
                    'showGroupBookmarks' => 1,
                    'userCanDeleteAll' => 0,
                    'groupIdsToShowBookmarksFor' => '4,5,6'
                ),
            )
        )
    );


    /**
     * @var string
     */
    protected $proxyClass;



    /**
     * @var Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManagerFactory
     */
    protected $proxy;



    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $bookmarkManagerMock;




    public function setUp()
    {
        $this-> proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManagerFactory');
        $this->proxy = new $this->proxyClass;
        $this->initDefaultConfigurationBuilderMock();

        $this->bookmarkManagerMock = $this->getMockBuilder('Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManager')
            ->setMethods(array('_injectConfigurationBuilder', '_injectSessionPersistenceManager'))
            ->setConstructorArgs(array('Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerFactoryTest'))
            ->getMock();
    }


    /**
     * @test
     */
    public function classExists()
    {
        $this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManagerFactory'));
    }



    /**
     * @test
     */
    public function injectBookmarkRepository()
    {
        $bookmarkRepositoryMock = $this->getMock('Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository', $methods = array(), $arguments = array(), $mockClassName = '', $callOriginalConstructor = false);

        $this->proxy->injectBookmarkRepository($bookmarkRepositoryMock);
        $this->assertEquals($bookmarkRepositoryMock, $this->proxy->_get('bookmarkRepository'));
    }



    /**
     * @test
     */
    public function getInstanceByConfigurationBuilderReturnsExistingInstanceOfBookmarkManager()
    {
        $instances = array('Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkManagerFactoryTest' => $this->bookmarkManagerMock);
        $this->proxy->_set('instances', $instances);

        $actual = $this->proxy->getInstanceByConfigurationBuilder($this->configurationBuilderMock);
        $this->assertEquals($this->bookmarkManagerMock, $actual);
    }



    //TODO: fix and reactivate test
    public function getInstanceByConfigurationBuilderBuildsNotYetExistingBookmarkManager()
    {
        $bookmarkConfigMock = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig')
            ->setMethods(array('getBookmarkPid'))
            ->disableOriginalConstructor()
            ->getMock();
        $bookmarkConfigMock->expects($this->once())
            ->method('getBookmarkPid')
            ->will($this->returnValue(30));

        $configurationBuilderMock = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder')
            ->setMethods(array('buildBookmarkConfiguration', 'getListIdentifier'))
            ->disableOriginalConstructor()
            ->getMock();
        $configurationBuilderMock->expects($this->once())
            ->method('buildBookmarkConfiguration')
            ->will($this->returnValue($bookmarkConfigMock));
        $configurationBuilderMock->expects($this->atLeastOnce())
            ->method('getListIdentifier')
            ->will($this->returnValue('Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManagerFactory'));

        $bookmarkRepositoryMock = $this->getMockBuilder('Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository')
            ->setMethods(array('setBookmarkStoragePid'))
            ->getMock();

        $bookmarkRepositoryMock->expects($this->once())
            ->method('setBookmarkStoragePid')
            ->with(30);

        $objectManagerMock = $this->getMockBuilder('\TYPO3\CMS\Extbase\Object\ObjectManager')
            ->setMethods(array('get'))
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerMock->expects($this->once())
            ->method('get')
            ->with('Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManager', 'Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManagerFactory')
            ->will($this->returnValue($this->bookmarkManagerMock));

        $this->bookmarkManagerMock->expects($this->once())
            ->method('_injectConfigurationBuilder')
            ->with($configurationBuilderMock);

        $sessionPersistenceManagerMock = $this->getMockBuilder('Tx_PtExtbase_State_Session_SessionPersistenceManager')
            ->disableOriginalConstructor()
            ->getMock();

        $sessionPersistenceManagerBuilderMock = $this->getMockBuilder('Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder')
            ->setMethods(array('getInstance'))
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
