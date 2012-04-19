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
 * Test for abstractTimeSpanFilter
 * 
 * @author Daniel Lienert
 * @package Test
 * @subpackage Domain\Model\Filter
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_AbstractTimeSpanFilterTest extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $defaultFilterSettings;



	public function setup() {
		$this->initDefaultConfigurationBuilderMock();

		$this->defaultFilterSettings = array(
			'filterIdentifier' => 'test',
			'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_TimeSpanFilter',
			'partialPath' => 'Filter/TimeSpan',
			'dbTimeFormat' => 'd.m.Y H:i',
			'fieldIdentifier' => array(
				10 => array(
					'start' => 'field1',
					'end' => 'field2'
				)
			),
		);
	}



	/**
	 * @test
	 */
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_AbstractTimeSpanFilter'));
	}
	


	/**
	 * @test
	 */
	public function buildDateFieldIdentifierArrayWithRawFieldIdentifiers() {

		$timeSpanFilterMock = $this->buildAccessibleAbstractTimeSpanFilter(); /** @var $timeSpanFilterMock Tx_PtExtlist_Domain_Model_Filter_AbstractTimeSpanFilter  */
		$fieldIdentifierArray = $timeSpanFilterMock->getDateFieldsConfigs();

		$testField1 = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'field1', array('field' => 'fieldName1', 'table' => 'tableName1'));
		$testField2 = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'field2', array('field' => 'fieldName2', 'table' => 'tableName2'));

		$this->assertTrue(is_a($fieldIdentifierArray[10]['start'], 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig'));
		$this->assertTrue(is_a($fieldIdentifierArray[10]['end'], 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig'));
		$this->assertEquals($testField1->getIdentifier(), $fieldIdentifierArray[10]['start']->getIdentifier());
		$this->assertEquals($testField2->getIdentifier(), $fieldIdentifierArray[10]['end']->getIdentifier());
	}


	/**
	 * @test
	 */
	public function buildDateFieldIdentifierArrayWithFieldIdentifierCollection() {

		$filterSettings = $this->defaultFilterSettings;
		$filterSettings['fieldIdentifier'] = 'field1';

		$timeSpanFilterMock = $this->buildAccessibleAbstractTimeSpanFilter($filterSettings); /** @var $timeSpanFilterMock Tx_PtExtlist_Domain_Model_Filter_AbstractTimeSpanFilter  */
		$fieldIdentifierArray = $timeSpanFilterMock->getDateFieldsConfigs();

		$testField = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'field1', array('field' => 'fieldName1', 'table' => 'tableName1'));

		$this->assertTrue(is_a($fieldIdentifierArray[0]['start'], 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig'));
		$this->assertTrue(is_a($fieldIdentifierArray[0]['end'], 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig'));
		$this->assertEquals($testField->getIdentifier(), $fieldIdentifierArray[0]['start']->getIdentifier());
		$this->assertEquals($testField->getIdentifier(), $fieldIdentifierArray[0]['end']->getIdentifier());
	}


	public function testDBTimeFormatIsSet() {
		$filterMock = $this->buildAccessibleAbstractTimeSpanFilter();
		$this->assertEquals('d.m.Y H:i', $filterMock->_get('dbTimeFormat'));
	}


	/**
	 * Getter for FROM filter value
	 *
	 * @test
	 */
	public function getFilterValueStart() {
		$filterMock = $this->buildAccessibleAbstractTimeSpanFilter();
		$this->assertEquals($filterMock->getFilterValueStart(), date_create('2000-01-01'));
	}



	/**
	 * Getter for filter value
	 *
	 * @test
	 */
	public function getFilterValueEnd() {
		$filterMock = $this->buildAccessibleAbstractTimeSpanFilter();
		$this->assertEquals($filterMock->getFilterValueEnd(), date_create());
	}



	/**
	 * @test
	 */
	public function getFilterValueStartInDBFormat() {
		$filterMock = $this->buildAccessibleAbstractTimeSpanFilter();
		$this->assertEquals($filterMock->getFilterValueStartInDBFormat(), date_create('2000-01-01')->format('d.m.Y H:i'));
	}



	/**
	 * @test
	 */
	public function getFilterValueEndInDBFormat() {
		$filterMock = $this->buildAccessibleAbstractTimeSpanFilter();
		$this->assertEquals($filterMock->getFilterValueEndInDBFormat(), date_create()->format('d.m.Y H:i'));
	}



	/**
	 * Utility Methods
	 *
	 * @return Tx_PtExtlist_Domain_Model_Filter_AbstractTimeSpanFilter
	 */
	public function buildAccessibleAbstractTimeSpanFilter($filterSettings = NULL) {

		if(!$filterSettings) $filterSettings = $this->defaultFilterSettings;

		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_AbstractTimeSpanFilter');
		$abstractTimeSpanFilter = $this->getMockForAbstractClass($accessibleClassName);

		$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $filterSettings, 'test');

		$dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
		$dataBackendMock->_injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());

		$abstractTimeSpanFilter->injectFilterConfig($filterConfiguration);
		$abstractTimeSpanFilter->injectDataBackend($dataBackendMock);

		$abstractTimeSpanFilter->_set('filterValueStart', date_create('2000-01-01'));
		$abstractTimeSpanFilter->_set('filterValueEnd', date_create());
		
		$abstractTimeSpanFilter->init();

		return $abstractTimeSpanFilter;
	}

}