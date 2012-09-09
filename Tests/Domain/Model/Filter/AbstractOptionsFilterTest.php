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

require_once t3lib_extMgm::extPath('pt_extlist').'Tests/Domain/Model/Filter/Fixtures/TestDataProvider.php';

/**
 * Testcase for abstract groupDataFilter class
 *
 * @package Tests
 * @subpackage Domain\Model\Filter
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_AbstractOptionsFilterTest extends Tx_PtExtlist_Tests_BaseTestcase {

    protected $defaultFilterSettings;



    public function setup() {
        $this->initDefaultConfigurationBuilderMock();
    }



	/** @test */
    public function buildFilterQueryBuildsExpectedCriteriasForSingleFilterValue() {

        $selectFilter = $this->buildAccessibleAbstractGroupDataFilter();
        $selectFilter->_set('filterValues', array('filterValue' => 'filterValue'));
		$selectFilter->_set('isActive', TRUE);
        $selectFilter->_call('buildFilterQuery');

        $filterQuery = $selectFilter->_get('filterQuery');
        $criterias = $filterQuery->getCriterias();

        $this->assertEquals(count($criterias), 1);
        $this->assertTrue(is_a($criterias[0], 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
        $this->assertEquals($criterias[0]->getField(), 'tableName1.fieldName1');
        $this->assertEquals($criterias[0]->getValue(), 'filterValue');
        $this->assertEquals($criterias[0]->getOperator(), '=');
    }



	/** @test */
    public function buildFilterQueryBuildsExpectedCriteriasForMultipleFilterValue() {

        $selectFilter = $this->buildAccessibleAbstractGroupDataFilter();
        $selectFilter->_set('filterValues', array('filterValue1', 'filterValue2'));
		$selectFilter->_set('isActive', TRUE);
        $selectFilter->_call('buildFilterQuery');

        $filterQuery = $selectFilter->_get('filterQuery');
        $criterias = $filterQuery->getCriterias();

        $this->assertEquals(count($criterias), 1);
        $this->assertTrue(is_a($criterias[0], 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
        $this->assertEquals($criterias[0]->getField(), 'tableName1.fieldName1');
        $this->assertEquals($criterias[0]->getValue(), array('filterValue1', 'filterValue2'));
        $this->assertEquals($criterias[0]->getOperator(), 'IN');
    }



	/** @test */
    public function buildFilterQueryBuildsExpectedCriteriasForSingleFilterValueWhenInverted() {

        $filterSettings = array(
            'filterIdentifier' => 'field1',
            'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
            'partialPath' => 'Filter/SelectFilter',
            'fieldIdentifier' => 'field1',
            'displayFields' => 'field1,field2',
            'filterField' => 'field1',
            'invert' => '1'
        );

        $selectFilter = $this->buildAccessibleAbstractGroupDataFilter($filterSettings);
        $selectFilter->_set('filterValues', array('filterValue' => 'filterValue'));
		$selectFilter->_set('isActive', TRUE);
        $selectFilter->_call('buildFilterQuery');

        $filterQuery = $selectFilter->_get('filterQuery');
        $criterias = $filterQuery->getCriterias();

        $this->assertEquals(count($criterias), 1);
        $this->assertTrue(is_a($criterias[0], 'Tx_PtExtlist_Domain_QueryObject_NotCriteria'));
    }



    public function testInit() {
        $this->markTestIncomplete();
    }



    public function testReset() {
        $this->markTestIncomplete();
    }



    public function testPersistToSession() {
        $this->markTestIncomplete();
    }



    public function testAddInactiveOption() {

        $settings = array(
            'filterIdentifier' => 'test',
            'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
            'partialPath' => 'Filter/SelectFilter',
            'fieldIdentifier' => 'field1',
            'displayFields' => 'field1,field2',
            'filterField' => 'field3',
            'invert' => '0',
            'inactiveOption' => 'all',
        );

        $abstractGroupDataFilter = $this->buildAccessibleAbstractGroupDataFilter($settings);
        $options = array();
        $abstractGroupDataFilter->_callRef('addInactiveOption', $options);

        $this->assertEquals($options['']['value'], 'all');
        $this->assertEquals($options['']['selected'], true, 'Selected must be true cause no filterValues are set');

        $options = array();
        $abstractGroupDataFilter->_set('filterValues', array('x' => 'x'));
        $abstractGroupDataFilter->_callRef('addInactiveOption', $options);

        $this->assertEquals($options['']['value'], 'all');
        $this->assertEquals($options['']['selected'], false, 'Selected must be false cause we have a filterValue');
    }



    public function testGetValues() {
        $this->markTestIncomplete();
    }



    public function testInitOnCorrectConfiguration() {
        $selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
            $this->configurationBuilderMock,
            array(
                'filterIdentifier' => 'field1',
                'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
                'partialPath' => 'Filter/SelectFilter',
                'fieldIdentifier' => 'field1',
                'displayFields' => 'field1,field2',
                'filterField' => 'field1'
            ), 'test');
        $sessionManagerMock = $this->getMock('Tx_PtExtbase_State_Session_SessionPersistenceManager', array(), array(), '', FALSE);

        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->_injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());

        $selectFilter->injectFilterConfig($filterConfiguration);
        $selectFilter->injectDataBackend($dataBackendMock);
        $selectFilter->init();
    }



    public function testExceptionsOnMissingFieldIdentifierConfiguration() {
        $selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();

        try {
            $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
                $this->configurationBuilderMock,
                array(
                    'filterIdentifier' => 'field1',
                    'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
                    'partialPath' => 'Filter/SelectFilter',
                    'optionsSourceField' => 'field1,field2',
                ), 'test');

            $selectFilter->init();
        } catch (Exception $e) {
            return;
        }
        $this->fail('No error has been thrown on configuration without fieldIdentifier');
    }



    public function testOnMissingFilterFieldConfiguration() {
        $selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
            $this->configurationBuilderMock,
            array(
                'filterIdentifier' => 'field1',
                'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
                'partialPath' => 'Filter/SelectFilter',
                'fieldIdentifier' => 'field1',
                'displayField' => 'field2'
            ), 'test');
        $sessionManagerMock = $this->getMock('Tx_PtExtbase_State_Session_SessionPersistenceManager', array(), array(), '', FALSE);

        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->_injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());
        $selectFilter->injectDataBackend($dataBackendMock);

        $selectFilter->injectFilterConfig($filterConfiguration);
    }



    public function testSetDefaultValuesFromTSConfigSingle() {
        $testFilter = $this->buildAccessibleAbstractGroupDataFilter();
        $defaultValue = 'test';
        $testFilter->_call('setDefaultValuesFromTSConfig', $defaultValue);
        $this->assertEquals(array('test' => 'test'), $testFilter->_get('filterValues'));
    }



    public function testSetDefaultValuesFromTSConfigMultiple() {
        $testFilter = $this->buildAccessibleAbstractGroupDataFilter();
        $defaultValue = array(10 => 'test', 20 => 'test2');
        $testFilter->_call('setDefaultValuesFromTSConfig', $defaultValue);
        $this->assertEquals(array('test' => 'test', 'test2' => 'test2'), $testFilter->_get('filterValues'));
    }



    /**
     * @test
     */
    public function getOptions() {
        $abstractOptionsFilter = $this->buildAcccessibleAbstractOptionsFilterWithTestDataProvider();
        $options = $abstractOptionsFilter->getOptions();

        $this->assertEquals(array('value' => 'value1', 'selected' => FALSE), $options['key1']);
    }



    /**
     * @return array
     */
    public function displayValueDataProvider() {
        return array(
            'none' => array('values' => array(), 'selectedValues' => array(), 'expected' => ''),
            'one' => array('values' => array('key' => 'test', 'otherKey' => 'test2'), 'selectedValues' => array('key'), 'expected' => 'test'),
            'multiple' => array('values' => array('key1' => 'val1', 'key2' => 'val2', 'key3' => 'val3'),  'selectedValues' => array('key1', 'key2'), 'expected' => 'val1, val2'),
        );
    }



    /**
     * @test
     * @dataProvider displayValueDataProvider
     * @param $values
     * @param $selectedValues
     * @param $expected
     */
    public function displayValue($values, $selectedValues, $expected) {
        $abstractOptionsFilter = $this->buildAcccessibleAbstractOptionsFilterWithTestDataProvider($values, $selectedValues);

        $abstractOptionsFilter->_set('filterValues', $selectedValues);
        $this->assertEquals($expected, $abstractOptionsFilter->getDisplayValue());
    }



	//**************************************************************
	//* Utility Methods
	//**************************************************************/

    /**
     * @param null $testData
     * @return Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter
     */
    protected function buildAcccessibleAbstractOptionsFilterWithTestDataProvider($testData = NULL) {

        if(!$testData) {
            $testData = array('key1' => 'value1', 'key2' => 'value2');
        }

        $accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter');

        $abstractOptionsFilter = $this->getMockBuilder($accessibleClassName)
            ->setMethods(array('buildDataProvider'))
            ->getMock();

        $allFilterSettings = $this->configurationBuilderMock->getSettings('filters');
        $filterSettings = $allFilterSettings['testfilterbox']['filterConfigs']['10'];

        $abstractOptionsFilter->_set('filterConfig', new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
            $this->configurationBuilderMock,
            $filterSettings, 'test'));

        $dataProviderFixture = new Tx_PtExtlist_Tests_Domain_Model_Filter_Fixture_TestDataProvider();
        $dataProviderFixture->setOptions($testData);

        $abstractOptionsFilter->expects($this->once())->method('buildDataProvider')->will($this->returnValue($dataProviderFixture));

        return $abstractOptionsFilter;
    }



    public function buildAccessibleAbstractGroupDataFilter($filterSettings = NULL) {
        $this->defaultFilterSettings = $filterSettings;
        if (!$this->defaultFilterSettings) {
            $this->defaultFilterSettings = array(
                'filterIdentifier' => 'test',
                'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
                'partialPath' => 'Filter/SelectFilter',
                'fieldIdentifier' => 'field1',
                'displayFields' => 'field1,field2',
                'filterField' => 'field3',
                'invert' => '0'
            );
        }

        $accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter');
        $abstractOptionsFilter = $this->getMockForAbstractClass($accessibleClassName);

        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $this->defaultFilterSettings, 'test');

        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->_injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());

        $abstractOptionsFilter->injectFilterConfig($filterConfiguration);
        $abstractOptionsFilter->injectDataBackend($dataBackendMock);
        $abstractOptionsFilter->init();

        return $abstractOptionsFilter;
    }

}
?>