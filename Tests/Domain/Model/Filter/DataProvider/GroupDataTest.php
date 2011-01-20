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
 * Testcase for abstract groupDataFilter class
 *
 * @package Tests
 * @subpackage Somain\Model\Filter\DataProvider
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_GroupDataTest extends Tx_PtExtlist_Tests_BaseTestcase {
    
	
	protected $defaultFilterSettings;
	
	
	
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
   
   
   
   protected function buildAccessibleGroupDataProvider($filterSettings) {
   		
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
    	$filterConfiguration->injectConfigurationBuilder($this->configurationBuilderMock);
    	    	
    	$dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilderMock);
    	
   		$accesibleGroupDataProvider->injectFilterConfig($filterConfiguration);
   		$accesibleGroupDataProvider->init();
   		
   		return $accesibleGroupDataProvider;
   }
}
?>