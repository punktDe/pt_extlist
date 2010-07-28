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
 * Testcase for pt_list mysql data backend object. 
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Test_Domain_DataBackend_MySqlDataBackend_testcase extends Tx_Extbase_BaseTestcase {
	
	/**
	 * Holds configuration string for demo TS setup
	 *
	 * @var string
	 */
	protected $tsConfigString =
"plugin.tx_ptextlist.settings {

    # This comes from flexform!
    listIdentifier = list1

    listConfig.list1 {
    
        # config für dosGenerator
        backendConfig {

            dataBackendClass = Tx_PtExtlist_Domain_DataBackend_DummyDataBackend
            dataSourceClass = Tx_PtExtlist_Domain_DataBackend_DataSource_DummyDataSource
            dataMapperClass = Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper
            
            datasource {
                host = localhost
                username = typo3
                password = typo3
                database = typo3
            }
            
            tables (
                static_countries, 
                static_territories st_continent, 
                static_territories st_subcontinent
            )
            
            baseFromClause (
                ...
            )
            
            baseWhereClause (
                ...
            ) 
            
        }
}";
	
	
	
	/**
	 * Holds array with demo ts config
	 *
	 * @var unknown_type
	 */
	protected $tsConfig;
	
		
	
	/**
	 * Holds an instance of TS parser
	 *
	 * @var t3lib_TSparser
	 */
	protected $typoScriptParser;
	
	
	
	/**
	 * Holds an instance of extlist configuration builder
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;
	
	
	
	/**
	 * Setup test by loading some demo ts settings
	 */
	public function setup() {
		 $this->typoScriptParser = t3lib_div::makeInstance('t3lib_TSparser');
		 $this->typoScriptParser->parse($this->tsConfigString);
		 $this->tsConfig = Tx_Extbase_Utility_TypoScript::convertTypoScriptArrayToPlainArray($this->typoScriptParser->setup);
	     $this->configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->tsConfig['plugin']['tx_ptextlist']['settings']);
	}
	
	

	public function testSetUp() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
	}
	
	
	
	public function testInjectBackendConfiguration() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->injectBackendConfiguration($this->configurationBuilder->getBackendConfiguration());
	}
	
	
	
	public function testInjectDataMapper() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataMapperMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper', array(), array());
		$dataBackend->injectDataMapper($dataMapperMock);
	}
	
	
	
	public function testInjectDataSource() {
        $dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataSourceMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource', array('executeQuery'), array(new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration(array())));
		$dataBackend->injectDataSource($dataSourceMock);
	}
	
	
	
	public function testInjectFilterboxCollection() {
        $dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $filterBoxCollectionMock = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection($this->configurationBuilder);
		$dataBackend->injectfilterboxCollection($filterBoxCollectionMock);
	}
	
	
	
	public function testInjectPager() {
        $dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $pagerMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager');		
		$dataBackend->injectPager($pagerMock);
	}
	
	
	
	public function testInjectQueryInterpreter() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$queryInterpreterMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter');
		$dataBackend->injectQueryInterpreter($queryInterpreterMock);
	}
	
	
	
	public function testBuildFromPart() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->injectBackendConfiguration($this->configurationBuilder->getBackendConfiguration());
		$fromPart = $dataBackend->buildFromPart();
		$this->assertTrue($fromPart == $this->tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list1']['backendConfig']['tables']);
	}
	
	
	
	public function testGetBaseWhereClause() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->injectBackendConfiguration($this->configurationBuilder->getBackendConfiguration());
		$baseWhereClause = $dataBackend->getBaseWhereClause();
		$this->assertTrue($baseWhereClause == $this->tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list1']['backendConfig']['baseWhereClause']);
	}
	
	
	
	public function testGetWhereClauseFromFilter() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
		
		$filterMock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '>'));
		
		$filterWhereClause = $dataBackend->getWhereClauseFromFilter($filterMock);
		$this->assertTrue($filterWhereClause == '`test` > "10"', 'Filter where clause was expected to be "test > 10" but was ' . $filterWhereClause);
	}
	
	
	
	public function testGetWhereClauseFromFilterbox() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
        
        $filter1Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '>'));
        $filter2Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '<'));
            
        $filterBox = $this->getFilterboxByArrayOfFilters(array($filter1Mock, $filter2Mock));

        $whereClauseFromFilterbox = $dataBackend->getWhereClauseFromFilterbox($filterBox);
        $this->assertTrue($whereClauseFromFilterbox == '(`test` > "10") AND (`test` < "10")', 'Where clause from filterbox was expected to be "(test > 10) AND (test < 10)" but was ' . $whereClauseFromFilterbox);
	}
	
	
	
	public function testGetWhereClauseFromFilterboxCollection() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
		
		$filter1Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '>'));
        $filter2Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '<'));
        $filter3Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 20, '>'));
        $filter4Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 20, '<'));
        
        $filterbox1 = $this->getFilterboxByArrayOfFilters(array($filter1Mock, $filter2Mock));
        $filterbox2 = $this->getFilterboxByArrayOfFilters(array($filter3Mock, $filter4Mock));
        
        $filterboxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection($this->configurationBuilder);
        $filterboxCollection->addItem($filterbox1);
        $filterboxCollection->addItem($filterbox2);
        
        $dataBackend->injectfilterboxCollection($filterboxCollection);
        $whereClauseForFilterboxCollection = $dataBackend->getWhereClauseFromFilterboxes();
        $this->assertTrue($whereClauseForFilterboxCollection == '((`test` > "10") AND (`test` < "10")) AND ((`test` > "20") AND (`test` < "20"))', 'Where clause for filterbox collection should have been "((test > 10) AND (test < 10)) AND ((test > 20) AND (test < 20))" but was ' . $whereClauseForFilterboxCollection);
        
	}
	
	
	
	public function testGetLimitPart() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
        
        $pagerMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager');
        $pagerMock->expects($this->any())
            ->method('getCurrentPage')
            ->will($this->returnValue(10));
        $pagerMock->expects($this->any())
            ->method('getItemsPerPage')
            ->will($this->returnValue(10));
        
        $dataBackend->injectPager($pagerMock);
            
        $limitPart = $dataBackend->buildLimitPart();
        $this->assertTrue($limitPart == '100:10', 'Limit part of pager was expected to be 10:10 but was ' . $limitPart);
	}
	
	
	
	public function testBuildSelectPart() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());

        $fieldConfigurationCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
        $fieldConfigurationCollection->addItem($this->getFieldConfigMockForTableAndFieldAndIdentifier('table1', 'field1', 'test1'));
        $fieldConfigurationCollection->addItem($this->getFieldConfigMockForTableAndFieldAndIdentifier('table1', 'field2', 'test2'));
        $dataBackend->injectFieldConfigurationCollection($fieldConfigurationCollection);
        
        $selectPartForFieldConfigurationCollection = $dataBackend->buildSelectPart();
        
        $this->assertTrue($selectPartForFieldConfigurationCollection == 'table1.field1, table1.field2', 'Select part for field configuration collection should be "table1.field1, table1.field2" but was ' . $selectPartForFieldConfigurationCollection);
	}
	
	
	
	
	public function testGetSelectPartFromFieldConfiguration() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
        
        $fieldConfigurationMock = $this->getFieldConfigMockForTableAndFieldAndIdentifier('table', 'field', 'test');
       
        $selectPartForFieldConfiguration = $dataBackend->getSelectPartFromFieldConfiguration($fieldConfigurationMock);
        $this->assertTrue($selectPartForFieldConfiguration == 'table.field', 'Expected select part to be "table.field" but was ' . $selectPartForFieldConfiguration);
	}
	
	
	
	public function testGetListData() {
		$dataSourceReturnArray = array(
            array('t1.f1' => 'v1_1', 't1.f2' => 'v1_2', 't1.f3' => 'v1_3','t2.f1' => 'v1_4', 't2.f2' => 'v1_5'),
            array('t1.f1' => 'v2_1', 't1.f2' => 'v2_2', 't1.f3' => 'v2_3','t2.f1' => 'v2_4', 't2.f2' => 'v2_5'),
            array('t1.f1' => 'v3_1', 't1.f2' => 'v3_2', 't1.f3' => 'v3_3','t2.f1' => 'v3_4', 't2.f2' => 'v3_5'),
            array('t1.f1' => 'v4_1', 't1.f2' => 'v4_2', 't1.f3' => 'v4_3','t2.f1' => 'v4_4', 't2.f2' => 'v4_5'),
            array('t1.f1' => 'v5_1', 't1.f2' => 'v5_2', 't1.f3' => 'v5_3','t2.f1' => 'v5_4', 't2.f2' => 'v5_5'),
            array('t1.f1' => 'v6_1', 't1.f2' => 'v6_2', 't1.f3' => 'v6_3','t2.f1' => 'v6_4', 't2.f2' => 'v6_5'),
            array('t1.f1' => 'v7_1', 't1.f2' => 'v7_2', 't1.f3' => 'v7_3','t2.f1' => 'v7_4', 't2.f2' => 'v7_5'),
            array('t1.f1' => 'v8_1', 't1.f2' => 'v8_2', 't1.f3' => 'v8_3','t2.f1' => 'v8_4', 't2.f2' => 'v8_5'),
        );

        $dataSourceMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource', array('executeQuery'), array(new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration(array())));
        $dataSourceMock->expects($this->once())
            ->method('executeQuery')
            ->will($this->returnValue($dataSourceReturnArray));
            
        $pagerMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager');
        $pagerMock->expects($this->any())
            ->method('getCurrentPage')
            ->will($this->returnValue(10));
        $pagerMock->expects($this->any())
            ->method('getItemsPerPage')
            ->will($this->returnValue(10));
            
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->injectBackendConfiguration($this->configurationBuilder->getBackendConfiguration());
        $dataBackend->injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
        $dataBackend->injectDataSource($dataSourceMock);
        $dataBackend->injectPager($pagerMock);
        
        $dataBackend->getListData();
	}
	
	
	
	protected function getFieldConfigMockForTableAndFieldAndIdentifier($table, $field, $identifier) {
		$fieldConfigurationMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig', array(), array($identifier, array('table' => $table, 'field' => $field)));
        $fieldConfigurationMock->expects($this->any())
            ->method('getTable')
            ->will($this->returnValue($table));
        $fieldConfigurationMock->expects($this->any())
            ->method('getField')
            ->will($this->returnValue($field));
        return $fieldConfigurationMock;
	}
	
	
	
	protected function getFilterboxByArrayOfFilters($filtersArray) {
		$filterBoxConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilder, 'test', array());
        $filterBox = new Tx_PtExtlist_Domain_Model_Filter_Filterbox($filterBoxConfiguration);
        foreach($filtersArray as $filter) {
        	$filterBox->addItem($filter);
        }
        return $filterBox;
	}
	
	
	
	
	protected function getFilterMockByCriteria($criteria) {
		$filterQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
        $filterQuery->addCriteria($criteria);
        $filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter', array('getFilterQuery'));
        $filterMock->expects($this->once())
            ->method('getFilterQuery')
            ->will($this->returnValue($filterQuery));
        return $filterMock;
	}
	
    	
}

?>