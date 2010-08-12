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
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Daniel Lienert <lienert@punkt.de>>
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_AbstractGroupDataFilterTest extends Tx_PtExtlist_Tests_BaseTestcase {
    
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
	public function testCreateFilterQuerySingleValue() {
    	
        $selectFilter = $this->buildAccessibleSelectFilter();
		$selectFilter->_set('filterValues', array('filterValue'));
		
        $selectFilter->_call('createFilterQuery');
 		$filterQuery = $selectFilter->_get('filterQuery');
		$criterias = $filterQuery->getCriterias();
		
		$this->assertEquals(count($criterias), 1);
		$this->assertTrue(is_a($criterias[0], 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
		$this->assertEquals($criterias[0]->getField(), 'tableName1.fieldName1');
		$this->assertEquals($criterias[0]->getValue(), 'filterValue');
		$this->assertEquals($criterias[0]->getOperator(), '=');
    }
    
    
	public function testCreateFilterQueryMultipleValue() {
    	
        $selectFilter = $this->buildAccessibleSelectFilter();
		$selectFilter->_set('filterValues', array('filterValue1', 'filterValue2'));
		
        $selectFilter->_call('createFilterQuery');
 		$filterQuery = $selectFilter->_get('filterQuery');
		$criterias = $filterQuery->getCriterias();
		
		$this->assertEquals(count($criterias), 1);
		$this->assertTrue(is_a($criterias[0], 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
		$this->assertEquals($criterias[0]->getField(), 'tableName1.fieldName1');
		$this->assertEquals($criterias[0]->getValue(), array('filterValue1', 'filterValue2'));
		$this->assertEquals($criterias[0]->getOperator(), 'IN');
    }
    
    
	public function testCreateFilterQuerySingleValueInverted() {
    	
		$filterSettings = array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'displayFields' => 'test1,test2',
               'filterField' => 'test',
               'invert' => '1'
       		 );
		
		
        $selectFilter = $this->buildAccessibleSelectFilter($filterSettings);
		$selectFilter->_set('filterValues', array('filterValue'));
		
        $selectFilter->_call('createFilterQuery');
 		$filterQuery = $selectFilter->_get('filterQuery');
		$criterias = $filterQuery->getCriterias();
		
		$this->assertEquals(count($criterias), 1);
		$this->assertTrue(is_a($criterias[0], 'Tx_PtExtlist_Domain_QueryObject_NotCriteria'));
    }
    
    
    public function testInit() {
    	$this->markTestIncomplete();
    }
    
    
    
    public function testReset() {
    	$this->markTestIncomplete();
    }
    
    
    
    public function testPersistToSession() {
    	$this->markTestIncomplete();
    }
    
    
    
    public function testGetOptions() {
    	$this->markTestIncomplete();
    }
    
    
    
    public function testGetFieldsRequiredToBeSelected() {
    	$this->markTestIncomplete();
    }
    
    
    
    public function testGetValues() {
    	$this->markTestIncomplete();
    }
	
    
public function testInitOnCorrectConfiguration() {
    	$selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
           array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'displayFields' => 'test1,test2',
               'filterField' => 'test'
        ));
        $sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        
        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());
        
        $selectFilter->injectFilterConfig($filterConfiguration);
        $selectFilter->injectSessionPersistenceManager($sessionManagerMock);
        $selectFilter->injectDataBackend($dataBackendMock);
        $selectFilter->init();
    }
    
    
    
    public function testExceptionsOnMissingFieldIdentifierConfiguration() {
    	$selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();
    	$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
    	   array(
    	       'filterIdentifier' => 'test', 
    	       'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
    	       'partialPath' => 'Filter/SelectFilter',
               'optionsSourceField' => 'test1,test2',
    	   		'fieldIdentifier' => 'field1',
    	));
    	
    	$sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());
    	$selectFilter->injectDataBackend($dataBackendMock);
        
    	$selectFilter->injectSessionPersistenceManager($sessionManagerMock);
    	$selectFilter->injectFilterConfig($filterConfiguration);
    	
    	
    	try {
            $selectFilter->init();
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No error has been thrown on configuration without fieldIdentifier');
    }
    
    
    
    public function testExceptionOnMissingFilterFieldsConfiguration() {
    	$selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
           array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'displayField' => 'test'
        ));
        $sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        
        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());
    	$selectFilter->injectDataBackend($dataBackendMock);
        
        $selectFilter->injectSessionPersistenceManager($sessionManagerMock);
        $selectFilter->injectFilterConfig($filterConfiguration);
        try {
            $selectFilter->init();
        } catch(Exception $e) {
            return;
        }
        $this->fail('No error has been thrown on configuration without filter fields configuration');
    }
     
    
    public function testBuildExcludeFiltersArray() {
    	$selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
           array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'filterField' => 'test.test',
               'displayFields' => 'test.test',
               'excludeFilters' => 'filterbox1.filter1'
        ));
        $selectFilter->injectFilterConfig($filterConfiguration);
        $sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        
        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());
    	$selectFilter->injectDataBackend($dataBackendMock);
        
        $selectFilter->injectSessionPersistenceManager($sessionManagerMock);
        $selectFilter->init();
        $excludeFiltersArray = $selectFilter->buildExcludeFiltersArray();
        $this->assertTrue(array_key_exists('filterbox1', $excludeFiltersArray) && in_array('filter1', $excludeFiltersArray['filterbox1']));
    }
    
    
    /**
     * Utility Methods
     */
    
    public function buildAccessibleSelectFilter($filterSettings = NULL) {
    	
    	if(!$filterSettings) {
    		$filterSettings = array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'displayFields' => 'test1,test2',
               'filterField' => 'test',
               'invert' => '0'
       		 );
    	}
    	
    	$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_SelectFilter');
    	$selectFilter = new $accessibleClassName;
        
    	$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', $filterSettings);
        $sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        
        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());
        
        $selectFilter->injectFilterConfig($filterConfiguration);
        $selectFilter->injectSessionPersistenceManager($sessionManagerMock);
        $selectFilter->injectDataBackend($dataBackendMock);
        $selectFilter->init();
        
        return $selectFilter;
    }
    
    
    
    
    
    
}
?>