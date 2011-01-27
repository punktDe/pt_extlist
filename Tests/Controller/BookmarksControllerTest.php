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
 * Testcase for bookmarks controller
 *
 * @package Tests
 * @subpackage Controller
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Controller_BookmarksController_testcase  extends Tx_PtExtlist_Tests_BaseTestcase {
/*
	protected $settings = array(
	            
                        // this is really ugly but required to make controller work
                        'bookmarks' => array(
                            'showPublicBookmarks' => '1',
                            'showUserBookmarks' => '1',
                            'showGroupBookmarks' => '1'
                        ),
                'listIdentifier' => 'Tx_PtExtlist_Tests_Controller_BookmarksController_testcase',
                'abc' => '1',
                'prototype' => array(

                'backend' => array (
                    'mysql' => array (
                        'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend',
                        'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
                        'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
                        
                        
                    )
                    ),
                'column' => array (
                        'xy' => 'z',
                    ),
                ),
                'listConfig' => array(
                     'Tx_PtExtlist_Tests_Controller_BookmarksController_testcase' => array(
                        
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
                        
                         'abc' => '2',
                         'def' => '3',
                            
                         'fields' => array(
                             'field1' => array( 
                                 'table' => 'tableName1',
                                 'field' => 'fieldName1',
                                 'isSortable' => '0',
                                 'access' => '1,2,3,4'
                             ),
                             'field2' => array( 
                                 'table' => 'tableName2',
                                 'field' => 'fieldName2',
                                 'isSortable' => '1',
                                 'access' => '1,2,3,4'
                             ),
                             'field3' => array( 
                                 'special' => 'special',
                                 'isSortable' => '1',
                                 'access' => '1,2,3,4'
                             ),
                             'field4' => array( 
                                 'table' => 'tableName4',
                                 'field' => 'fieldName4',
                             )
                         ),
                        'columns' => array(
                            10 => array( 
                                'columnIdentifier' => 'column1',
                                'fieldIdentifier' => 'field1',
                                'label' => 'Column 1',
                                'isSortable' => '0',
                                
                            ),
                            20 => array( 
                                'columnIdentifier' => 'column2',
                                'fieldIdentifier' => 'field2',
                                'label' => 'Column 2',  
                                'isSortable' => '1',
                                'sorting' => 'tstamp, title',
                                
                            ),
                            30 => array( 
                                'columnIdentifier' => 'column3',
                                'fieldIdentifier' => 'field3',
                                'label' => 'Column 3',  
                                'isSortable' => '1',
                                'sorting' => 'tstamp asc, title !DeSc',
                                'accessGroups' => '1,2,3,4'
                            ),
                            40 => array( 
                                'columnIdentifier' => 'column4',
                                'fieldIdentifier' => 'field4',
                                'label' => 'Column 4',  
                                //'renderTemplate' => 'typo3conf/ext/pt_extlist/Configuration/TypoScript/Demolist/Demolist_Typo3_02.hierarchicStructure.html',
                            )
                        ),
                        'renderer' => array(
                            'rendererClassName' => 'Tx_PtExtlist_Domain_Renderer_DefaultRenderer',
                            'enabled' => 1,
                            'showCaptionsInBody' => 0,
                            'specialCell' => 'EXT:pt_extlist/Resources/Private/UserFunctions/class.tx_ptextlist_demolist_specialcell.php:tx_ptextlist_demolist_specialcell->processCell'
                        ),
                        'filters' => array(
                             'testfilterbox' => array(
                                'filterConfigs' => array( 
                                    '10' => array(
                                        'filterIdentifier' => 'filter1',
                                        'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                        'fieldIdentifier' => 'field1',
                                        'partialPath' => 'Filter/StringFilter',
                                        'defaultValue' => 'default',
                                     ),
                                     '20' => array(
                                        'filterIdentifier' => 'filter2',
                                        'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                        'fieldIdentifier' => 'field1',
                                        'partialPath' => 'Filter/StringFilter',
                                        'accessGroups' => '1,2,3'
                                     )
                                 )
                             )
                        ),
                        'pager' => array(
                            'itemsPerPage'   => '10',
                            'pagerConfigs' => array(
                                'default' => array(
                                    'templatePath' => 'EXT:pt_extlist/',
                                    'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager',
                                    'enabled' => '1'
                                ),
                            ),
                        ),

                        
                        'aggregateData' => array(
                            'sumField1' => array (
                                'fieldIdentifier' => 'field1',
                                'method' => 'sum',
                            ),
                            'avgField2' => array (
                                'fieldIdentifier' => 'field2',
                                'method' => 'avg',
                            ),
                        ),
                        
                        
                        'aggregateRows' => array (
                            10 => array (
                                'column2' => array (
                                    'aggregateDataIdentifier' => 'avgField2',
                                )
                            )
                        )
                    )
                )
            );
	
            
            
    public function setup() {
    	parent::setup();
    	$this->initDefaultConfigurationBuilderMock();
    }
            
	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Controller_BookmarksController'));
	}
	
	
	
	public function testShowAction() {
		$userBookmarksCollectionMock = array('test' => 'value');
		$publicBookmarksCollectionMock = array('test2' => 'value2');
		$groupBookmarksMock = array('test3' => 'value3');
		
		$bookmarksConfigurationMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig', array(), array(), '', FALSE);
		$bookmarksConfigurationMock->expects($this->once())->method('getShowPublicBookmarks')->will($this->returnValue(true));
		$bookmarksConfigurationMock->expects($this->once())->method('getShowUserBookmarks')->will($this->returnValue(true));
		$bookmarksConfigurationMock->expects($this->once())->method('getShowGroupBookmarks')->will($this->returnValue(true));
		
		$bookmarksRepositoryMock = $this->getMock('Tx_PtExtlist_Domain_Repository_BookmarksRepository', array('findPublicBookmarksByListIdentifier', 'findBookmarksByFeUserAndListIdentifier', 'findBookmarksByFeUserGroupIdsAndListIdentifier'), array(), '', FALSE);
		$bookmarksRepositoryMock->expects($this->once())->method('findBookmarksByFeUserAndListIdentifier')->will($this->returnValue($userBookmarksCollectionMock));
		$bookmarksRepositoryMock->expects($this->once())->method('findPublicBookmarksByListIdentifier')->will($this->returnValue($publicBookmarksCollectionMock));
		$bookmarksRepositoryMock->expects($this->once())->method('findBookmarksByFeUserGroupIdsAndListIdentifier')->will($this->returnValue($groupBookmarksMock));
		
		$feUserMock = $this->getMock('Tx_Extbase_Domain_Model_FrontendUser', array('getUsergroups'), array(), '', FALSE);
		$feUserMock->expects($this->once())->method('getUsergroups')->will($this->returnValue(array(1,2,3,4)));
		
		$mockView = $this->getMock(
            'Tx_Fluid_Core_View_TemplateView',
            array('assign'), array(), '', FALSE);
        $mockView->expects($this->at(0))->method('assign')->with('publicBookmarks', $publicBookmarksCollectionMock);
        $mockView->expects($this->at(1))->method('assign')->with('userBookmarks', $userBookmarksCollectionMock);
        $mockView->expects($this->at(2))->method('assign');
        
        $mockController = $this->getMock(
            $this->buildAccessibleProxy('Tx_PtExtlist_Controller_BookmarksController'),
            array('dummy'),array(), '', FALSE);
        $mockController->_set('view', $mockView);
        $mockController->_set('configurationBuilder', $this->configurationBuilderMock);
        $mockController->_set('bookmarksRepository', $bookmarksRepositoryMock);
        $mockController->_set('feUser', $feUserMock);
        $mockController->_set('settings', $this->settings);
        $mockController->_set('bookmarkConfiguration', $bookmarksConfigurationMock);
        
        $mockController->showAction();
	}
	
	
	
	public function testNewAction() {
		$bookmarkMockNonCloned = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark');
        $bookmarkMock = clone $bookmarkMockNonCloned;
		
        $mockView = $this->getMock(
            'Tx_Fluid_Core_View_TemplateView',
            array('assign'), array(), '', FALSE);
        $mockView->expects($this->at(0))->method('assign')->with('allowedToStorePublicBookmark', false);
        $mockView->expects($this->at(1))->method('assign')->with('bookmark', $bookmarkMock);
        
        $mockController = $this->getMock(
            $this->buildAccessibleProxy('Tx_PtExtlist_Controller_BookmarksController'),
            array('dummy'),array(), '', FALSE);
        $mockController->_set('view', $mockView);
        $mockController->newAction($bookmarkMock);
	}
	
	
	
	public function testCreateAction() {
		$nonClonedBookmark = new Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark();
		$bookmark = clone $nonClonedBookmark;
		
        $bookmarkRepositoryMock = $this->getMock('Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository', array('add'), array(),'', FALSE);
        $bookmarkRepositoryMock->expects($this->once())->method('add')->with($bookmark);
		
        $bookmarkManagerMock = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager');
        
        $persistenceManagerMock = $this->getMock('Tx_Extbase_Persistence_Manager', array('persistAll'), array(), '', FALSE);
        
        $mockRequest = $this->getMock('Tx_Extbase_MVC_Request', array(), array(), '', FALSE);
        // TODO here are some things missing to be tested
        $mockRequest->expects($this->at(0))->method('hasArgument')->will($this->returnValue(0));
        
		$mockController = $this->getMock(
            $this->buildAccessibleProxy('Tx_PtExtlist_Controller_BookmarksController'),
            array('forward'),array(), '', FALSE);
        $mockController->expects($this->once())->method('forward')->with('show')->will($this->returnValue(true));
        $mockController->_set('request', $mockRequest);
        $mockController->_set('listIdentifier', 'Tx_PtExtlist_Tests_Controller_BookmarksController_testcase');
        $mockController->_set('settings', $this->settings);
        $mockController->_set('configurationBuilder', $this->configurationBuilderMock);
        $mockController->_set('bookmarksRepository', $bookmarkRepositoryMock);
        $mockController->_set('bookmarkManager', $bookmarkManagerMock);
        $mockController->_set('persistenceManager', $persistenceManagerMock);
        
        $mockController->createAction($bookmark);
	}
	
	
	
	public function testProcessAction() {
		$bookmarkMockNonCloned = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark');
		$bookmarkMock = clone $bookmarkMockNonCloned;
        
        $bookmarkManagerMock = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager');
		
		$mockRequest = $this->getMock('Tx_Extbase_MVC_Request', array('dummy'), array(), '', FALSE);
		
		$mockView = $this->getMock(
            'Tx_Fluid_Core_View_TemplateView',
            array('assign'), array(), '', FALSE);
        $mockView->expects($this->once())->method('assign')->with('processedBookmark', $bookmarkMock);
        
		$mockController = $this->getMock(
            $this->buildAccessibleProxy('Tx_PtExtlist_Controller_BookmarksController'),
            array('forward'),array(), '', FALSE);
        $mockController->expects($this->once())->method('forward')->with('show')->will($this->returnValue(true));
        $mockController->_set('view', $mockView);
        $mockController->_set('request', $mockRequest);
        $mockController->_set('configurationBuilder', $this->configurationBuilderMock);
        $mockController->_set('listIdentifier', 'Tx_PtExtlist_Tests_Controller_BookmarksController_testcase');
        $mockController->_set('settings', $this->settings);
        $mockController->_set('bookmarkManager', $bookmarkManagerMock);
        
        $mockController->processAction($bookmarkMock);
	}
	
	
	
	public function testDeleteActionNonConfirmed() {
		$bookmarkMockNonCloned = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark');
        $bookmarkMock = clone $bookmarkMockNonCloned;
        
        $mockRequest = $this->getMock('Tx_Extbase_MVC_Request', array('hasArgument'), array(), '', FALSE);
		$mockRequest->expects($this->once())->method('hasArgument')->with('reallyDelete')->will($this->returnValue(false));
		
		$mockView = $this->getMock(
            'Tx_Fluid_Core_View_TemplateView',
            array('assign'), array(), '', FALSE);
        $mockView->expects($this->once())->method('assign')->with('bookmark', $bookmarkMock);
        
        $mockController = $this->getMock(
            $this->buildAccessibleProxy('Tx_PtExtlist_Controller_BookmarksController'),
            array('dummy'),array(), '', FALSE);
        $mockController->_set('view', $mockView);
        $mockController->_set('request', $mockRequest);
        $mockController->_set('configurationBuilder', $this->configurationBuilderMock);
        $mockController->_set('listIdentifier', 'Tx_PtExtlist_Tests_Controller_BookmarksController_testcase');
        $mockController->_set('settings', $this->settings);
		
        $mockController->deleteAction($bookmarkMock);
	}
	
	
	
	public function testDeleteActionConfirmed() {
		$bookmarkMockNonCloned = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark');
        $bookmarkMock = clone $bookmarkMockNonCloned;
        
        $mockRequest = $this->getMock('Tx_Extbase_MVC_Request', array('hasArgument'), array(), '', FALSE);
        $mockRequest->expects($this->once())->method('hasArgument')->with('reallyDelete')->will($this->returnValue(true));

        $bookmarkRepositoryMock = $this->getMock('Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository', array('remove'), array(),'', FALSE);
        $bookmarkRepositoryMock->expects($this->once())->method('remove')->with($bookmarkMock);
        
        $persistenceManagerMock = $this->getMock('Tx_Extbase_Persistence_Manager', array('persistAll'), array(), '', FALSE);
        
        $mockController = $this->getMock(
            $this->buildAccessibleProxy('Tx_PtExtlist_Controller_BookmarksController'),
            array('forward'),array(), '', FALSE);
        $mockController->expects($this->once())->method('forward')->with('show')->will($this->returnValue(true));
        $mockController->_set('view', $mockView);
        $mockController->_set('request', $mockRequest);
        $mockController->_set('listIdentifier', 'Tx_PtExtlist_Tests_Controller_BookmarksController_testcase');
        $mockController->_set('settings', $this->settings);
        $mockController->_set('configurationBuilder', $this->configurationBuilderMock);
        $mockController->_set('bookmarksRepository', $bookmarkRepositoryMock);
        $mockController->_set('persistenceManager', $persistenceManagerMock);
        
        $mockController->deleteAction($bookmarkMock);
	}
*/
	
	
	public function testUpdateAction() {
		$this->markTestIncomplete();
	}
	
	
	
	public function testEditAction() {
		$this->markTestIncomplete();
	}
	
}

?>