<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * Testcase for ExtBase Data Backend
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseBackendTest extends Tx_PtExtlist_Tests_BaseTestcase {
	
	protected $extBaseSettings = array();
	
	
	
	protected $extBaseSettingsString = '
	
	plugin.tx_ptextlist.settings.persistence.storagePid = 12
	
	extensionName = PtExtlist
    pluginName = pi1
    controller = List
    action = list
    switchableControllerActions {
       10 {
           controller = List
           action = list
       }
    
    }
	
	# Required for requestBuilder
	
    persistence{
        enableAutomaticCacheClearing = 1
        updateReferenceIndex = 0
        classes {
            Tx_Extbase_Domain_Model_FrontendUser {
                mapping {
                    tableName = fe_users
                    recordType = Tx_Extbase_Domain_Model_FrontendUser
                    columns {
                        lockToDomain.mapOnProperty = lockToDomain
                    }
                }
            }
            Tx_Extbase_Domain_Model_FrontendUserGroup {
                mapping {
                    tableName = fe_groups
                    recordType =
                    columns {
                        lockToDomain.mapOnProperty = lockToDomain
                    }
                }
            }
        }
}';
	
	
	
	protected $settings = array('listIdentifier' => 'test',
                'abc' => '1',
                'prototype' => array(
                'pager' => array(
                        'pagerClassName' => 'Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock',
                    ),
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
                     'test' => array(
                        
                        'backendConfig' => array (
                                'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend',
                                'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
                                'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',

                                'repositoryClassName' => 'Tx_Extbase_Domain_Repository_FrontendUserGroupRepository'
                
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
                             )
                         ),
                        'columns' => array(
                            10 => array( 
                                'columnIdentifier' => 'column1',
                                'fieldIdentifier' => 'field1',
                                'label' => 'Column 1',
                                'isSortable' => '0',
                                'access' => '1,2,3,4'
                            ),
                            20 => array( 
                                'columnIdentifier' => 'column2',
                                'fieldIdentifier' => 'field2',
                                'label' => 'Column 2',  
                                'isSortable' => '1',
                                'sorting' => 'tstamp, title',
                                'access' => '1,2,3,4'
                            ),
                            30 => array( 
                                'columnIdentifier' => 'column3',
                                'fieldIdentifier' => 'field3',
                                'label' => 'Column 3',  
                                'isSortable' => '1',
                                'sorting' => 'tstamp asc, title !DeSc',
                                'access' => '1,2,3,4'
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
                                 '10' => array(
                                    'filterIdentifier' => 'filter1',
                                    'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                    'fieldDescriptionIdentifier' => 'field1',
                                    'partialPath' => 'Filter/StringFilter',
                                    'defaultValue' => 'default',
                                 ),
                                 '20' => array(
                                    'filterIdentifier' => 'filter2',
                                    'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                    'fieldDescriptionIdentifier' => 'field1',
                                    'partialPath' => 'Filter/StringFilter'
                                 )
                             )
                        ),
                        'pager' => array(
                            'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager',
                            'itemsPerPage'   => '10', 
                            'enabled' => '1'
                        ),
                    )
                )
            );
	
	
	public function setup() {
		$this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($this->settings); 
		  
		$typoScriptParser = t3lib_div::makeInstance('t3lib_TSparser');
        $typoScriptParser->parse($this->extBaseSettingsString);
        $this->extBaseSettings = Tx_Extbase_Utility_TypoScript::convertTypoScriptArrayToPlainArray($typoScriptParser->setup);
	}
	
	
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend'));
    }
    
    
    
    public function testInjectDataSource() {
    	$dataSourceMock = $this->getMock('Tx_Extbase_Domain_Repository_FrontendUserGroupRepository', array(), array(), '', FALSE);
    	$extBaseDataBackend = new Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend($this->configurationBuilderMock);
    	$extBaseDataBackend->injectDataSource($dataSourceMock);
    }
    
    
    
    public function testThrowExceptionOnInjectingWrongDataSource() {
    	$dataSourceMock = $this->getMock('Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseBackendTest', array(), array(), '', FALSE);
    	$extBaseDataBackend = new Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend($this->configurationBuilderMock);
    	try {
    		$extBaseDataBackend->injectDataSource($dataSourceMock);
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No exception has been thrown when trying to inject wrong type of data source object');
    }
    
    
    
    public function testCreateDataSource() {
    	$dispatcher = new Tx_Extbase_Dispatcher();
    	$dataSource = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend::createDataSource($this->configurationBuilderMock);
    	$this->assertTrue(is_a($dataSource, 'Tx_Extbase_Persistence_Repository'));
    }
    
    
    
    public function testGetListData() {
    	$extBaseDataBackend = $this->getPreparedExtbaseDataBackend();
        
        #print_r(Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration());
        $mapperMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Mapper_DomainObjectMapper', array('getMappedListData'), array(), '', FALSE);
        $mapperMock->expects($this->any())
            ->method('getMappedListData')
            ->will($this->returnValue(new Tx_PtExtlist_Domain_Model_List_List()));
        $extBaseDataBackend->injectDataMapper($mapperMock);
        
        $listData = $extBaseDataBackend->getListData();
    }
    
    
    
    public function testGetTotalItemsCount() {
    	$extBaseDataBackend = $this->getPreparedExtbaseDataBackend();
    	
    	// overwrite datasource to return fake query object
    	$queryObjectMock = $this->getMock('Tx_Extbase_Persistence_Query', array('count'), array(), '', FALSE);
    	$queryObjectMock->expects($this->any())->method('count')->will($this->returnValue(10));
    	
    	$repositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository', array(), array('createQuery'), '', FALSE);
    	$repositoryMock->expects($this->any())->method('createQuery')->will($this->returnValue($queryObjectMock));
    	
    	$extBaseDataBackend->injectDataSource($repositoryMock);
    	
    	$this->assertEquals($extBaseDataBackend->getTotalItemsCount(), 10);
    }
    
    
    
    public function testGetGroupData() {
    	
    	$this->markTestSkipped();
    	
    	$extBaseDataBackend = $this->getPreparedExtbaseDataBackend();
        
        // overwrite datasource to return fake query object
        $returnArray = array('field' => 'value');
       
        $queryObjectMock = $this->getMock('Tx_PtExtlist_Domain_QueryObject_Query', array('getCriterias'), array(), '', FALSE);
        $queryObjectMock->expects($this->any())->method('getCriterias')->will($this->returnValue(array()));
        
        $extbaseQueryObjectMock = $this->getMock('Tx_Extbase_Persistence_Query', array('execute','getObjectDataByQuery'), array(), '', FALSE);
        $extbaseQueryObjectMock->expects($this->any())->method('execute')->will($this->returnValue($returnArray));
        
        $repositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository', array(), array('createQuery'), '', FALSE);
        $repositoryMock->expects($this->any())->method('createQuery')->will($this->returnValue($extbaseQueryObjectMock));
        
        $extBaseDataBackend->injectDataSource($repositoryMock);
        
        $this->assertEquals($extBaseDataBackend->getGroupData($queryObjectMock), $returnArray);
    }
    
    
    
    /************************************************************************************************************
     * Helper methods  
     ************************************************************************************************************/
	
    /**
     * Returns a prepared extbase backend for testing
     * 
     * Data backend can be customized by injection different components
     *
     * @return Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend
     */
    protected function getPreparedExtbaseDataBackend() {
    	$dispatcher = new Tx_Extbase_Dispatcher();
        $dataSource = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend::createDataSource($this->configurationBuilderMock);
        $extBaseDataBackend = new Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend($this->configurationBuilderMock);
        $extBaseDataBackend->injectDataSource($dataSource);
        
        try {
            $dispatcher->dispatch('content', $this->extBaseSettings);
        } catch (Exception $e) {
            
        }
        
//        $pagerMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager', array(), array('isEnabled', 'getCurrentPage', 'getItemsPerPage'), '', FALSE);
        
        
        $pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('isEnabled', 'getCurrentPage', 'getItemsPerPage'), array(),'',FALSE);
        $pagerCollectionMock->expects($this->any())->method('isEnabled')->will($this->returnValue(true));
        $pagerCollectionMock->expects($this->any())->method('getCurrentPage')->will($this->returnValue(1));
        $pagerCollectionMock->expects($this->any())->method('getItemsPerPage')->will($this->returnValue(1));
        
        $extBaseDataBackend->injectPagerCollection($pagerCollectionMock);
        
        return $extBaseDataBackend;
    }
}

?>