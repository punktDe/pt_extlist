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
 * @subpackage Domain\Model\Bookmarks
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_Model_Bookmarks_BookmarkManagerFactory_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
    
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
                        ),
                            
                                            
                        // this is really ugly but required to make controller work
                        'bookmarks' => array(
                            'showPublicBookmarks' => '1',
                            'showUserBookmarks' => '1',
                            'showGroupBookmarks' => '1',
                            'bookmarksPid' => '1,2,3',
                            'feUsersAllowedToEdit' => '2,3,4',
                            'feGroupsAllowedToEdit' => '3,4,5',
                            'groupIdsToShowBookmarksFor' => '4,5,6'
                        ),
                    )
                )
            );
            
            
            
    public function setup() {
    	parent::setup();
    	$this->initDefaultConfigurationBuilderMock();
    }
            
            
            
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManagerFactory'));	
    }
    
    
    
    public function testGetInstanceByConfigurationBuilder() {
    	$bookmarkManager = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManagerFactory::getInstanceByConfigurationBuilder($this->configurationBuilderMock);
    	$this->assertTrue(is_a($bookmarkManager, 'Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager'));
    }
    
    
    
    public function testGetSingleton() {
    	$firstInstance = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManagerFactory::getInstanceByConfigurationBuilder($this->configurationBuilderMock);
    	$secondInstance = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManagerFactory::getInstanceByConfigurationBuilder($this->configurationBuilderMock);
    	$this->assertEquals($firstInstance, $secondInstance);
    	
    	$anotherConfigurationBuilder = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
    	$differentInstance = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManagerFactory::getInstanceByConfigurationBuilder($anotherConfigurationBuilder);
    	$this->assertNotEquals($firstInstance, $differentInstance); 
    }
	
}

?>