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

class Tx_PtExtlist_Tests_Domain_Model_Filter_MinFilterTest extends Tx_PtExtlist_Tests_BaseTestcase {

	public function setup(){
		$this->initDefaultConfigurationBuilderMock();
	}
	
	public function testSetup() {
		$filter = new Tx_PtExtlist_Domain_Model_Filter_MinFilter();
		$this->assertTrue(is_a($filter,'Tx_PtExtlist_Domain_Model_Filter_FilterInterface'));
	}
	
	
	public function testValidationOnInactiveState() {
		$filterMock = $this->getFilterMock(0,0,0,true,false);
		$this->assertTrue((bool)$filterMock->validate());
	}
	public function testValidationMaxEquals() {
		
		$filterMock = $this->getFilterMock(10);
		$this->assertTrue((bool)$filterMock->validate());
	}
	public function testValidationMaxSmaller() {
		
		$filterMock = $this->getFilterMock(5);
		$this->assertTrue((bool)$filterMock->validate());
	}
	public function testValidationMaxBigger() {
		
		$filterMock = $this->getFilterMock(11);
		$this->assertFalse((bool)$filterMock->validate());
	}
	
	public function testValidationMinEquals() {
		
		$filterMock = $this->getFilterMock(1);
		$this->assertTrue((bool)$filterMock->validate());
	}
	public function testValidationMinBigger() {
		
		$filterMock = $this->getFilterMock(3);
		$this->assertTrue((bool)$filterMock->validate());
	}
	public function testValidationMinSmaller() {
		
		$filterMock = $this->getFilterMock(-1);
		$this->assertFalse((bool)$filterMock->validate());
	}
	
	public function testCriteria() {
		$filterMock = $this->getFilterMock(5,10,1,false);
		
		$fieldConfig = $this->buildFieldCollection('field1')->getFieldConfigByIdentifier('field1');
		
		$criteria = $filterMock->_callRef('buildFilterCriteria',$fieldConfig);
		
		$this->assertTrue(is_a($criteria,'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
		
		$this->assertEquals('>=',$criteria->getOperator());
		$this->assertEquals('5',$criteria->getValue());
	}
	
	
	protected function getFilterMock($filterValue, $max=10, $min=1, $injectConfigMock = true, $active = true) {
		if($injectConfigMock) {
			
			$configMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig', 
				array('getSettings'), array(),'',FALSE);			
				
			$settings = array('maxValue'=>$max, 'minValue'=>$min);
			$configMock
				->expects($this->any())
				->method('getSettings')
				->with('validation')
				->will($this->returnValue($settings));
		}
			
		$fieldMock = $this->getAccessibleMock('Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig',
			array('getTableFieldCombined'), array(),'',FALSE);
		$fieldMock->expects($this->any())
			->method('getTableFieldCombined')
			->will($this->returnValue('foo'));
	
		$filterMock = $this->getAccessibleMock('Tx_PtExtlist_Domain_Model_Filter_MinFilter', 
			array('dummyMethod'), array(),'',FALSE);
			
		$filterMock->_set('listIdentifier','test');
		if($injectConfigMock) $filterMock->_set('filterConfig', $configMock);
		$filterMock->_set('isActive', $active);
		$filterMock->_set('filterValue',$filterValue);
		$filterMock->_set('fieldIdentifierCollection', $this->buildFieldCollection('field1'));
		
		return $filterMock;
	}
	
	protected function buildFieldCollection($fields) {
		$fieldIdentifierList = t3lib_div::trimExplode(',', $fields);
		return  $this->configurationBuilderMock->buildFieldsConfiguration()->extractCollectionByIdentifierList($fieldIdentifierList);
	}
	
}

?>