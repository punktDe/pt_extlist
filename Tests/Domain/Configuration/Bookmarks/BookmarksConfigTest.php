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
 * @subpackage Domain\Configuration\Bookmarks
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Bookmarks_BookmarkConfig_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * Holds instance of bookmark config that we want to test
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig
	 */
	protected $bookmarkConfigToBeTested;
	
	
	
	/**
	 * Holds an array of settings
	 *
	 * @var array
	 */
	protected $settings = array(

                'listIdentifier' => 'Tx_PtExtlist_Tests_Domain_Configuration_Bookmarks_BookmarkConfig_testcase',

	            'prototype' => array(

	                'backend' => array (
	                    'mysql' => array (
	                        'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend',
	                        'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
	                        'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
	                        
	                        
	                    )
	                ),
	            ),
                
                'listConfig' => array(
                     'Tx_PtExtlist_Tests_Domain_Configuration_Bookmarks_BookmarkConfig_testcase' => array(
                        
                        'backendConfig' => array (
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
                            'showPublicBookmarks' => '1',
                            'showUserBookmarks' => '1',
                            'showGroupBookmarks' => '1',
                            'bookmarksPid' => '1,2,3',
						    'feUsersAllowedToEdit' => '2,3,4',
						    'feGroupsAllowedToEdit' => '3,4,5',
						    'groupIdsToShowBookmarksFor' => '4,5,6',
                            'feUsersAllowedToEditPublic' => '5,6,7',
                            'feGroupsAllowedToEditPublic' => '6,7,8'
                        ),
                    )
                )
            );
            
            
            
    public function setup() {
    	$this->initDefaultConfigurationBuilderMock($this->settings);
    	$this->bookmarkConfigToBeTested = new Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig($this->configurationBuilderMock, $this->configurationBuilderMock->getSettingsForConfigObject('bookmarks'));
    }
	
            
            
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig'));
	}
	
	
	
	public function testConstruct() {
		$bookmarksConfiguration = new Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig($this->configurationBuilderMock, $this->configurationBuilderMock->getSettingsForConfigObject('bookmarks'));
		$this->assertTrue(is_a($bookmarksConfiguration, 'Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig'));
	}
	
	
	
    public function testGetBookmarksPid() {
        $this->assertEquals($this->bookmarkConfigToBeTested->getBookmarksPid(), '1,2,3');
    }
    
    
    
    public function testGetFeGroupsAllowedToEdit() {
        $this->assertEquals($this->bookmarkConfigToBeTested->getFeGroupsAllowedToEdit(), '3,4,5');
    }
    
    
    
    public function testGetFeUsersAllowedToEdit() {
        $this->assertEquals($this->bookmarkConfigToBeTested->getFeUsersAllowedToEdit(), '2,3,4');
    }
    
    
    
    public function testGetGroupIdsToShowBookmarksFor() {
        $this->assertEquals($this->bookmarkConfigToBeTested->getGroupIdsToShowBookmarksFor(), '4,5,6');
    }
    
    
    
    public function testGetFeUsersAllowdToEditPublic() {
    	$this->assertEquals($this->bookmarkConfigToBeTested->getFeUsersAllowedToEditPublic(), '5,6,7');
    }
    
    
    
    public function testGetFeGroupsAllowedToEditPublic() {
    	$this->assertEquals($this->bookmarkConfigToBeTested->getFeGroupsAllowedToEditPublic(), '6,7,8');
    }
    
    
    
    public function testGetListIdentifier() {
        $this->assertEquals($this->bookmarkConfigToBeTested->getListIdentifier(), 'Tx_PtExtlist_Tests_Domain_Configuration_Bookmarks_BookmarkConfig_testcase');
    }
    
    
    
    public function testGetSettingsArray() {
    	$settingsArray = $this->settings['listConfig']['Tx_PtExtlist_Tests_Domain_Configuration_Bookmarks_BookmarkConfig_testcase']['bookmarks'];
        $this->assertEquals($this->bookmarkConfigToBeTested->getSettings(), $settingsArray);
    }
    
    

    public function testGetShowGroupBookmarks() {
        $this->assertEquals($this->bookmarkConfigToBeTested->getShowGroupBookmarks(), true);
    }
    
    
    
    public function getShowPublicBookmarks() {
        $this->assertEquals($this->bookmarkConfigToBeTested->getShowPublicBookmarks(), true);
    }
    
    
    
    public function getShowUserBookmarks() {
        $this->assertEquals($this->bookmarkConfigToBeTested->getShowUserBookmarks(), true);
    }
	
}

?>