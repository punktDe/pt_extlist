<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Testcase for  explicitSQlQuery class
 *
 * @package Tests
 * @subpackage Model/Filter/DataProvider
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_ExplicitSQLQueryTest extends Tx_PtExtlist_Tests_BaseTestcase {
    
	
	protected $defaultFilterSettings;
	
	
	
	public function setup() {


		$this->initDefaultConfigurationBuilderMock();

		$this->defaultFilterSettings = array(
			'filterIdentifier' => 'test',
			'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
			'partialPath' => 'Filter/SelectFilter',
			'fieldIdentifier' => 'field1',
			'displayFields' => 'field1,field2',
			'filterField' => 'field3',
			'invert' => '0',
			'optionsSqlQuery' => array('select' => 'username, email',
									 			'from' => 'be_users',
												'where' => 'deleted = 0',
												'orderBy' => 'username',
												'groupBy' => 'username',
												'limit' => 10
			)
		);
	}



	/**
	 * @test
	 */
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_DataProvider_ExplicitSQLQuery'));
	}


	/**
	 * @test
	 */
	public function optionsSqlQuerySettingsAreSetCorrectly() {
		$dataProvider = $this->buildAccessibleDataProvider();


		$this->assertEquals($dataProvider->_get('selectPart'), 'username, email');
		$this->assertEquals($dataProvider->_get('wherePart'), 'deleted = 0');
		$this->assertEquals($dataProvider->_get('fromPart'), 'be_users');
		$this->assertEquals($dataProvider->_get('orderByPart'), 'username');
		$this->assertEquals($dataProvider->_get('groupByPart'), 'username');
		$this->assertEquals($dataProvider->_get('limitPart'), 10);

		$this->assertEquals($dataProvider->_get('filterField'), 'field3');
		$this->assertEquals($dataProvider->_get('displayFields'), array('field1', 'field2'));
	}


	/**
	 * @return array
	 */
	public function faultySettingsDataProvider() {

		$defaultFilterSettings = array(
			'filterIdentifier' => 'test',
			'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
			'partialPath' => 'Filter/SelectFilter',
			'fieldIdentifier' => 'field1',
			'displayFields' => 'field1,field2',
			'filterField' => 'field3',
			'invert' => '0',
			'optionsSqlQuery' => array('select' => 'username, email',
									 			'from' => 'be_users',
												'where' => 'deleted = 0',
												'orderBy' => 'username',
												'groupBy' => 'username',
												'limit' => 10
			)
		);

		$faultySettings = array();
		$faultySettings['SelectPartIsMissing'] = array('faultySettings' => $defaultFilterSettings);
		unset($faultySettings['SelectPartIsMissing']['faultySettings']['optionsSqlQuery']['select']);

		$faultySettings['FromPartIsMissing'] = array('faultySettings' => $defaultFilterSettings);
		unset($faultySettings['FromPartIsMissing']['faultySettings']['optionsSqlQuery']['from']);

		return $faultySettings;
	}



	/**
	 * @test
	 * @dataProvider faultySettingsDataProvider
	 */
	public function throwExceptionOnMissingParameter($faultySettings) {
		try {
			$dataProvider = $this->buildAccessibleDataProvider($faultySettings);
		} catch(Exception $e) {
			return;
		}

		$this->fail('No Exception is thrown.');
	}


	/**
	 * @test
	 */
	public function getDataFromSqlServerReturnsData() {
		$dataProvider = $this->buildAccessibleDataProvider();
		$data = $dataProvider->_call('getDataFromSqlServer');
		$this->assertTrue(count($data) > 0);
	}


	/**
	 * Build the dataprovider
	 *
	 * @param array $filterSettings
	 * @return Tx_PtExtlist_Domain_Model_Filter_DataProvider_ExplicitSQLQuery
	 */
	protected function buildAccessibleDataProvider($filterSettings = NULL) {

		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DataProvider_ExplicitSQLQuery');
		$accesibleExplicitDataProvider = new $accessibleClassName;

		if(!$filterSettings) $filterSettings = $this->defaultFilterSettings;

    	$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $filterSettings,'test');

		$accesibleExplicitDataProvider->injectFilterConfig($filterConfiguration);
		$accesibleExplicitDataProvider->init();

		$dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilderMock);

		return $accesibleExplicitDataProvider;
   }
}
?>