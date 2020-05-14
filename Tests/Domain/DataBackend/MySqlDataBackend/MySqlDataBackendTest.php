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
class Tx_PtExtlist_Tests_Domain_DataBackend_MySqlDataBackendTest extends Tx_PtExtlist_Tests_Domain_DataBackend_AbstractDataBackendBaseTest
{
    /** @test */
    public function makeSureClassExists()
    {
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
    }


    /** @test */
    public function dataBackendConfigurationCanBeInjected()
    {
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectBackendConfiguration($this->configurationBuilder->buildDataBackendConfiguration());
    }


    /** @test */
    public function dataMapperCanBeInjected()
    {
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataMapperMock = $this->getMock('Mapper_ArrayMapper', [], []);
        $dataBackend->_injectDataMapper($dataMapperMock);
    }


    /** @test */
    public function dataSourceCanBeInjected()
    {
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataSourceMock = $this->getMock('DataSource_MySqlDataSource',
            ['executeQuery'],
            [new DataSource_DatabaseDataSourceConfiguration($this->configurationBuilder->buildDataBackendConfiguration()->getDataSourceSettings())
            ]
        );
        $dataBackend->_injectDataSource($dataSourceMock);
    }


    /** @test */
    public function filterboxCollectionCanBeInjected()
    {
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $filterBoxCollectionMock = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection($this->configurationBuilder);
        $dataBackend->_injectfilterboxCollection($filterBoxCollectionMock);
    }


    /** @test */
    public function pagerCollectionCanBeInjected()
    {
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);


        $pagerCollectionMock = $this->getMock('PagerCollection', ['isEnabled', 'getCurrentPage', 'getItemsPerPage'], [], '', false);
        $pagerCollectionMock->expects($this->any())->method('isEnabled')->will($this->returnValue(true));
        $pagerCollectionMock->expects($this->any())->method('getCurrentPage')->will($this->returnValue(1));
        $pagerCollectionMock->expects($this->any())->method('getItemsPerPage')->will($this->returnValue(1));


