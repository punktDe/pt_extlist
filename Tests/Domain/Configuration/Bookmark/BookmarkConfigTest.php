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
 * Testcase for bookmarks configuration
 *
 * @package Tests
 * @subpackage Domain\Configuration\Bookmark
 * @author Michael Knoll
 * @see Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Bookmark_BookmarkConfigTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * Holds instance of bookmark config that we want to test
     *
     * @var Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig
     */
    protected $bookmarkConfigToBeTested;
    
    
    
    /**
     * Holds an array of settings
     *
     * @var array
     */
    protected $settings = array(

                'listIdentifier' => 'Tx_PtExtlist_Tests_Domain_Configuration_Bookmark_BookmarkConfigTest',

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
                     'Tx_PtExtlist_Tests_Domain_Configuration_Bookmark_BookmarkConfigTest' => array(
                        
                        'backendConfig' => array(
                                'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend',
                                'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
                                'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
                
                
                                'dataSource' => array(
                                    'testKey' => 'testValue',
                                    'username' => 'user',
                                    'password' => 'pass',
                                    'host' => 'localhost',
                                    'port' => 3306,
                                    'databaseName' => 'typo3',
                                ),
                                
                                'baseFromClause' => 'companies',
                                'baseGroupByClause' => 'company',
                                'baseWhereClause' => 'employees > 0'
                        ),
                            
                                            
                        // this is really ugly but required to make controller work
                        'bookmarks' => array(
                            'bookmarkPid' => 30,
                            'createPublicBookmarks' => 1,
                            'createPrivateBookmarks' => 1,
                            'createGroupBookmarks' => 1,
                            'showPublicBookmarks' => 1,
                            'showPrivateBookmarks' => 1,
                            'showGroupBookmarks' => null,
                            'userCanDeleteAll' => 0,
                            'groupIdsToShowBookmarksFor' => '4,5,6'
                        ),
                    )
                )
            );
            
            
            
    public function setUp()
    {
        $this->initDefaultConfigurationBuilderMock($this->settings);
        $this->bookmarkConfigToBeTested = new Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig($this->configurationBuilderMock, $this->configurationBuilderMock->getSettingsForConfigObject('bookmarks'));
    }


    /**
     * @test
     */
    public function classExists()
    {
        $this->assertTrue(class_exists('Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig'));
    }



    /**
     * @test
     */
    public function getBookmarkPidGetsBookmarkPid()
    {
        $this->assertEquals('30', $this->bookmarkConfigToBeTested->getBookmarkPid());
    }



    /**
     * @test
     */
    public function getGroupIdsToShowBookmarksFor()
    {
        $this->assertEquals('4,5,6', $this->bookmarkConfigToBeTested->getGroupIdsToShowBookmarksFor());
    }



    /**
     * @test
     */
    public function getShowGroupBookmarks()
    {
        $this->assertEquals(null, $this->bookmarkConfigToBeTested->getShowGroupBookmarks());
    }



    /**
     * @test
     */
    public function getShowPublicBookmarks()
    {
        $this->assertEquals(true, $this->bookmarkConfigToBeTested->getShowPublicBookmarks());
    }



    /**
     * @test
     */
    public function getShowPrivateBookmarks()
    {
        $this->assertEquals(true, $this->bookmarkConfigToBeTested->getShowPrivateBookmarks());
    }



    /**
     * @test
     */
    public function getCreateGroupBookmarks()
    {
        $this->assertEquals(true, $this->bookmarkConfigToBeTested->getCreateGroupBookmarks());
    }



    /**
     * @test
     */
    public function getCreatePublicBookmarks()
    {
        $this->assertEquals(true, $this->bookmarkConfigToBeTested->getCreatePublicBookmarks());
    }



    /**
     * @test
     */
    public function getCreatePrivateBookmarks()
    {
        $this->assertEquals(true, $this->bookmarkConfigToBeTested->getCreatePrivateBookmarks());
    }



    /**
     * @test
     */
    public function getUserCanDeleteAll()
    {
        $this->assertEquals(false, $this->bookmarkConfigToBeTested->getUserCanDeleteAll());
    }
}
