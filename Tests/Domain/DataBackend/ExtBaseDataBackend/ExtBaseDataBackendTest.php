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
 * Testcase for ExtBase Data Backend
 *
 * @package Tests
 * @subpackage Domain\DataBackend
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseBackendTest extends Tx_PtExtlist_Tests_BaseTestcase {
	
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
						'dataSourceClass' => 'Tx_Extbase_Persistence_Repository',
                        
                    )
                ),
                'column' => array (
                        'xy' => 'z',
                    ),
                ),
                'listConfig' => array(
                     'test' => array(

						 'backendConfig' => array(
							 'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend',
							 'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
							 'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
							 'dataSourceClass' => 'Tx_Extbase_Persistence_Repository',
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
        parent::setup();
		$this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($this->settings); 
	}
	
	
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend'));
    }
    
    
    
    public function testInjectDataSource() {
    	$dataSourceMock = $this->getMock('Tx_Extbase_Domain_Repository_FrontendUserGroupRepository', array(), array(), '', FALSE);
    	$extBaseDataBackend = new Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend($this->configurationBuilderMock);
    	$extBaseDataBackend->_injectDataSource($dataSourceMock);
    }
    
    
    
    public function testThrowExceptionOnInjectingWrongDataSource() {
    	$dataSourceMock = $this->getMock('Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseBackendTest', array(), array(), '', FALSE);
    	$extBaseDataBackend = new Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend($this->configurationBuilderMock);
    	try {
    		$extBaseDataBackend->_injectDataSource($dataSourceMock);
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No exception has been thrown when trying to inject wrong type of data source object');
    }
    
    
    
    public function testGetListData() {
    	$extBaseDataBackend = $this->getPreparedExtbaseDataBackend();

        $sorterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_Sorter', array('getSortingStateCollection'), array(), '', FALSE);
        $sorterMock->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue($sortingStateCollectionMock));
        $extBaseDataBackend->_injectSorter($sorterMock);

        $mapperMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Mapper_DomainObjectMapper', array('getMappedListData'), array(), '', FALSE);
        $mapperMock->expects($this->any())
            ->method('getMappedListData')
            ->will($this->returnValue(new Tx_PtExtlist_Domain_Model_List_List()));
        $extBaseDataBackend->_injectDataMapper($mapperMock);
        
        $listData = $extBaseDataBackend->getListData();
    }
    
    
    
    public function testGetTotalItemsCount() {
    	$extBaseDataBackend = $this->getPreparedExtbaseDataBackend();
    	
    	// create fake query settings
    	$querySettingsMock = $this->getMock('Tx_Extbase_Persistence_Typo3QuerySettings', array('setRespectStoragePage'), array(), '', FALSE);
    	$querySettingsMock->expects($this->any())->method('setRespectStoragePage');
    	
    	// overwrite datasource to return fake query object
    	$queryObjectMock = $this->getMock('Tx_Extbase_Persistence_Query', array('count'), array(), '', FALSE);
    	$queryObjectMock->expects($this->any())->method('count')->will($this->returnValue(10));
    	$queryObjectMock->expects($this->any())->method('getQuerySettings')->will($this->returnValue($querySettingsMock));
    	$queryObjectMock->setQuerySettings($querySettingsMock); // Set this here, as getQuerySettings() will check on query settings to be existing!
    	
    	$repositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository', array(), array('createQuery'), '', FALSE);
    	$repositoryMock->expects($this->any())->method('createQuery')->will($this->returnValue($queryObjectMock));
    	
    	$extBaseDataBackend->_injectDataSource($repositoryMock);
    	
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
        
        $extBaseDataBackend->_injectDataSource($repositoryMock);
        
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
        $dataSource = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend::createDataSource($this->configurationBuilderMock);
        $extBaseDataBackend = new Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend($this->configurationBuilderMock);
        $extBaseDataBackend->_injectDataSource($dataSource);
        $extBaseDataBackend->_injectBackendConfiguration($this->configurationBuilderMock->buildDataBackendConfiguration());
        
        $pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('isEnabled', 'getCurrentPage', 'getItemsPerPage'), array(),'',FALSE);
        $pagerCollectionMock->expects($this->any())->method('isEnabled')->will($this->returnValue(true));
        $pagerCollectionMock->expects($this->any())->method('getCurrentPage')->will($this->returnValue(1));
        $pagerCollectionMock->expects($this->any())->method('getItemsPerPage')->will($this->returnValue(1));
        
        $extBaseDataBackend->_injectPagerCollection($pagerCollectionMock);
        
        return $extBaseDataBackend;
    }

}
?>