        $dataBackend->_injectPagerCollection($pagerCollectionMock);
    }


    /** @test */
    public function queryInterpreterCanBeInjected()
    {
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $queryInterpreterMock = $this->getMock('MySqlDataBackend_MySqlInterpreter_MySqlInterpreter');
        $dataBackend->_injectQueryInterpreter($queryInterpreterMock);
    }


    /** @test */
    public function buildFromPartCreatesExpectedFromPart()
    {
        $pagerCollectionMock = $this->getMock('PagerCollection', ['setItemCount'], [], '', false, false);
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectBackendConfiguration($this->configurationBuilder->buildDataBackendConfiguration());
        $dataBackend->_injectPagerCollection($pagerCollectionMock);
        $dataBackend->init();
        $fromPart = $dataBackend->buildFromPart();
        $this->assertEquals($fromPart,
            $this->tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list1']['backendConfig']['tables'],
            'Test expected . ' . $this->tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list1']['backendConfig']['tables'] . ' but recieved ' . $fromPart
        );
    }

    /**
     * @test
     */
    public function buildQueryUsesFluidProcessorIfFluidInlineFluidSyntaxWasFound()
    {
        $pagerCollectionMock = $this->getMock('PagerCollection', ['setItemCount'], [], '', false, false);

        $dataBackend = $this->objectManager->get('MySqlDataBackend_MySqlDataBackend', $this->configurationBuilder); /**  */
        $dataBackend->_injectPagerCollection($pagerCollectionMock);
        $dataBackend->_injectQueryInterpreter(new MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());

        $filterboxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection($this->configurationBuilder);

        $filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter', ['getValue']);
        $filterMock->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue(20));
        $filterBox = $this->getFilterboxByArrayOfFilters(['theFilter' => $filterMock]);
        $filterboxCollection->addFilterBox($filterBox, 'theBox');

        $dataBackend->_injectfilterboxCollection($filterboxCollection);

        $query = 'SELECT * FROM test WHERE uid = 10';
        $expected = 'SELECT * FROM test WHERE uid = 10';

        $this->assertEquals($expected, $dataBackend->processQueryWithFluid($query));


        $query = 'SELECT * FROM test WHERE uid = {filters.theBox.theFilter.value}';
        $expected = 'SELECT * FROM test WHERE uid = 20';

        $this->assertEquals($expected, $dataBackend->processQueryWithFluid($query));
    }


    /** @test */
    public function buildFromPartCreatesExpectedFromPartWithGivenBaseFromClause()
    {
        $tsConfig = $this->tsConfig;
        $tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list2'] = $this->tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list1'];
        $tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list2']['backendConfig']['baseFromClause'] = 'static_countries';
        $tsConfig['plugin']['tx_ptextlist']['settings']['listIdentifier'] = 'list2';

        $configurationBuilder = new ConfigurationBuilder($tsConfig['plugin']['tx_ptextlist']['settings'], 'list2');
        $pagerCollectionMock = $this->getMock('PagerCollection', ['setItemCount'], [], '', false, false);
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($configurationBuilder);
        $dataBackend->_injectBackendConfiguration($configurationBuilder->buildDataBackendConfiguration());
        $dataBackend->_injectPagerCollection($pagerCollectionMock);
        $dataBackend->init();
        $fromPart = $dataBackend->buildFromPart();
        $this->assertEquals($fromPart,
            $tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list2']['backendConfig']['baseFromClause'],
            'Test expected . ' . $tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list2']['backendConfig']['baseFromClause'] . ' but recieved ' . $fromPart
        );
    }


    /** @test */
    public function getWhereClauseReturnsExpectedWhereClause()
    {
        $pagerCollectionMock = $this->getMock('PagerCollection', ['setItemCount'], [], '', false, false);
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectBackendConfiguration($this->configurationBuilder->buildDataBackendConfiguration());
        $dataBackend->_injectPagerCollection($pagerCollectionMock);
        $dataBackend->init();
        $baseWhereClause = $dataBackend->getBaseWhereClause();
        $this->assertEquals($baseWhereClause, $this->tsConfig['plugin']['tx_ptextlist']['settings']['listConfig']['list1']['backendConfig']['baseWhereClause']);
    }


    /** @test */
    public function getWhereClauseReturnsWhereClauseFromStringFilterReturnsExpectedValue()
    {
        $pagerCollectionMock = $this->getMock('PagerCollection', ['setItemCount'], [], '', false, false);
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectPagerCollection($pagerCollectionMock);
        $dataBackend->_injectQueryInterpreter(new MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());

        $filterMock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 'testValue', '='));

        $filterWhereClause = $dataBackend->getWhereClauseFromFilter($filterMock);
        $this->assertEquals("test = 'testValue'", $filterWhereClause);
    }


    /** @test */
    public function getWhereClauseFromFilterWithStringNumericValueReturnsExpectedValue()
    {
        $pagerCollectionMock = $this->getMock('PagerCollection', ['setItemCount'], [], '', false, false);
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectQueryInterpreter(new MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
        $dataBackend->_injectPagerCollection($pagerCollectionMock);

        $filterMock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', '10', '>'));

        $filterWhereClause = $dataBackend->getWhereClauseFromFilter($filterMock);
        $this->assertTrue($filterWhereClause == 'test > 10', 'Filter where clause was expected to be "test > 10" but was ' . $filterWhereClause);
    }


    /** @test */
    public function getWhereClauseFromFilterWithoutActiveFilterReturnsExpectedValue()
    {
        $pagerCollectionMock = $this->getMock('PagerCollection', ['setItemCount'], [], '', false, false);
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectQueryInterpreter(new MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
        $dataBackend->_injectPagerCollection($pagerCollectionMock);

        $filterMock = $this->getFilterMockByCriteria();

        $filterWhereClause = $dataBackend->getWhereClauseFromFilter($filterMock);
        $this->assertTrue($filterWhereClause == '', 'Filter where clause was expected to be "" but was ' . $filterWhereClause);
    }


    /** @test */
    public function getWhereClauseFromFilterboxReturnsExpectedString()
    {
        $pagerCollectionMock = $this->getMock('PagerCollection', ['setItemCount'], [], '', false, false);
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectQueryInterpreter(new MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
        $dataBackend->_injectPagerCollection($pagerCollectionMock);

        $filter1Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '>'));
        $filter2Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '<'));

        $filterBox = $this->getFilterboxByArrayOfFilters([$filter1Mock, $filter2Mock]);

        $whereClauseFromFilterbox = $dataBackend->getWhereClauseFromFilterbox($filterBox);
        $this->assertTrue($whereClauseFromFilterbox == '(test > 10) AND (test < 10)', 'Where clause from filterbox was expected to be "(test > 10) AND (test < 10)" but was ' . $whereClauseFromFilterbox);
    }


    /** @test */
    public function getWhereClauseFromFilterboxCollectionReturnsExpectedString()
    {
        $pagerCollectionMock = $this->getMock('PagerCollection', ['setItemCount'], [], '', false, false);
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectPagerCollection($pagerCollectionMock);
        $dataBackend->_injectQueryInterpreter(new MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());

        $filter1Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '>'));
        $filter2Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 10, '<'));
        $filter3Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 20, '>'));
        $filter4Mock = $this->getFilterMockByCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 20, '<'));

        $filterbox1 = $this->getFilterboxByArrayOfFilters([$filter1Mock, $filter2Mock]);
        $filterbox2 = $this->getFilterboxByArrayOfFilters([$filter3Mock, $filter4Mock]);

        $filterboxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection($this->configurationBuilder);
        $filterboxCollection->addItem($filterbox1);
        $filterboxCollection->addItem($filterbox2);

        $dataBackend->_injectfilterboxCollection($filterboxCollection);
        $whereClauseForFilterboxCollection = $dataBackend->getWhereClauseFromFilterboxes();
        $this->assertTrue($whereClauseForFilterboxCollection == '((test > 10) AND (test < 10)) AND ((test > 20) AND (test < 20))', 'Where clause for filterbox collection should have been "((test > 10) AND (test < 10)) AND ((test > 20) AND (test < 20))" but was ' . $whereClauseForFilterboxCollection);
    }


    /** @test */
    public function getLimitPartReturnsExpectedString()
    {
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectQueryInterpreter(new MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());

        $pagerCollectionMock = $this->getMock('PagerCollection', ['isEnabled', 'getCurrentPage', 'getItemsPerPage', 'getItemOffset'], [$this->configurationBuilder]);
        $pagerCollectionMock->expects($this->any())
            ->method('getCurrentPage')
            ->will($this->returnValue(10));
        $pagerCollectionMock->expects($this->any())
            ->method('getItemsPerPage')
            ->will($this->returnValue(10));
        $pagerCollectionMock->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValue(true));
        $pagerCollectionMock->expects($this->once())
            ->method('getItemOffset')
            ->will($this->returnValue(90));

        $dataBackend->_injectPagerCollection($pagerCollectionMock);

        $limitPart = $dataBackend->buildLimitPart();
        $this->assertTrue($limitPart == '90,10', 'Limit part of pager was expected to be 90,10 but was ' . $limitPart);
    }


    /** @test */
    public function buildSelectPartWithTableAndFieldReturnsExpectedString()
    {
        $pagerCollectionMock = $this->getMock('PagerCollection', ['setItemCount'], [], '', false, false);
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectPagerCollection($pagerCollectionMock);
        $dataBackend->_injectQueryInterpreter(new MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());

        $fieldConfigurationCollection = new FieldConfigCollection();
        $fieldConfigurationCollection->addItem($this->getFieldConfigMockForTableAndFieldAndIdentifier('table1', 'field1', 'test1'));
        $fieldConfigurationCollection->addItem($this->getFieldConfigMockForTableAndFieldAndIdentifier('table1', 'field2', 'test2'));
        $dataBackend->_injectFieldConfigurationCollection($fieldConfigurationCollection);

        $selectPartForFieldConfigurationCollection = $dataBackend->buildSelectPart();

        $expectedSelectPart = DbUtils::getAliasedSelectPartByFieldConfig($fieldConfigurationCollection[0]) . ', ' .
            DbUtils::getAliasedSelectPartByFieldConfig($fieldConfigurationCollection[1]);

        $this->assertTrue($selectPartForFieldConfigurationCollection == $expectedSelectPart,
            'Select part for field configuration collection should be "' . $expectedSelectPart . '" but was ' . $selectPartForFieldConfigurationCollection);
    }


    /** @test */
    public function buildSelectPartWithSpecialStringReturnsExpectedString()
    {
        $pagerCollectionMock = $this->getMock('PagerCollection', ['setItemCount'], [], '', false, false);
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectPagerCollection($pagerCollectionMock);
        $dataBackend->_injectQueryInterpreter(new MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());

        $fieldConfigurationCollection = new FieldConfigCollection();
        $fieldConfigurationCollection->addItem($this->getFieldConfigMockForSpecialAndIdentifier('specialString', 'testFieldIdentifier'));
        $dataBackend->_injectFieldConfigurationCollection($fieldConfigurationCollection);

        $selectPartForFieldConfigurationCollection = $dataBackend->buildSelectPart();

        $expectedSelectPart = '(specialString) AS testFieldIdentifier';

        $this->assertTrue($selectPartForFieldConfigurationCollection == $expectedSelectPart,
            'Select part for field configuration collection should be "' . $expectedSelectPart . '" but was ' . $selectPartForFieldConfigurationCollection);
    }


    /** @test */
    public function getListDataReturnsExpectedListData()
    {
        $dataSourceReturnArray = [
            ['t1.f1' => 'v1_1', 't1.f2' => 'v1_2', 't1.f3' => 'v1_3', 't2.f1' => 'v1_4', 't2.f2' => 'v1_5'],
            ['t1.f1' => 'v2_1', 't1.f2' => 'v2_2', 't1.f3' => 'v2_3', 't2.f1' => 'v2_4', 't2.f2' => 'v2_5'],
            ['t1.f1' => 'v3_1', 't1.f2' => 'v3_2', 't1.f3' => 'v3_3', 't2.f1' => 'v3_4', 't2.f2' => 'v3_5'],
            ['t1.f1' => 'v4_1', 't1.f2' => 'v4_2', 't1.f3' => 'v4_3', 't2.f1' => 'v4_4', 't2.f2' => 'v4_5'],
            ['t1.f1' => 'v5_1', 't1.f2' => 'v5_2', 't1.f3' => 'v5_3', 't2.f1' => 'v5_4', 't2.f2' => 'v5_5'],
            ['t1.f1' => 'v6_1', 't1.f2' => 'v6_2', 't1.f3' => 'v6_3', 't2.f1' => 'v6_4', 't2.f2' => 'v6_5'],
            ['t1.f1' => 'v7_1', 't1.f2' => 'v7_2', 't1.f3' => 'v7_3', 't2.f1' => 'v7_4', 't2.f2' => 'v7_5'],
            ['t1.f1' => 'v8_1', 't1.f2' => 'v8_2', 't1.f3' => 'v8_3', 't2.f1' => 'v8_4', 't2.f2' => 'v8_5'],
        ];

        $dataSourceMock = $this->getMock('DataSource_MySqlDataSource',
            ['executeQuery', 'fetchAll'],
            [new DataSource_DatabaseDataSourceConfiguration($this->configurationBuilder->buildDataBackendConfiguration()->getDataBackendSettings())]);

        $dataSourceMock->expects($this->once())
            ->method('executeQuery')
            ->will($this->returnValue($dataSourceMock));

        $dataSourceMock->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue($dataSourceReturnArray));

        $pagerCollectionMock = $this->getMock('PagerCollection', ['isEnabled', 'getCurrentPage', 'getItemsPerPage', 'getItemOffset'], [$this->configurationBuilder]);
        $pagerCollectionMock->expects($this->any())
            ->method('getCurrentPage')
            ->will($this->returnValue(10));
        $pagerCollectionMock->expects($this->any())
            ->method('getItemsPerPage')
            ->will($this->returnValue(10));
        $pagerCollectionMock->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValue(true));
        $pagerCollectionMock->expects($this->once())
            ->method('getItemOffset')
            ->will($this->returnValue(90));

        $sortingStateCollectionMock = $this->getMock('SortingStateCollection', ['getSortingsQuery'], [], '', false);
        $sortingStateCollectionMock->expects($this->any())->method('getSortingsQuery')->will($this->returnValue(new Tx_PtExtlist_Domain_QueryObject_Query()));
        $sorterMock = $this->getMock('Sorter', ['getSortingStateCollection'], [], '', false);
        $sorterMock->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue($sortingStateCollectionMock));

        $mapperMock = $this->getMock('Mapper_ArrayMapper', [], []);
        $mapperMock->expects($this->once())
            ->method('getMappedListData')
            ->will($this->returnValue($dataSourceReturnArray));

        $filterboxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', ['getExcludeFilters'], [], '', false);
        $filterboxCollectionMock->expects($this->any())->method('excludeFilters')->will($this->returnValue([]));

        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);
        $dataBackend->_injectBackendConfiguration($this->configurationBuilder->buildDataBackendConfiguration());
        $dataBackend->_injectQueryInterpreter(new MySqlDataBackend_MySqlInterpreter_MySqlInterpreter());
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
    public function listDataCacheWorksAsExpected()
    {
        /* @var $dataBackend MySqlDataBackend_MySqlDataBackend */
        $dataBackend = $this->getAccessibleMock('MySqlDataBackend_MySqlDataBackend', ['buildListData'], [$this->configurationBuilder]);
        $dataBackend->expects($this->once())
            ->method('buildListData');

        $dataBackend->getListData();
        $dataBackend->_set('listData', ['x' => 'y']);

        $dataBackend->getListData();
    }


    /** @test */
    public function resetListDataResetsListData()
    {
        $dataBackend = $this->getAccessibleMock('MySqlDataBackend_MySqlDataBackend', ['buildListData'], [$this->configurationBuilder]);
        $dataBackend->expects($this->exactly(2))
            ->method('buildListData');

        $dataBackend->getListData();
        $dataBackend->_set('listData', []);
        $dataBackend->_set('listQueryParts', []);

        $dataBackend->resetListDataCache();
        $this->assertEquals($dataBackend->_get('listQueryParts'), null);

        $dataBackend->getListData();
    }


    /** @test */
    public function getGroupDataReturnsExpectedData()
    {
        $dataBackend = new MySqlDataBackend_MySqlDataBackend($this->configurationBuilder);

        $fields = ['field1', 'field2', 'field3'];
        $excludeFilters = ['filterbox1.filter1', 'filterbox1.filter2', 'filterbox2.filter1'];
        $additionalQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
        $additionalQuery->addCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria::greaterThan('field1', 10));

        $returnArray = ['test'];

        $sortingStateCollectionMock = $this->getMock('SortingStateCollection', ['getSortingsQuery'], [], '', false);
        $sortingStateCollectionMock->expects($this->any())->method('getSortingsQuery')->will($this->returnValue(new Tx_PtExtlist_Domain_QueryObject_Query()));
        $sorterMock = $this->getMock('Sorter', ['getSortingStateCollection'], [], '', false);
        $sorterMock->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue($sortingStateCollectionMock));


        $queryInterpreterMock = $this->getMock('MySqlDataBackend_MySqlInterpreter_MySqlInterpreter', ['interpretQuery'], [], '', false);
        $dataSourceMock = $this->getMock('DataSource_MySqlDataSource', ['executeQuery', 'fetchAll'], [], '', false);

        $dataSourceMock->expects($this->once())
            ->method('executeQuery')
            ->will($this->returnValue($dataSourceMock));

        $dataSourceMock->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue($returnArray));


        $filterboxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', ['getExcludeFilters'], [], '', false);
        $filterboxCollectionMock->expects($this->any())->method('excludeFilters')->will($this->returnValue([]));

        $dataBackend->_injectBackendConfiguration($this->configurationBuilder->buildDataBackendConfiguration());
        $dataBackend->_injectPagerCollection($this->objectManager->get('PagerCollectionFactory')->getInstance($this->configurationBuilder));
        $dataBackend->_injectDataSource($dataSourceMock);
        $dataBackend->_injectQueryInterpreter($queryInterpreterMock);
        $dataBackend->_injectSorter($sorterMock);
        $dataBackend->_injectFilterboxCollection($filterboxCollectionMock);
        $dataBackend->init();

        $groupData = $dataBackend->getGroupData($additionalQuery, $excludeFilters);
        $this->assertEquals($returnArray, $groupData);
    }


    /** @test */
    public function convertTableFieldToAliasReturnsExpectedStrings()
    {
        $accessibleClassName = $this->buildAccessibleProxy('MySqlDataBackend_MySqlDataBackend');
        $dataBackend = new $accessibleClassName($this->configurationBuilder);
        $dataBackend->_injectFieldConfigurationCollection($this->configurationBuilder->buildFieldsConfiguration());

        $strings[] = ['test' => 'table1.field1 AS fieldIndentifier1', 'return' => 'fieldIndentifier1'];
        $strings[] = ['test' => '(someSepcial table1.field1) AS SpecialIdentifier', 'return' => '(someSepcial fieldIndentifier1) AS SpecialIdentifier'];
        $strings[] = ['test' => 'GROUP BY table1.field1', 'return' => 'GROUP BY fieldIndentifier1'];

        foreach ($strings as $string) {
            $returnString = $dataBackend->_call('convertTableFieldToAlias', $string['test']);
            $this->assertEquals($string['return'], $returnString, 'Mangled string is not as excepted : ' . $returnString . ' is not ' . $string['return']);
        }
    }


    /** @test */
    public function testBuildQuery()
    {
        $this->markTestIncomplete();
    }





    /** @test */
    public function testBuildWherePart()
    {
        $this->markTestIncomplete();
    }


    /** @test */
    public function testBuildOrderByPart()
    {
        $this->markTestIncomplete();
    }


    /** @test */
    public function testGetTotalItemsCount()
    {
        $this->markTestIncomplete();
    }


    /** @test */
    public function getGroupByPartReturnsExpectedString()
    {
        $dataBackend = $this->getDataBackend();
        $groupByPart = $dataBackend->buildGroupByPart();
        $this->assertEquals($groupByPart, 'company');
    }


    /** @test */
    public function getAggregatesByConfigCollectionReturnsExpectedString()
    {
        $configOverwrite['listConfig']['test']['aggregateData']['sumField1']['scope'] = 'query';
        $configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance(null, $configOverwrite);
        $aggConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactory::getInstance($configurationBuilderMock);
        $dataBackend = $this->getDataBackend($configurationBuilderMock);

        $this->assertEquals('SUM(field1) AS sumField1', $dataBackend->_call('buildAggregateFieldSQLByConfig', $aggConfigCollection->getAggregateConfigByIdentifier('sumField1')));
    }


    /** @test */
    public function getAggregatesByConfigCollectionWithUnsopportedMethodThrowsException()
    {
        $configOverwrite['listConfig']['test']['aggregateData']['sumField1'] = ['scope' => 'query', 'method' => 'aNotExistantMethod'];
        $configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance(null, $configOverwrite);
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
    public function getAggregatesByConfigCollectionWithSpecialStringReturnsExpectedString()
    {
        $configOverwrite['listConfig']['test']['aggregateData']['sumField1'] = ['scope' => 'query', 'special' => 'special'];
        $configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance(null, $configOverwrite);
        $aggConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactory::getInstance($configurationBuilderMock);
        $dataBackend = $this->getDataBackend($configurationBuilderMock);

        $this->assertEquals('special AS sumField1', $dataBackend->_call('buildAggregateFieldSQLByConfig', $aggConfigCollection->getAggregateConfigByIdentifier('sumField1')));
    }


    /**
     * @test
     */
    public function buildAggregateSqlByConfigCollectionReturnsExpectedSql()
    {
        $configOverwrite['listConfig']['test']['aggregateData']['sumField1'] = ['scope' => 'query', 'special' => 'special'];

        $configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance(null, $configOverwrite);
        $aggConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactory::getInstance($configurationBuilderMock);
        $dataBackend = $this->getDataBackend($configurationBuilderMock);

        $filterboxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', ['getExcludeFilters'], [], '', false);
        $filterboxCollectionMock->expects($this->any())->method('excludeFilters')->will($this->returnValue([]));

        $dataBackend->_injectFilterboxCollection($filterboxCollectionMock);

        $actual = $dataBackend->_call('buildAggregateSQLByConfigCollection', $aggConfigCollection);

        $expected = "SELECT special AS sumField1, AVG(field2) AS avgField2
 FROM (SELECT tableName1.fieldName1 AS field1, tableName2.fieldName2 AS field2, (special) AS field3, tableName4.fieldName4 AS field4
FROM companies
WHERE employees > 0
GROUP BY company
)  AS AGGREGATEQUERY";

        $expected = preg_replace('/[\n\r]/', ' ', $expected);
        $expected = trim(preg_replace('/\s\s+/', ' ', $expected));

        $actual = preg_replace('/[\n\r]/', ' ', $actual);
        $actual = trim(preg_replace('/\s\s+/', ' ', $actual));

        $this->assertEquals($expected, $actual);
    }


    /**
     * @test
     */
    public function getIterationListData()
    {
        $dataBackend = $this->getDataBackend($this->configurationBuilder);

        $filterBoxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', ['getExcludeFilters'], [], '', false);
        $filterBoxCollectionMock->expects($this->any())->method('excludeFilters')->will($this->returnValue([]));
        $dataBackend->_injectFilterboxCollection($filterBoxCollectionMock);

        $iterationListData = $dataBackend->getIterationListData();

        $this->assertInstanceOf('IterationListDataInterface', $iterationListData);
    }


    /**********************************************************************************************************************************************************
     * Helper methods
     **********************************************************************************************************************************************************/


    /**
     * @param null $configurationBuilderMock
     * @return MySqlDataBackend_MySqlDataBackend
     */
    protected function getDataBackend($configurationBuilderMock = null)
    {
        if (!is_a($configurationBuilderMock, 'Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock')) {
            $configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
        }

        $dataBackendAccessible = $this->buildAccessibleProxy('MySqlDataBackend_MySqlDataBackend');
        $dataBackend = new $dataBackendAccessible($configurationBuilderMock);
        /** @var $dataBackend  MySqlDataBackend_MySqlDataBackend */

        $queryInterpreterMock = $this->getMock('MySqlDataBackend_MySqlInterpreter_MySqlInterpreter', ['interpretQuery'], [], '', false);
        $dataSourceMock = $this->getMock('DataSource_MySqlDataSource', ['executeQuery'], [], '', false);

        $sortingStateCollectionMock = $this->getMock('SortingStateCollection', ['getSortingsQuery'], [], '', false);
        $sortingStateCollectionMock->expects($this->any())->method('getSortingsQuery')->will($this->returnValue(new Tx_PtExtlist_Domain_QueryObject_Query()));
        $sorterMock = $this->getMock('Sorter', ['getSortingStateCollection'], [], '', false);
        $sorterMock->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue($sortingStateCollectionMock));

        $dataMapperMock = new Mapper_ArrayMapper($configurationBuilderMock);

        $dataBackend->_injectBackendConfiguration($configurationBuilderMock->buildDataBackendConfiguration());
        $dataBackend->_injectDataSource($dataSourceMock);
        $dataBackend->_injectQueryInterpreter($queryInterpreterMock);
        $dataBackend->_injectFieldConfigurationCollection($configurationBuilderMock->buildFieldsConfiguration());
        $dataBackend->_injectPagerCollection($this->objectManager->get('PagerCollectionFactory')->getInstance($this->configurationBuilder));
        $dataBackend->_injectSorter($sorterMock);
        $dataBackend->_injectDataMapper($dataMapperMock);

        // TODO should we also mock this?!?
        $dataBackend->injectRendererChainFactory(\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('Tx_PtExtlist_Domain_Renderer_RendererChainFactory'));

        $dataBackend->init();

        return $dataBackend;
    }


    protected function getFieldConfigMockForTableAndFieldAndIdentifier($table, $field, $identifier)
    {
        $fieldConfigurationMock = $this->getMock('FieldConfig', [], [$this->configurationBuilder, $identifier, ['table' => $table, 'field' => $field]]);
        $fieldConfigurationMock->expects($this->any())
            ->method('getTable')
            ->will($this->returnValue($table));
        $fieldConfigurationMock->expects($this->any())
            ->method('getField')
            ->will($this->returnValue($field));
        return $fieldConfigurationMock;
    }


    protected function getFieldConfigMockForSpecialAndIdentifier($special, $identifier)
    {
        $fieldConfigurationMock = $this->getMock('FieldConfig', [], [$this->configurationBuilder, $identifier, ['special' => $special]]);
        $fieldConfigurationMock->expects($this->any())
            ->method('getSpecial')
            ->will($this->returnValue($special));
        $fieldConfigurationMock->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($identifier));
        return $fieldConfigurationMock;
    }


    protected function getFilterboxByArrayOfFilters($filtersArray)
    {
        $filterBoxConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilder, 'test', []);
        $filterBox = new Tx_PtExtlist_Domain_Model_Filter_Filterbox($filterBoxConfiguration);
        foreach ($filtersArray as $key => $filter) {
            $filterBox->addFilter($filter, $key);
        }
        return $filterBox;
    }


    protected function getFilterMockByCriteria($criteria = null)
    {
        $filterQuery = new Tx_PtExtlist_Domain_QueryObject_Query();

        if ($criteria != null) {
            $filterQuery->addCriteria($criteria);
        }

        $filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter', ['getFilterQuery']);
        $filterMock->expects($this->once())
            ->method('getFilterQuery')
            ->will($this->returnValue($filterQuery));
        return $filterMock;
    }


    protected function getHeaderColumnBySortingFieldAndDirection($field, $direction)
    {
        $sortingQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
        $sortingQuery->addSorting($field, $direction);
        $headerMock = $this->getMock('HeaderColumn', ['getSortingQuery']);
        $headerMock->expects($this->once())
            ->method('getSortingQuery')
            ->will($this->returnValue($sortingQuery));
        return $headerMock;
    }
}
