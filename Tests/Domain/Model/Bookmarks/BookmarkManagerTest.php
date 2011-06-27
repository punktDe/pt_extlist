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
 * @subpackage Domain\Model\Bookmarks
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_Model_Bookmarks_BookmarkManager_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

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
        $this->initDefaultConfigurationBuilderMock();
    }
	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager'));
	}
	
	
	
	public function testGetSetCurrentBookmark() {
		
		$this->markTestIncomplete();
		
		$sessionPersistenceManagerMock = $this->getMock('Tx_PtExtbase_State_Session_SessionPersistenceManager');
		
		$bookmarkMock = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark');
		$bookmarkManager = new Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager($this->configurationBuilderMock->getListIdentifier());
		$bookmarkManager->injectSessionPersistenceManager($sessionPersistenceManagerMock);
		$bookmarkManager->setCurrentBookmark($bookmarkMock);
		
		$this->assertTrue($bookmarkManager->getCurrentBookmark() === $bookmarkMock);
	}
	
	
	
	public function testAddContentToBookmark() {	
        $bookmark = new Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark();

        $sessionPersistenceManagerMock = $this->getMock('Tx_PtExtbase_State_Session_SessionPersistenceManager',array('getSessionDataByNamespace'), array(), '', FALSE);
        $returnArray = array('test');
		$sessionPersistenceManagerMock->expects($this->once())->method('getSessionDataByNamespace')->with('tx_ptextlist_pi1.Tx_PtExtlist_Tests_Domain_Configuration_Bookmarks_BookmarkConfig_testcase.filters')->will($this->returnValue($returnArray));
        
		$bookmarkManager = new Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager($this->configurationBuilderMock->getListIdentifier());
        $bookmarkManager->injectSessionPersistenceManager($sessionPersistenceManagerMock);

        $bookmarkManager->addContentToBookmark($bookmark);
        
        $this->assertEquals(serialize(array('filters' => $returnArray)), $bookmark->getContent());
	}
	
	
	
	public function testInjectSessionPersistenceManager() {
		$sessionPersistenceManagerMock = $this->getMock('Tx_PtExtbase_State_Session_SessionPersistenceManager');
		
		$bookmarkManager = new Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager($this->configurationBuilderMock->getListIdentifier());
		$bookmarkManager->injectSessionPersistenceManager($sessionPersistenceManagerMock);
	}
	
	
	
	public function testInjectBookmarkRepository() {
		$bookmarkRepositoryMock = $this->getMock('Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository', array(), array(), '', FALSE);
		
		$bookmarkManager = new Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager($this->configurationBuilderMock->getListIdentifier());
		$bookmarkManager->injectBookmarkRepository($bookmarkRepositoryMock);
	}
	
}

?>