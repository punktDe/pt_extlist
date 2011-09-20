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
 * Testcase for field config collection
 *
 * @package Tests
 * @subpackage Domain\Configuration\Data\Fields
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Data_Fields_FieldConfigCollection_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * Holds a dummy configuration for a aggregate config collection object
	 * @var array
	 */
	protected $aggregateSettings = array();
	
	
	
	public function setup() {
		$this->aggregateSettings = array(
		    'field1' => array( 
		        'table' => 'tableName1',
		        'field' => 'fieldName1',
		        'isSortable' => '0',
		        'access' => '1,2,3,4'
		    ),
		    'field2' => array( 
                'table' => 'tableName2',
                'field' => 'fieldName2',
                'isSortable' => '0',
                'access' => '1,2,3,4'
            ),
            'field3' => array( 
                'table' => 'tableName3',
                'field' => 'fieldName3',
                'isSortable' => '0',
                'access' => '1,2,3,4'
            )
		);
		
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
	
	public function testSetup() {
		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
	}
	
	
	
	public function testExceptionOnGettingNonAddedItem() {
		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
        try {
            $fieldConfigCollection->getFieldConfigByIdentifier('test');
        } catch(Exception $e) {
            return;
        }
        $this->fail();
	}
	
	
	
	public function testAddGetCorrectItems() {
		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		$fieldConfigCollection->addFieldConfig(new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock, 'field1', $this->aggregateSettings['field1']));
		$fieldConfigCollection->addFieldConfig(new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock, 'field2', $this->aggregateSettings['field2']));
		$fieldConfig1 = $fieldConfigCollection->getFieldConfigByIdentifier('field1');
		$this->assertEquals($fieldConfig1->getIdentifier(), 'field1');
		$fieldConfig2 = $fieldConfigCollection->getFieldConfigByIdentifier('field2');
		$this->assertEquals($fieldConfig2->getIdentifier(), 'field2');
	}
	
	
	public function testExtractCollectionByIdentifierList() {
		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		$fieldConfigCollection->addFieldConfig(new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock, 'field1', $this->aggregateSettings['field1']));
		$fieldConfigCollection->addFieldConfig(new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock, 'field2', $this->aggregateSettings['field2']));
		$fieldConfigCollection->addFieldConfig(new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock, 'field3', $this->aggregateSettings['field3']));
		
		$extractFieldConfigCollection = $fieldConfigCollection->extractCollectionByIdentifierList(array('field1', 'field3'));
		$this->assertEquals(count($extractFieldConfigCollection), 2);
		$this->assertTrue(is_a($extractFieldConfigCollection->getFieldConfigByIdentifier('field1'), 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig'));
		$this->assertTrue(is_a($extractFieldConfigCollection->getFieldConfigByIdentifier('field3'), 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig'));
	}
	
	public function testExtractCollectionByIdentifierListExtractAll() {
		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		$fieldConfigCollection->addFieldConfig(new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock, 'field1', $this->aggregateSettings['field1']));
		$fieldConfigCollection->addFieldConfig(new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock, 'field2', $this->aggregateSettings['field2']));
		$fieldConfigCollection->addFieldConfig(new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock, 'field3', $this->aggregateSettings['field3']));
		
		
		$extractFieldConfigCollection = $fieldConfigCollection->extractCollectionByIdentifierList(array('*'));
		$this->assertEquals(count($extractFieldConfigCollection), 3);
		$this->assertTrue(is_a($extractFieldConfigCollection->getFieldConfigByIdentifier('field1'), 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig'));
		$this->assertTrue(is_a($extractFieldConfigCollection->getFieldConfigByIdentifier('field2'), 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig'));
		$this->assertTrue(is_a($extractFieldConfigCollection->getFieldConfigByIdentifier('field3'), 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig'));
	}
}
?>