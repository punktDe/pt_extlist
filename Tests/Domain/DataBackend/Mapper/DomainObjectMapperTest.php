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
 * Testcase for mapper mapping domain object data on list data
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_Mapper_DomainObjectMapper_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_Mapper_DomainObjectMapper'));
    }
    
    
    
    public function testGetMappedListData() {
    	$domainObjectMapper = new Tx_PtExtlist_Domain_DataBackend_Mapper_DomainObjectMapper();
    	$domainObjectMapper->setMapperConfiguration($this->createMapperConfiguration());
    	$mappedListData = $domainObjectMapper->getMappedListData($this->createMappingTestData()); 
    	$this->assertEquals(count($mappedListData), 4);
    	$this->assertEquals(get_class($mappedListData[0]), 'Tx_PtExtlist_Domain_Model_List_Row');
    	$this->assertEquals(count($mappedListData[0]), 2);
    	$row = $mappedListData[0]; /* @var $row Tx_PtExtlist_Domain_Model_List_Row */
    	$this->assertEquals(get_class($row['field1']), 'Tx_PtExtlist_Domain_Model_List_Cell');
    	$this->assertEquals($row['field1']->getValue(), 'group 1');
    }
    
    
    
    public function testThrowExceptionOnNonExistingMappingConfiguration() {
    	$domainObjectMapper = new Tx_PtExtlist_Domain_DataBackend_Mapper_DomainObjectMapper();
    	try {
    		$domainObjectMapper->getMappedListData($this->createMappingTestData());
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No Exception has been thrown on non-existing mapping configuration');
    }
    
    
    
    protected function createMappingTestData() {
    	$objectCollection = new Tx_Extbase_Persistence_ObjectStorage();
    	$feGroup1 = new Tx_Extbase_Domain_Model_FrontendUserGroup('group 1');
    	$feGroup2 = new Tx_Extbase_Domain_Model_FrontendUserGroup('group 2');
    	$feGroup3 = new Tx_Extbase_Domain_Model_FrontendUserGroup('group 3');
    	$feGroup4 = new Tx_Extbase_Domain_Model_FrontendUserGroup('group 4');
    	$objectCollection->attach($feGroup1);
    	$objectCollection->attach($feGroup2);
    	$objectCollection->attach($feGroup3);
    	$objectCollection->attach($feGroup4);
    	return $objectCollection;
    }
    
    
    
    protected function createMapperConfiguration() {
    	$mapperConfiguration = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
    	$field1Configuration = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig('field1', array('table' => 'domObject', 'field' => 'title'));
    	$field2Configuration = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig('field2', array('table' => 'domObject', 'field' => 'title'));
    	$mapperConfiguration->addFieldConfig($field1Configuration);
    	$mapperConfiguration->addFieldConfig($field2Configuration);
    	return $mapperConfiguration;
    }
	
}

?>