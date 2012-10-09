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
 * Testcase for mysql data backend 
 * 
 * @author Daniel Lienert 
 * @author Michael Knoll 
 * @package Tests
 * @subpackage Domain\DataBackend\MySqlDataBackend
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_MySqlDataBackendTest extends Tx_PtExtlist_Tests_Domain_DataBackend_AbstractDataBackendBaseTest {

	/** @test */
	public function makeSureClassExists() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
	}
	
	

	/** @test */
	public function dataBackendConfigurationCanBeInjected() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->_injectBackendConfiguration($this->configurationBuilder->buildDataBackendConfiguration());
	}
	
	

	/** @test */
	public function dataMapperCanBeInjected() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataMapperMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper', array(), array($this->configurationBuilder));
		$dataBackend->_injectDataMapper($dataMapperMock);
	}
	
	

	/** @test */
	public function dataSourceCanBeInjected() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataSourceMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource', 
		 								 array('executeQuery'), 
										 array(new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($this->configurationBuilder->buildDataBackendConfiguration()->getDataSourceSettings())
										 	)
										 );
		$dataBackend->_injectDataSource($dataSourceMock);
	}
	
	

	/** @test */
	public function filterboxCollectionCanBeInjected() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$filterBoxCollectionMock = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection($this->configurationBuilder);
		$dataBackend->_injectfilterboxCollection($filterBoxCollectionMock);
	}
	
	

	/** @test */
	public function pagerCollectionCanBeInjected() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);


		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('isEnabled', 'getCurrentPage', 'getItemsPerPage'), array(),'',FALSE);
		$pagerCollectionMock->expects($this->any())->method('isEnabled')->will($this->returnValue(true));
		$pagerCollectionMock->expects($this->any())->method('getCurrentPage')->will($this->returnValue(1));
		$pagerCollectionMock->expects($this->any())->method('getItemsPerPage')->will($this->returnValue(1));


		$dataBackend->_injectPagerCollection($pagerCollectionMock);
	}
	
	

	/** @test */
	public function queryInterpreterCanBeInjected() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$queryInterpreterMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter');
		$dataBackend->_injectQueryInterpreter($queryInterpreterMock);
	}
	
	

	/** @test */
	public function buildFromPartCreatesExpectedFromPart() {
		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setItemCount'), array(), '', false, false);
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->_injectBackendConfiguration($this->configurationBuilder->buildDataBackendConfiguration());
		$dataBackend->_injectPagerCollection($pagerCollectionMock);
		$dataBackend->init();
		$fromPart = $dataBackend->buildFromPart();
		$this->assertEquals($fromPart, 
							$this->tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list1']['backendConfig']['tables'],
							'Test expected . ' .$this->tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list1']['backendConfig']['tables'] . ' but recieved ' . $fromPart
		);
	}

    

	/** @test */
	public function buildFromPartCreatesExpectedFromPartWithGivenBaseFromClause() {
		$tsConfig = $this->tsConfig;
		$tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list2'] = $this->tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list1'];
		$tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list2']['backendConfig']['baseFromClause'] = 'static_countries';
		$tsConfig['plugin']['tx_ptextlist']['settings']['listIdentifier'] = 'list2';
		
		Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($tsConfig['plugin']['tx_ptextlist']['settings']);
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('list2');
		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setItemCount'), array(), '', false, false);
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($configurationBuilder);
		$dataBackend->_injectBackendConfiguration($configurationBuilder->buildDataBackendConfiguration());
		$dataBackend->_injectPagerCollection($pagerCollectionMock);
		$dataBackend->init();
		$fromPart = $dataBackend->buildFromPart();
		$this->assertEquals($fromPart, 
							$tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list2']['backendConfig']['baseFromClause'],
							'Test expected . ' .$tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list2']['backendConfig']['baseFromClause'] . ' but recieved ' . $fromPart
		);
	}
	
	

	/** @test */
	public function getWhereClauseReturnsExpectedWhereClause() {
		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setItemCount'), array(), '', false, false);
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->_injectBackendConfiguration($this->configurationBuilder->buildDataBackendConfiguration());
		$dataBackend->_injectPagerCollection($pagerCollectionMock);
		$dataBackend->init();
		$baseWhereClause = $dataBackend->getBaseWhereClause();
		$this->assertTrue($baseWhereClause == $this->tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list1']['backendConfig']['baseWhereClause']);
	}
	
	

	/** @test */
	public function getWhereClauseReturnsWhereClauseFromStringFilterReturnsExpectedValue() {
		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setItemCount'), array(), '', false, false);
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->_injectPagerCollection($pagerCollectionMock);
		$dataBackend->_injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
		
		$filterMock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 'testValue', '='));
		
		$filterWhereClause = $dataBackend->getWhereClauseFromFilter($filterMock);
		$this->assertTrue($filterWhereClause == 'test = "testValue"', 'Filter where clause was expected to be <test = "testValue"> but was ' . $filterWhereClause);
	}



	/** @test */
	public function getWhereClauseFromFilterWithStringNumericValueReturnsExpectedValue() {
		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setItemCount'), array(), '', false, false);
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->_injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
		$dataBackend->_injectPagerCollection($pagerCollectionMock);

		$filterMock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', '10', '>'));

		$filterWhereClause = $dataBackend->getWhereClauseFromFilter($filterMock);
		$this->assertTrue($filterWhereClause == 'test > 10', 'Filter where clause was expected to be "test > 10" but was ' . $filterWhereClause);
	}



	/** @test */
	public function getWhereClauseFromFilterWithoutActiveFilterReturnsExpectedValue() {
		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setItemCount'), array(), '', false, false);
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->_injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
		$dataBackend->_injectPagerCollection($pagerCollectionMock);

		$filterMock = $this->getFilterMockByCriteria();
		
		$filterWhereClause = $dataBackend->getWhereClauseFromFilter($filterMock);
		$this->assertTrue($filterWhereClause == '', 'Filter where clause was expected to be "" but was ' . $filterWhereClause);
	}
	
	

	/** @test */
	public function getWhereClauseFromFilterboxReturnsExpectedString() {
		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setItemCount'), array(), '', false, false);
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
		$dataBackend->_injectPagerCollection($pagerCollectionMock);

        $filter1Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '>'));
        $filter2Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '<'));
            
        $filterBox = $this->getFilterboxByArrayOfFilters(array($filter1Mock, $filter2Mock));

        $whereClauseFromFilterbox = $dataBackend->getWhereClauseFromFilterbox($filterBox);
        $this->assertTrue($whereClauseFromFilterbox == '(test > 10) AND (test < 10)', 'Where clause from filterbox was expected to be "(test > 10) AND (test < 10)" but was ' . $whereClauseFromFilterbox);
	}
	
	

	/** @test */
	public function getWhereClauseFromFilterboxCollectionReturnsExpectedString() {
		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setItemCount'), array(), '', false, false);
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->_injectPagerCollection($pagerCollectionMock);
		$dataBackend->_injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());

		$filter1Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '>'));
		$filter2Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '<'));
		$filter3Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 20, '>'));
		$filter4Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 20, '<'));

		$filterbox1 = $this->getFilterboxByArrayOfFilters(array($filter1Mock, $filter2Mock));
		$filterbox2 = $this->getFilterboxByArrayOfFilters(array($filter3Mock, $filter4Mock));

		$filterboxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection($this->configurationBuilder);
		$filterboxCollection->addItem($filterbox1);
		$filterboxCollection->addItem($filterbox2);

		$dataBackend->_injectfilterboxCollection($filterboxCollection);
		$whereClauseForFilterboxCollection = $dataBackend->getWhereClauseFromFilterboxes();
		$this->assertTrue($whereClauseForFilterboxCollection == '((test > 10) AND (test < 10)) AND ((test > 20) AND (test < 20))', 'Where clause for filterbox collection should have been "((test > 10) AND (test < 10)) AND ((test > 20) AND (test < 20))" but was ' . $whereClauseForFilterboxCollection);

	}
	
	

	/** @test */
	public function getLimitPartReturnsExpectedString() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());

        $pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('isEnabled', 'getCurrentPage', 'getItemsPerPage'), array($this->configurationBuilder));
        $pagerCollectionMock->expects($this->any())
            ->method('getCurrentPage')
            ->will($this->returnValue(10));
        $pagerCollectionMock->expects($this->any())
            ->method('getItemsPerPage')
            ->will($this->returnValue(10));
        $pagerCollectionMock->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValue(true));
        
        $dataBackend->_injectPagerCollection($pagerCollectionMock);
            
        $limitPart = $dataBackend->buildLimitPart();
        $this->assertTrue($limitPart == '90,10', 'Limit part of pager was expected to be 90,10 but was ' . $limitPart);
	}
	
	

	/** @test */
	public function buildSelectPartWithTableAndFieldReturnsExpectedString() {
		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setItemCount'), array(), '', false, false);
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->_injectPagerCollection($pagerCollectionMock);
        $dataBackend->_injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());

        $fieldConfigurationCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
        $fieldConfigurationCollection->addItem($this->getFieldConfigMockForTableAndFieldAndIdentifier('table1', 'field1', 'test1'));
        $fieldConfigurationCollection->addItem($this->getFieldConfigMockForTableAndFieldAndIdentifier('table1', 'field2', 'test2'));
        $dataBackend->_injectFieldConfigurationCollection($fieldConfigurationCollection);
        
        $selectPartForFieldConfigurationCollection = $dataBackend->buildSelectPart();
        
        $expectedSelectPart = Tx_PtExtlist_Utility_DbUtils::getAliasedSelectPartByFieldConfig($fieldConfigurationCollection[0]) . ', ' .
            Tx_PtExtlist_Utility_DbUtils::getAliasedSelectPartByFieldConfig($fieldConfigurationCollection[1]);
        
        $this->assertTrue($selectPartForFieldConfigurationCollection == $expectedSelectPart, 
            'Select part for field configuration collection should be "' . $expectedSelectPart . '" but was ' . $selectPartForFieldConfigurationCollection);
	}

	

	/** @test */
	public function buildSelectPartWithSpecialStringReturnsExpectedString() {
		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setItemCount'), array(), '', false, false);
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->_injectPagerCollection($pagerCollectionMock);
        $dataBackend->_injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());

        $fieldConfigurationCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
        $fieldConfigurationCollection->addItem($this->getFieldConfigMockForSpecialAndIdentifier('specialString', 'testFieldIdentifier'));
        $dataBackend->_injectFieldConfigurationCollection($fieldConfigurationCollection);
        
        $selectPartForFieldConfigurationCollection = $dataBackend->buildSelectPart();
        
        $expectedSelectPart = '(specialString) AS testFieldIdentifier';
        
        $this->assertTrue($selectPartForFieldConfigurationCollection == $expectedSelectPart, 
            'Select part for field configuration collection should be "' . $expectedSelectPart . '" but was ' . $selectPartForFieldConfigurationCollection);
	}
	
	

	/** @test */
	public function getListDataReturnsExpectedListData() {
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

        $dataSourceMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource', 
        									array('executeQuery', 'fetchAll'),
        									array(new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($this->configurationBuilder->buildDataBackendConfiguration()->getDataBackendSettings())));

		$dataSourceMock->expects($this->once())
            ->method('executeQuery')
            ->will($this->returnValue($dataSourceMock));

		$dataSourceMock->expects($this->once())
			->method('fetchAll')
			->will($this->returnValue($dataSourceReturnArray));
            
        $pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('isEnabled', 'getCurrentPage', 'getItemsPerPage'), array($this->configurationBuilder));
        $pagerCollectionMock->expects($this->any())
            ->method('getCurrentPage')
            ->will($this->returnValue(10));
        $pagerCollectionMock->expects($this->any())
            ->method('getItemsPerPage')
            ->will($this->returnValue(10));
        $pagerCollectionMock->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValue(true));

        $sortingStateCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection', array('getSortingsQuery'), array(), '', FALSE);
        $sortingStateCollectionMock->expects($this->any())->method('getSortingsQuery')->will($this->returnValue(new Tx_PtExtlist_Domain_QueryObject_Query()));
        $sorterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_Sorter', array('getSortingStateCollection'), array(), '', FALSE);
        $sorterMock->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue($sortingStateCollectionMock));

        $mapperMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper', array(), array($this->configurationBuilder));
        $mapperMock->expects($this->once())
            ->method('getMappedListData')
            ->will($this->returnValue($dataSourceReturnArray));

        $filterboxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', array('getExcludeFilters'), array(), '', FALSE);
        $filterboxCollectionMock->expects($this->any())->method('excludeFilters')->will($this->returnValue(array()));
                
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		$dataBackend->_injectBackendConfiguration($this->configurationBuilder->buildDataBackendConfiguration());
        $dataBackend->_injectQueryInterpreter(new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
        $dataBackend->_injectDataSource($dataSourceMock);
        $dataBackend->_injectPagerCollection($pagerCollectionMock);
        $dataBackend->_injectDataMapper($mapperMock);
        $dataBackend->_injectSorter($sorterMock);
        $dataBackend->_injectFilterboxCollection($filterboxCollectionMock);
        $dataBackend->init();
        
        $listData = $dataBackend->getListData();
        $this->assertTrue($listData == $dataSourceReturnArray);
	}



	/** @test */
	public function listDataCacheWorksAsExpected() {
		/* @var $dataBackend Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend */
		$dataBackend = $this->getAccessibleMock('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend', array('buildListData'), array($this->configurationBuilder));
		$dataBackend->expects($this->once())
					->method('buildListData');

		$dataBackend->getListData();
		$dataBackend->_set('listData', array('x' => 'y'));

		$dataBackend->getListData();
	}



	/** @test */
	public function resetListDataResetsListData() {
		$dataBackend = $this->getAccessibleMock('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend', array('buildListData'), array($this->configurationBuilder));
		$dataBackend->expects($this->exactly(2))
			->method('buildListData');

		$dataBackend->getListData();
		$dataBackend->_set('listData', array());
		$dataBackend->_set('listQueryParts', array());

		$dataBackend->resetListDataCache();
		$this->assertEquals($dataBackend->_get('listQueryParts'), NULL);

		$dataBackend->getListData();
	}
	
	

	/** @test */
	public function getGroupDataReturnsExpectedData() {
		$dataBackend = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
		
		$fields = array('field1', 'field2', 'field3');
		$excludeFilters = array('filterbox1.filter1', 'filterbox1.filter2', 'filterbox2.filter1');
		$additionalQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
		$additionalQuery->addCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria::greaterThan('field1', 10));
		
		$returnArray = array('test');

        $sortingStateCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection', array('getSortingsQuery'), array(), '', FALSE);
        $sortingStateCollectionMock->expects($this->any())->method('getSortingsQuery')->will($this->returnValue(new Tx_PtExtlist_Domain_QueryObject_Query()));
        $sorterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_Sorter', array('getSortingStateCollection'), array(), '', FALSE);
        $sorterMock->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue($sortingStateCollectionMock));

		
		$queryInterpreterMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',array('interpretQuery'), array(), '', FALSE);
        $dataSourceMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource', array('executeQuery', 'fetchAll'), array(), '', FALSE);

		$dataSourceMock->expects($this->once())
			->method('executeQuery')
			->will($this->returnValue($dataSourceMock));

		$dataSourceMock->expects($this->once())
			->method('fetchAll')
			->will($this->returnValue($returnArray));


        $filterboxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', array('getExcludeFilters'), array(), '', FALSE);
        $filterboxCollectionMock->expects($this->any())->method('excludeFilters')->will($this->returnValue(array()));

        $dataBackend->_injectBackendConfiguration($this->configurationBuilder->buildDataBackendConfiguration());
        $dataBackend->_injectPagerCollection(Tx_PtExtlist_Domain_Model_Pager_PagerCollectionFactory::getInstance($this->configurationBuilder));
	    $dataBackend->_injectDataSource($dataSourceMock);
		$dataBackend->_injectQueryInterpreter($queryInterpreterMock);
        $dataBackend->_injectSorter($sorterMock);
        $dataBackend->_injectFilterboxCollection($filterboxCollectionMock);
		$dataBackend->init();
		
		$groupData = $dataBackend->getGroupData($additionalQuery, $excludeFilters);
		$this->assertEquals($returnArray, $groupData);
	}
	
	

	public function testCreateDataSource() {
		/* PROBLEM: PDO is not installed on devel server */
		// hint: Database parameters are taken from current T3 database configuration
		#$dataSource = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend::createDataSource($this->configurationBuilder);
		#$this->assertTrue(is_a($dataSource, 'Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource'));
	}



	/** @test */
	public function convertTableFieldToAliasReturnsExpectedStrings() {
		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend');
		$dataBackend = new $accessibleClassName($this->configurationBuilder);
		$dataBackend->_injectFieldConfigurationCollection($this->configurationBuilder->buildFieldsConfiguration());
		
		$strings[] = array('test' => 'table1.field1 AS fieldIndentifier1', 'return' =>'fieldIndentifier1'); 
		$strings[] = array('test' => '(someSepcial table1.field1) AS SpecialIdentifier', 'return' =>'(someSepcial fieldIndentifier1) AS SpecialIdentifier'); 
		$strings[] = array('test' => 'GROUP BY table1.field1', 'return' =>'GROUP BY fieldIndentifier1'); 
		
		foreach($strings as $string) {
			$returnString = $dataBackend->_call('convertTableFieldToAlias', $string['test']);
			$this->assertEquals($string['return'], $returnString, 'Mangled string is not as excepted : ' . $returnString . ' is not ' . $string['return']);
		}
	}



	/** @test */
	public function testBuildQuery() {
		$this->markTestIncomplete();
	}
	
	

	/** @test */
	public function testBuildWherePart() {
		$this->markTestIncomplete();
	}
	
	

	/** @test */
	public function testBuildOrderByPart() {
		$this->markTestIncomplete();
	}
	
	

	/** @test */
	public function testGetTotalItemsCount() {
		$this->markTestIncomplete();
	}



	/** @test */
	public function getGroupByPartReturnsExpectedString() {
		$dataBackend = $this->getDataBackend();
		$groupByPart = $dataBackend->buildGroupByPart();
		$this->assertEquals($groupByPart, 'company');
	}
	


	/** @test */
	public function getAggregatesByConfigCollectionReturnsExpectedString() {
		$configOverwrite['listConfig']['test']['aggregateData']['sumField1']['scope'] = 'query';
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance(NULL, $configOverwrite);
		$aggConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactory::getInstance($configurationBuilderMock);
		$dataBackend = $this->getDataBackend($configurationBuilderMock);
		
		$this->assertEquals('SUM(field1) AS sumField1', $dataBackend->_call('buildAggregateFieldSQLByConfig', $aggConfigCollection->getAggregateConfigByIdentifier('sumField1')));
	}



	/** @test */
	public function getAggregatesByConfigCollectionWithUnsopportedMethodThrowsException() {
		$configOverwrite['listConfig']['test']['aggregateData']['sumField1'] = array('scope' => 'query', 'method' => 'aNotExistantMethod');
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance(NULL, $configOverwrite);
		$aggConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactory::getInstance($configurationBuilderMock);
		$dataBackend = $this->getDataBackend($configurationBuilderMock);
		
		try {
			$dataBackend->_call('buildAggregateFieldSQLByConfig', $aggConfigCollection->getAggregateConfigByIdentifier('sumField1'));
		} catch (Exception $e) {
			return;
		}
		
		$this->fail('No Exception thrown if Method is unsupported');
	}



	/** @test */
	public function getAggregatesByConfigCollectionWithSpecialStringReturnsExpectedString() {
		$configOverwrite['listConfig']['test']['aggregateData']['sumField1'] = array('scope' => 'query', 'special' => 'special');
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance(NULL, $configOverwrite);
		$aggConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactory::getInstance($configurationBuilderMock);
		$dataBackend = $this->getDataBackend($configurationBuilderMock);
		
		$this->assertEquals('special AS sumField1', $dataBackend->_call('buildAggregateFieldSQLByConfig', $aggConfigCollection->getAggregateConfigByIdentifier('sumField1')));
	}
	
	

	/** @test */
	public function buildAggregateSqlByConfigCollectionReturnsExpectedSql() {
		$configOverwrite['listConfig']['test']['aggregateData']['sumField1'] = array('scope' => 'query', 'special' => 'special');
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance(NULL, $configOverwrite);
		$aggConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactory::getInstance($configurationBuilderMock);
		$dataBackend = $this->getDataBackend($configurationBuilderMock);

        $filterboxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', array('getExcludeFilters'), array(), '', FALSE);
        $filterboxCollectionMock->expects($this->any())->method('excludeFilters')->will($this->returnValue(array()));

        $dataBackend->_injectFilterboxCollection($filterboxCollectionMock);

		$sql = $dataBackend->_call('buildAggregateSQLByConfigCollection', $aggConfigCollection);

		$this->assertEquals('SELECT special AS sumField1, AVG(field2) AS avgField2 
 FROM (SELECT tableName1.fieldName1 AS field1, tableName2.fieldName2 AS field2, (special) AS field3, tableName4.fieldName4 AS field4 
FROM companies 
WHERE employees > 0 
GROUP BY company 
)  AS AGGREGATEQUERY', $sql);
	}


	/**
	 * @test
	 */
	public function getIterationListData() {
		$dataBackend = $this->getDataBackend($this->configurationBuilder);

		$filterBoxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', array('getExcludeFilters'), array(), '', FALSE);
		$filterBoxCollectionMock->expects($this->any())->method('excludeFilters')->will($this->returnValue(array()));
		$dataBackend->_injectFilterboxCollection($filterBoxCollectionMock);

		$iterationListData = $dataBackend->getIterationListData();

		$this->assertInstanceOf('Tx_PtExtlist_Domain_Model_List_IterationListDataInterface', $iterationListData);
	}
	
	
	/**********************************************************************************************************************************************************
	 * Helper methods 
	 **********************************************************************************************************************************************************/


	/**
	 * @param null $configurationBuilderMock
	 * @return Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend
	 */
	protected function getDataBackend($configurationBuilderMock = NULL) {
		
		if(!is_a($configurationBuilderMock, 'Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock')) {
			$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();	
		}
		
		$dataBackendAccessible = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend');
		$dataBackend = new $dataBackendAccessible($configurationBuilderMock); /** @var $dataBackend  Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend */
				
		$queryInterpreterMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',array('interpretQuery'), array(), '', FALSE);
        $dataSourceMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource', array('executeQuery'), array(), '', FALSE);

        $sortingStateCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection', array('getSortingsQuery'), array(), '', FALSE);
        $sortingStateCollectionMock->expects($this->any())->method('getSortingsQuery')->will($this->returnValue(new Tx_PtExtlist_Domain_QueryObject_Query()));
        $sorterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_Sorter', array('getSortingStateCollection'), array(), '', FALSE);
        $sorterMock->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue($sortingStateCollectionMock));

		$dataMapperMock = new Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper($configurationBuilderMock);
        $pagerCollection = Tx_PtExtlist_Domain_Model_Pager_PagerCollectionFactory::getInstance($configurationBuilderMock);
        
        $dataBackend->_injectBackendConfiguration($configurationBuilderMock->buildDataBackendConfiguration());
	    $dataBackend->_injectDataSource($dataSourceMock);
		$dataBackend->_injectQueryInterpreter($queryInterpreterMock);
		$dataBackend->_injectFieldConfigurationCollection($configurationBuilderMock->buildFieldsConfiguration());
		$dataBackend->_injectPagerCollection($pagerCollection);
        $dataBackend->_injectSorter($sorterMock);
		$dataBackend->_injectDataMapper($dataMapperMock);

		$dataBackend->init();
		
		return $dataBackend;
	}



	protected function getFieldConfigMockForTableAndFieldAndIdentifier($table, $field, $identifier) {
		$fieldConfigurationMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig', array(), array($this->configurationBuilder,$identifier, array('table' => $table, 'field' => $field)));
        $fieldConfigurationMock->expects($this->any())
            ->method('getTable')
            ->will($this->returnValue($table));
        $fieldConfigurationMock->expects($this->any())
            ->method('getField')
            ->will($this->returnValue($field));
        return $fieldConfigurationMock;
	}


	
	protected function getFieldConfigMockForSpecialAndIdentifier($special, $identifier) {
		$fieldConfigurationMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig', array(), array($this->configurationBuilder,$identifier, array('special' => $special)));
        $fieldConfigurationMock->expects($this->any())
            ->method('getSpecial')
            ->will($this->returnValue($special));
        $fieldConfigurationMock->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($identifier));
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
	
	
	
	protected function getFilterMockByCriteria($criteria = NULL) {
		$filterQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
        
		if($criteria != NULL) {
        	$filterQuery->addCriteria($criteria);	
        }
		
        $filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter', array('getFilterQuery'));
        $filterMock->expects($this->once())
            ->method('getFilterQuery')
            ->will($this->returnValue($filterQuery));
        return $filterMock;
	}
	
	
	
	protected function getHeaderColumnBySortingFieldAndDirection($field, $direction) {
		$sortingQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
        $sortingQuery->addSorting($field, $direction);
        $headerMock = $this->getMock('Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', array('getSortingQuery'));
        $headerMock->expects($this->once())
            ->method('getSortingQuery')
            ->will($this->returnValue($sortingQuery));
        return $headerMock;
	}

}
?>