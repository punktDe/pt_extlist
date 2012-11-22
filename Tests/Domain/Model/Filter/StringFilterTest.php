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
 * Testcase for String Filter class
 *
 * @package Tests
 * @subpackage Domain\Model\Filter
 * @author Michael Knoll
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_StringFilter_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}


	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_StringFilter'));
	}


	public function testGetFilterValueAfterSessionInjection() {
		$filter = $this->getStringFilterInstance();
		$filter->injectSessionData(array('filterValue' => 'sessionFilterValue'));
		$filter->init();
		$this->assertTrue($filter->getFilterValue() == 'sessionFilterValue');
	}


	public function testGetFilterValueAfterTsConfigInjection() {
		$filter = $this->getStringFilterInstance();
		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
			$this->configurationBuilderMock,
			array('filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter'), 'test'));
		$filter->init();
		$this->assertTrue($filter->getFilterValue() == 'defaultValue');
	}


	public function testGetFilterValueAfterFirstInjectingConfigThenInjectingSessionData() {
		$filter = $this->getStringFilterInstance();
		$filter->injectSessionData(array('filterValue' => 'sessionFilterValue'));
		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
			$this->configurationBuilderMock,
			array('filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter'), 'test'));
		$filter->init();
		$this->assertTrue($filter->getFilterValue() == 'sessionFilterValue');
	}


	public function testGetFilterValueAfterFirstInjectingConfigThenInjectingSessionDataThenInjectingGpVars() {
		$filter = $this->getStringFilterInstance();

		/* First test: GP vars holds value for filter --> gp var value should be returned */
		$filter->injectSessionData(array('filterValue' => 'sessionFilterValue'));
		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
			$this->configurationBuilderMock,
			array('filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter'), 'test'));
		$filter->injectGPVars(array('filterValue' => 'gpVarsValue'));
		$filter->init();
		$this->assertTrue($filter->getFilterValue() == 'gpVarsValue');

		/* Second test: GP vars holds no value for filter --> session data should be returned */
		$filter->injectGPVars(array());
		$filter->init();
		$this->assertTrue($filter->getFilterValue() == 'sessionFilterValue');
	}


	public function testPersistToSession() {
		$filter = $this->getStringFilterInstance();
		$filter->injectGPVars(array('filterValue' => 'persistedFilterValue'));
		$filter->init();
		$sessionValue = $filter->persistToSession();
		$this->assertTrue(array_key_exists('filterValue', $sessionValue));
		$this->assertTrue($sessionValue['filterValue'] == 'persistedFilterValue');
	}


	public function testSetAndGetFieldIdentifier() {
		$filter = $this->getStringFilterInstance();
		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
			$this->configurationBuilderMock,
			array('fieldIdentifier' => 'field1', 'filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter'), 'test'));
		$filter->init();

		$this->assertTrue(is_a($filter->getFieldIdentifier(), 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection'));
	}


	public function testThrowExceptionOnNonExistingFieldIdentifier() {
		$filter = $this->getStringFilterInstance();
		try {
			$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
				$this->configurationBuilderMock,
				array('fieldIdentifier' => 'field1', 'filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => '', 'partialPath' => 'Filter/StringFilter'), 'test'));

			$filter->init();
		} catch (Exception $e) {
			return;
		}
		$this->fail('No exception has been thrown on missing field description identifier!');

	}


	/**
	 * @test
	 */
	public function createQuery() {
		$filter = $this->getStringFilterInstance();
		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
			$this->configurationBuilderMock,
			array('fieldIdentifier' => 'field1', 'filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter'), 'test'));
		$filter->injectGPVars(array('filterValue' => 'testValue'));
		$filter->init();


		$query = $filter->getFilterQuery();

		$this->assertTrue(is_a($query, 'Tx_PtExtlist_Domain_QueryObject_Query'), 'Expected: Tx_PtExtlist_Domain_QueryObject_Query');
		$this->assertTrue($this->queryHasCriteria($query, new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('tableName1.fieldName1', '%testValue%', 'LIKE')));
	}


	/**
	 * @test
	 */
	public function filterIsValid() {
		$filter = $this->getStringFilterInstance();
		$this->assertTrue($filter->validate() == true);
	}


	public function testReset() {
		$filter = $this->getStringFilterInstance();
		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
			$this->configurationBuilderMock,
			array('fieldIdentifier' => 'field1', 'filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter'), 'test'));
		$filter->injectSessionData(array('filterValue' => 'sessionFilterValue'));
		$filter->injectGPVars(array('filterValue' => 'gpVarFilterValue'));
		$filter->init();
		$this->assertTrue($filter->getFilterValue() == 'gpVarFilterValue');
		$filter->reset();
		$this->assertTrue($filter->getFilterValue() == '');
	}


	public function testGetFilterBreadCrumb() {
		$filter = $this->getStringFilterInstance();
		$breadCrumb = $filter->getFilterBreadCrumb();
		$this->assertEquals($breadCrumb->getFilter(), $filter);
		$this->assertEquals($breadCrumb->getMessage(), null);
	}


	/**
	 * Checks whether a query has a criteria
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 * @param Tx_PtExtlist_Domain_QueryObject_Criteria $criteria
	 * @return bool True, if criteria is contained by query
	 */
	protected function queryHasCriteria(Tx_PtExtlist_Domain_QueryObject_Query $query, Tx_PtExtlist_Domain_QueryObject_Criteria $criteria) {
		$criterias = $query->getCriterias();
		foreach ($criterias as $queryCriteria) {
			/* @var $queryCriteria Tx_PtExtlist_Domain_QueryObject_Criteria */
			if ($criteria->isEqualTo($queryCriteria)) {
				return true;
			}
		}
		return false;
	}



	/**
	 * DataProvider
	 * @return array
	 */
	public function splitTokenDataProvider() {
		return array(
			'visibleCharacter' => array('config' => '&', 'expected' => '&'),
			'visibleCharacterEnclosed' => array('config' => '|&|', 'expected' => '&'),
			'nonVisibleCharacterEnclosed' => array('config' => '| |', 'expected' => ' '),
			'enclosureEnclosed' => array('config' => '|||', 'expected' => '|'),
		);
	}


	/**
	 * @test
	 * @dataProvider splitTokenDataProvider
	 *
	 * @param $config
	 * @param $expected
	 */
	public function splitTokensAreSetByInitFilterByTsConfig($config, $expected) {
		$additionalSettings = array('andToken' => $config, 'orToken' => $config);

		$filter = $this->getStringFilterInstance($additionalSettings);
		$filter->_call('initFilterByTsConfig');

		$this->assertEquals($expected, $filter->_get('andToken'));
		$this->assertEquals($expected, $filter->_get('orToken'));
	}


	/**
	 * Data provider for filter values
	 * @return array
	 */
	public function filterValueDataProvider() {
		return array(
			'simpleValueNoToken' => 				array('value' => 'test', 			'andToken' => '', 		'orToken' => '', 	'expected' => array('test'), 			'expectedSQL' => 'table1.field1 LIKE "%test%"'),
			'2ValuesNoToken' => 					array('value' => 'test test2', 		'andToken' => '', 		'orToken' => '', 	'expected' => array('test test2'), 		'expectedSQL' => 'table1.field1 LIKE "%test test2%"'),
			'2ValuesNoTokenButAndTokenGiven' => 	array('value' => 'test test2', 		'andToken' => 'and', 	'orToken' => '', 	'expected' => array(array('test test2')), 		'expectedSQL' => 'table1.field1 LIKE "%test test2%"'),
			'2ValuesNoTokenButOrTokenGiven' => 		array('value' => 'test test2', 		'andToken' => '', 		'orToken' => 'or', 	'expected' => array(array('test test2')), 		'expectedSQL' => 'table1.field1 LIKE "%test test2%"'),
			'2ValuesNoTokenButBothTokenGiven' => 	array('value' => 'test test2', 		'andToken' => 'and', 	'orToken' => 'or', 	'expected' => array(array('test test2')), 		'expectedSQL' => 'table1.field1 LIKE "%test test2%"'),
			'2ValuesOrConcatenated' => 				array('value' => 'test or test2', 	'andToken' => 'and', 	'orToken' => 'or', 	'expected' => array(array('test'), array('test2')), 	'expectedSQL' => '(table1.field1 LIKE "%test%") OR (table1.field1 LIKE "%test2%")'),
			'2ValuesAndConcatenated' => 			array('value' => 'test and test2', 	'andToken' => 'and', 	'orToken' => 'or', 	'expected' => array(array('test', 'test2')), 'expectedSQL' => '(table1.field1 LIKE "%test%") AND (table1.field1 LIKE "%test2%")'),
			'3ValuesOrAndConcatenated' => 			array('value' => 'test or test2 and test3', 'andToken' => 'and', 'orToken' => 'or', 'expected' => array(array('test'), array('test2', 'test3')), 'expectedSQL' => '(table1.field1 LIKE "%test%") OR ((table1.field1 LIKE "%test2%") AND (table1.field1 LIKE "%test3%"))'),
			'3ValuesAndOrConcatenated' => 			array('value' => 'test and test2 or test3', 'andToken' => 'and', 'orToken' => 'or', 'expected' => array(array('test', 'test2'), array('test3')), 'expectedSQL' => '((table1.field1 LIKE "%test%") AND (table1.field1 LIKE "%test2%")) OR (table1.field1 LIKE "%test3%")'),
			'4ValuesAndOrConcatenated' => 			array('value' => 'test or test2 and test3 or test4', 'andToken' => 'and', 'orToken' => 'or', 'expected' => array(array('test'), array('test2', 'test3'), array('test4')), 'expectedSQL' => '((table1.field1 LIKE "%test%") OR ((table1.field1 LIKE "%test2%") AND (table1.field1 LIKE "%test3%"))) OR (table1.field1 LIKE "%test4%")'),
			'4ValuesOrAndConcatenated' => 			array('value' => 'test and test2 or test3 and test4', 'andToken' => 'and', 'orToken' => 'or', 'expected' => array(array('test','test2'), array('test3', 'test4')), 'expectedSQL' => '((table1.field1 LIKE "%test%") AND (table1.field1 LIKE "%test2%")) OR ((table1.field1 LIKE "%test3%") AND (table1.field1 LIKE "%test4%"))'),

			# Space as OR token
			'2ValuesSpaceOrToken' => 				array('value' => 'test test2', 	'andToken' => 'and', 	'orToken' => ' ', 	'expected' => array(array('test'), array('test2')), 	'expectedSQL' => '(table1.field1 LIKE "%test%") OR (table1.field1 LIKE "%test2%")'),
			'3ValuesAndSpaceOrToken' => 			array('value' => 'test & test2 test3', 'andToken' => '&', 'orToken' => ' ', 'expected' => array(array('test', 'test2'), array('test3')), 'expectedSQL' => '((table1.field1 LIKE "%test%") AND (table1.field1 LIKE "%test2%")) OR (table1.field1 LIKE "%test3%")'),
		);
	}


	/**
	 * @test
	 * @dataProvider filterValueDataProvider
	 *
	 * @param $value
	 * @param $andToken
	 * @param $orToken
	 * @param $expected
	 * @param $expectedSQL
	 */
	public function prepareFilterValue($value, $andToken, $orToken, $expected, $expectedSQL = '') {
		$additionalSettings = array('andToken' => $andToken, 'orToken' => $orToken);

		$filter = $this->getStringFilterInstance($additionalSettings);
		$filter->_call('initFilterByTsConfig');

		$actual = $filter->_call('prepareFilterValue', $value);
		$this->assertEquals($expected, $actual);
	}



	/**
	 * @test
	 * @dataProvider filterValueDataProvider
	 *
	 * @param $value
	 * @param $andToken
	 * @param $orToken
	 * @param $expected
	 * @param string $expectedSQL
	 */
	public function buildFilterCriteria($value, $andToken, $orToken, $expected, $expectedSQL = '') {

		$additionalSettings = array('andToken' => $andToken, 'orToken' => $orToken);

		$filter = $this->getStringFilterInstance($additionalSettings);
		$filter->_set('filterValue', $value);
		$filter->_call('initFilterByTsConfig');

		$fieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock, 'testField', array('table' => 'table1', 'field' => 'field1'));

		$actualCriteria = $filter->_call('buildFilterCriteria', $fieldConfig);

		$this->assertInstanceOf('Tx_PtExtlist_Domain_QueryObject_Criteria', $actualCriteria);

		$query = new Tx_PtExtlist_Domain_QueryObject_Query();
		$query->addCriteria($actualCriteria);

		$actualSQL = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter::getCriterias($query);

		$this->assertEquals($expectedSQL, $actualSQL);
	}



	/**
	 * @test
	 */
	public function buildFilterCriteriaForSingleValue() {

		$filter = $this->getStringFilterInstance();
		$fieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock, 'testField', array('table' => 'table1', 'field' => 'field1'));

		$criteria = $filter->_call('buildFilterCriteriaForSingleValue','testValue', $fieldConfig);


		$this->assertInstanceOf('Tx_PtExtlist_Domain_QueryObject_SimpleCriteria', $criteria);
	}





	/**
	 * Returns an instance of a string filter
	 *
	 * @var array $additionalSettings
	 * @return Tx_PtExtlist_Domain_Model_Filter_StringFilter
	 */
	protected function getStringFilterInstance($additionalSettings = array()) {

		$settings = array('filterIdentifier' => 'stringFilter1', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter');
		$settings = t3lib_div::array_merge_recursive_overrule($settings, $additionalSettings);

		$accessibleFilterClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_StringFilter');
		$filter	= new $accessibleFilterClass(); /** @var Tx_PtExtlist_Domain_Model_Filter_StringFilter $filter */

		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
			$this->configurationBuilderMock,
			$settings, 'test'));

		$fieldConfigMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig', array('getTable', 'getField'), array($this->configurationBuilderMock, 'testfield', array('field' => 'testfield', 'table' => 'testtable')));
		$fieldConfigMock->expects($this->any())
			->method('getTable')
			->will($this->returnValue('testtable'));
		$fieldConfigMock->expects($this->any())
			->method('getField')
			->will($this->returnValue('testfield'));

		$fieldConfigCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection', array('getFieldConfigByIdentifier'));
		$fieldConfigCollectionMock->expects($this->any())
			->method('getFieldConfigByIdentifier')
			->will($this->returnValue($fieldConfigMock));

		$dataBackendMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend', array('getFieldConfigurationCollection'), array($this->configurationBuilderMock));
		$dataBackendMock->expects($this->any())
			->method('getFieldConfigurationCollection')
			->will($this->returnValue($fieldConfigCollectionMock));

		$filter->injectDataBackend($dataBackendMock);

		return $filter;
	}

}

?>