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
 * Testcase for field configuration
 *
 * @package Tests
 * @subpackage Domain\Configuration\Data\Fields
 * @author Daniel Lienert <linert@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Data_Fields_FieldConfig_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * Holds a dummy configuration for a field config object
	 * @var array
	 */
	protected $fieldSettings = array();
	
	
	/**
	 * Holds an instance of field configuration object
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	protected $fieldConfig = null; 
	
	
	
	public function setup() {
		$this->fieldSettings = array(
		    'table' => 'tableName',
		    'field' => 'fieldName',
		    'isSortable' => '0',
		    'accessGroups' => '1,2,3,4',
			'expandGroupRows' => 1
		);
		
		$this->initDefaultConfigurationBuilderMock();
		
		$this->fieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test1', $this->fieldSettings);
	}
	
	
	
	public function testSetup() {
		$fieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test1', $this->fieldSettings);
	}
	
	
	
	public function testGetIdentifier() {
		$this->assertEquals($this->fieldConfig->getIdentifier(),'test1');
	}
	
	
	public function testGetExpandGroupRows() {
		$this->assertEquals($this->fieldConfig->getExpandGroupRows(), true);
	}
	
	
	public function testGetTable() {
		$this->assertEquals($this->fieldConfig->getTable(), $this->fieldSettings['table']);
	}
	
	
	
	public function testGetField() {
		$this->assertEquals($this->fieldConfig->getField(), $this->fieldSettings['field']);
	}
	
	
	public function testGetTableFieldCombined() {
		$this->assertEquals($this->fieldConfig->getTableFieldCombined(), $this->fieldSettings['table'] . '.' . $this->fieldSettings['field']);
	}
	
	
	public function testGetIsSortable() {
		$this->assertTrue(!$this->fieldConfig->getIsSortable());
	}
	
	
	
	public function testGetSpecial() {
		$fieldSettings = array(
		    'special' => 'specialtestString',
		);
		$fieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test', $fieldSettings);
		$this->assertEquals($fieldConfig->getSpecial(), $fieldSettings['special']);
	}
	
	
	
	public function testDefaultGetIsSortable() {
		$newFieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test1', array('table' => '1', 'field' => '2'));
		$this->assertEquals($newFieldConfig->getIsSortable(), true);
	}
	
	
	
	public function testGetAccessGroups() {
		$accessArray = $this->fieldConfig->getAccessGroups();
		$this->assertTrue(in_array('1', $accessArray));
		$this->assertTrue(in_array('2', $accessArray));
		$this->assertTrue(in_array('3', $accessArray));
		$this->assertTrue(in_array('4', $accessArray));
	}
	
	
	
	public function testNoTableNameGivenException() {
		try {
			new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test', array('field' => '2'));
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
	
	
    public function testNoFieldNameGivenException() {
        try {
            new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test',array('table' => '2'));
        } catch(Exception $e) {
            return;
        }
        $this->fail();
    }	
}
?>