<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert , Michael Knoll 
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
 * @subpackage Domain\Model\Filter
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_AbstractOptionsFilterTest extends Tx_PtExtlist_Tests_BaseTestcase {
    
	protected $defaultFilterSettings;
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
	public function testCreateFilterQuerySingleValue() {
    	
        $selectFilter = $this->buildAccessibleAbstractGroupDataFilter();
		$selectFilter->_set('filterValues', array('filterValue' => 'filterValue'));
		
        $selectFilter->_call('buildFilterQuery');
 		$filterQuery = $selectFilter->_get('filterQuery');
		$criterias = $filterQuery->getCriterias();

		$this->assertEquals(count($criterias), 1);
		$this->assertTrue(is_a($criterias[0], 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
		$this->assertEquals($criterias[0]->getField(), 'tableName1.fieldName1');
		$this->assertEquals($criterias[0]->getValue(), 'filterValue');
		$this->assertEquals($criterias[0]->getOperator(), '=');
    }
    
    
	public function testCreateFilterQueryMultipleValue() {
    	
        $selectFilter = $this->buildAccessibleAbstractGroupDataFilter();
		$selectFilter->_set('filterValues', array('filterValue1', 'filterValue2'));
		
        $selectFilter->_call('buildFilterQuery');
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
               'filterIdentifier' => 'field1', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'displayFields' => 'field1,field2',
               'filterField' => 'field1',
               'invert' => '1'
       		 );
		
		
        $selectFilter = $this->buildAccessibleAbstractGroupDataFilter($filterSettings);
		$selectFilter->_set('filterValues', array('filterValue' => 'filterValue'));
		
        $selectFilter->_call('buildFilterQuery');
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
    
    public function testAddInactiveOption() {
    	
    	$settings = array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'displayFields' => 'field1,field2',
               'filterField' => 'field3',
               'invert' => '0',
    		   'inactiveOption' => 'all',
       		 );
    	
    	$abstractGroupDataFilter = $this->buildAccessibleAbstractGroupDataFilter($settings);
    	$options = array();
    	$abstractGroupDataFilter->_callRef('addInactiveOption', $options);
    	
    	$this->assertEquals($options['']['value'], 'all');
    	$this->assertEquals($options['']['selected'], true, 'Selected must be true cause no filterValues are set');
    	
    	$options = array();
    	$abstractGroupDataFilter->_set('filterValues', array('x' => 'x'));
    	$abstractGroupDataFilter->_callRef('addInactiveOption', $options);
    	
    	$this->assertEquals($options['']['value'], 'all');
    	$this->assertEquals($options['']['selected'], false, 'Selected must be false cause we have a filterValue');
    }
       
    
    public function testGetValues() {
    	$this->markTestIncomplete();
    }
	
    
	public function testInitOnCorrectConfiguration() {
    	$selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
        	$this->configurationBuilderMock,
           array(
               'filterIdentifier' => 'field1', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'displayFields' => 'field1,field2',
               'filterField' => 'field1'
        ),'test');
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
    	
    	try {
	    	$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
	    	$this->configurationBuilderMock,
	    	   array(
	    	       'filterIdentifier' => 'field1', 
	    	       'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
	    	       'partialPath' => 'Filter/SelectFilter',
	               'optionsSourceField' => 'field1,field2',
	    	),'test');
    	
            $selectFilter->init();
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No error has been thrown on configuration without fieldIdentifier');
    }
    
    
    
    public function testOnMissingFilterFieldConfiguration() {
    	$selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
        $this->configurationBuilderMock,
           array(
               'filterIdentifier' => 'field1', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'displayField' => 'field2'
        ), 'test');
        $sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        
        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());
    	$selectFilter->injectDataBackend($dataBackendMock);
        
        $selectFilter->injectSessionPersistenceManager($sessionManagerMock);
        $selectFilter->injectFilterConfig($filterConfiguration);
    }
    
    
   public function testSetDefaultValuesFromTSConfigSingle() {
   		$testFilter = $this->buildAccessibleAbstractGroupDataFilter();
   		$defaultValue = 'test';
   		$testFilter->_call('setDefaultValuesFromTSConfig', $defaultValue);
   		$this->assertEquals(array('test' => 'test'), $testFilter->_get('filterValues'));
   }
    
   
 	public function testSetDefaultValuesFromTSConfigMultiple() {
   		$testFilter = $this->buildAccessibleAbstractGroupDataFilter();
   		$defaultValue = array(10 => 'test', 20 => 'test2');
   		$testFilter->_call('setDefaultValuesFromTSConfig', $defaultValue);
   		$this->assertEquals(array('test' => 'test', 'test2' => 'test2'), $testFilter->_get('filterValues'));
   }
   
   
    /**
     * Utility Methods
     */
    
    public function buildAccessibleAbstractGroupDataFilter($filterSettings = NULL) {
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
    	
    	$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter');
    	$abstractOptionsFilter = $this->getMockForAbstractClass($accessibleClassName);
        
    	$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock,$this->defaultFilterSettings,'test');
        $sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        
        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());
        
        $abstractOptionsFilter->injectFilterConfig($filterConfiguration);
        $abstractOptionsFilter->injectSessionPersistenceManager($sessionManagerMock);
        $abstractOptionsFilter->injectDataBackend($dataBackendMock);
        $abstractOptionsFilter->init();
        
        return $abstractOptionsFilter;
    }
    
    

    
    
    
}
?>