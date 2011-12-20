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
 * Testcase for abstract groupDataFilter class
 *
 * @package Tests
 * @subpackage Somain\Model\Filter\DataProvider
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_GroupDataTest extends Tx_PtExtlist_Tests_BaseTestcase {
    
	
	protected $defaultFilterSettings = array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'displayFields' => 'field1,field2',
               'filterField' => 'field3',
               'invert' => '0'
       		 );
	
	
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	

    public function testGetFieldsRequiredToBeSelected() {
    	$groupDataProvider = $this->buildAccessibleGroupDataProvider();

    	$fields = $groupDataProvider->_call('getFieldsRequiredToBeSelected');
    	$this->assertEquals($fields->count(), 3);
    	
    	$this->assertTrue($fields->hasItem($this->defaultFilterSettings['filterField']));
    	$this->assertTrue($fields->hasItem('field1'));
    	$this->assertTrue($fields->hasItem('field2'));
    	
    }
	
   
    public function testBuildExcludeFiltersArray() {
    	$filterSettings = array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'displayFields' => 'field1,field2',
               'filterField' => 'field3',
               'excludeFilters' => 'filterbox1.filter1'
        );
        
    	$groupDataProvider = $this->buildAccessibleGroupDataProvider($filterSettings);
        $excludeFiltersArray = $groupDataProvider->_call('buildExcludeFiltersArray');
        $this->assertTrue(array_key_exists('filterbox1', $excludeFiltersArray) && in_array('filter1', $excludeFiltersArray['filterbox1']));
    }
    
     
   public function testBuildGroupDataQuery() {
   		$groupDataProvider = $this->buildAccessibleGroupDataProvider();
   		$ret = $groupDataProvider->_call('buildGroupDataQuery', $groupDataProvider->_call('getFieldsRequiredToBeSelected'));

   		$this->assertTrue(is_a($ret, 'Tx_PtExtlist_Domain_QueryObject_Query'));
   		$this->assertTrue(count($ret->getFields()) == 3);

   		$sortings = $ret->getSortings();
   		$this->assertTrue($sortings['field1'] == Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
   		$this->assertTrue($sortings['field2'] == Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
   }  
   
   
   /**
    * @test
    */
   public function displayFieldsEqualsFieldIdentifierIfNotSet() {
   		$settings = $this->defaultFilterSettings;
   		unset($settings['displayFields']);
		
   		$groupDataProvider = $this->buildAccessibleGroupDataProvider($settings);
		$displayFields = $groupDataProvider->_GET('displayFields');
		
		$this->assertEquals($displayFields->count(),1);
		$this->assertEquals($displayFields->getItemByIndex(0)->getIdentifier(),'field1');
   }

   
    /**
    * @test
    */
   public function displayFieldSetExplicitly() {
		$groupDataProvider = $this->buildAccessibleGroupDataProvider();
		$displayFields = $groupDataProvider->_GET('displayFields');
		
		$this->assertEquals($displayFields->count(),2);
		$this->assertEquals($displayFields->getItemByIndex(1)->getIdentifier(),'field2');
   }

   
   
   /**
    * @test
    */
   public function filterFieldsEqualFirstFieldIdentifierIfNotSet() {
		$settings = $this->defaultFilterSettings;
   		unset($settings['filterField']);
		
   		$groupDataProvider = $this->buildAccessibleGroupDataProvider($settings);
		$filterField = $groupDataProvider->_GET('filterField');
		
		$this->assertEquals($filterField->getIdentifier(),'field1');
   }
   
   
   
   /**
    * @test
    */
   public function filterFieldSetExplicitly() {
   		$groupDataProvider = $this->buildAccessibleGroupDataProvider();
		$filterField = $groupDataProvider->_GET('filterField');
		
		$this->assertEquals($filterField->getIdentifier(),'field3');
   }
   
   
   protected function buildAccessibleGroupDataProvider($filterSettings = null) {
   		
   		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DataProvider_GroupData');
   		$accesibleGroupDataProvider = new $accessibleClassName;
   		
 	  	$this->defaultFilterSettings = $filterSettings;
    	if(!$this->defaultFilterSettings) {
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
   		
    	$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $this->defaultFilterSettings,'test');
    	    	
    	$dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilderMock);

        $accesibleGroupDataProvider->injectDataBackend($dataBackend);
   		$accesibleGroupDataProvider->injectFilterConfig($filterConfiguration);
   		$accesibleGroupDataProvider->init();
   		
   		return $accesibleGroupDataProvider;
   }  
}
?>