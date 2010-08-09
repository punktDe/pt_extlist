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

class Tx_PtExtlist_Tests_Configuration_Columns_SortingCollectionFactory_testcase extends Tx_Extbase_BaseTestcase {
	
	public function setup() {
		
		
	}
	
	public function testSingleSortingDefinitionNotForced() {
		$testDefinition = 'name asc';
		$sortingCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceBySortingSettings($testDefinition);
		$sortingConfigObject = $sortingCollection->getItemById('name');

		$this->assertTrue(is_a($sortingConfigObject, 'Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig'));
		$this->assertEquals($sortingCollection->count(), 1);
		
		$this->assertEquals($sortingConfigObject->getField(),'name');
		$this->assertEquals($sortingConfigObject->getDirection(), Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
		$this->assertEquals($sortingConfigObject->getForceDirection(),false, 'ForceDirection');		
	}
	
	public function testSingleSortingDefinitionForced() {
		$testDefinition = 'name !Desc';
		$sortingCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceBySortingSettings($testDefinition);
		$sortingConfigObject = $sortingCollection->getItemById('name');

		$this->assertTrue(is_a($sortingConfigObject, 'Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig'));
		$this->assertEquals($sortingCollection->count(), 1);
		
		$this->assertEquals($sortingConfigObject->getField(),'name');
		$this->assertEquals($sortingConfigObject->getDirection(), Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC);
		$this->assertEquals($sortingConfigObject->getForceDirection(),true, 'ForceDirection');		
	}
	
	public function testMultiSortingDefinition() {
		$testDefinition = 'name !Desc, company ASC';
		$sortingCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceBySortingSettings($testDefinition);
		
		$this->assertEquals($sortingCollection->count(), 2);
		
		// Test Object name
		$sortingConfigNameObject = $sortingCollection->getItemById('name');
		$this->assertTrue(is_a($sortingConfigNameObject, 'Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig'));
		
		$this->assertEquals($sortingConfigNameObject->getField(),'name');
		$this->assertEquals($sortingConfigNameObject->getDirection(), Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC);
		$this->assertEquals($sortingConfigNameObject->getForceDirection(),true, 'ForceDirection');

		// Test Object company
		
		$sortingConfigNameObject = $sortingCollection->getItemById('company');
		$this->assertTrue(is_a($sortingConfigNameObject, 'Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig'));
		
		$this->assertEquals($sortingConfigNameObject->getField(),'company');
		$this->assertEquals($sortingConfigNameObject->getDirection(), Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
		$this->assertEquals($sortingConfigNameObject->getForceDirection(),false, 'ForceDirection');
	}
	
	public function testIncompleteSortingDefinition() {
		$testDefinition = 'name';
		$sortingCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceBySortingSettings($testDefinition);
		
		$this->assertEquals($sortingCollection->count(), 1);
		
		$sortingConfigObject = $sortingCollection->getItemById('name');
		$this->assertTrue(is_a($sortingConfigObject, 'Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig'),'The result is not an object!');
		
		$this->assertEquals($sortingConfigObject->getField(),'name');
		$this->assertEquals($sortingConfigObject->getDirection(), Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
		$this->assertEquals($sortingConfigObject->getForceDirection(),false, 'ForceDirection should be false');
	}
	
	public function testMissingSortingDefinition() {
		$testDefinition = '';
		$sortingCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceBySortingSettings($testDefinition);
		$this->assertEquals($sortingCollection->count(), 0);
	}
	
	public function testGetInstanceByFieldConfigurationSingle() {
		$fieldConfiguration = 'name';
		$sortingCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceByFieldConfiguration($fieldConfiguration);
		
		$this->assertEquals($sortingCollection->count(), 1);
		
		$sortingConfigObject = $sortingCollection->getItemById('name');
		$this->assertTrue(is_a($sortingConfigObject, 'Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig'),'The result is not an object!');
		
		$this->assertEquals($sortingConfigObject->getField(),'name');
		$this->assertEquals($sortingConfigObject->getDirection(), NULL);
		$this->assertEquals($sortingConfigObject->getForceDirection(), false, 'ForceDirection should be false!');
	}
	
	public function testGetInstanceByFieldConfigurationMulti() {
		$fieldConfiguration = 'name,company';
		$sortingCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceByFieldConfiguration($fieldConfiguration);
		
		$this->assertEquals($sortingCollection->count(), 2);
		
		// Test Object name
		$sortingConfigNameObject = $sortingCollection->getItemById('name');
		$this->assertTrue(is_a($sortingConfigNameObject, 'Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig'));
		
		$this->assertEquals($sortingConfigNameObject->getField(),'name');
		$this->assertEquals($sortingConfigNameObject->getDirection(), NULL);
		$this->assertEquals($sortingConfigNameObject->getForceDirection(),false, 'ForceDirection');

		// Test Object company
		
		$sortingConfigNameObject = $sortingCollection->getItemById('company');
		$this->assertTrue(is_a($sortingConfigNameObject, 'Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig'));
		
		$this->assertEquals($sortingConfigNameObject->getField(),'company');
		$this->assertEquals($sortingConfigNameObject->getDirection(), NULL);
		$this->assertEquals($sortingConfigNameObject->getForceDirection(), false, 'ForceDirection');
	}
	
}
?>