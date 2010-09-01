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
 * Testcase for String Filter class
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
 class Tx_PtExtlist_Tests_Domain_Model_Filter_StringFilter_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
 	
 	public function setup() {
        $this->initDefaultConfigurationBuilderMock();
    }
    
    
    
    public function testSetup() {
 		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_StringFilter'));
 	}
 	
 	
 	
 	public function testGetFilterValueAfterSessionInjection() {
 		$filter = $this->getStringFilterInstance();
 		$filter->injectSessionData(array('filterValue' => 'sessionFilterValue'));
 		$filter->init();
 		$this->assertTrue($filter->getFilterValue() == 'sessionFilterValue');
 	}
 	
 	
 	
 	public function testGetFilterValueAfterTsConfigInjection() {
 		$filter = $this->getStringFilterInstance();
 		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
 		   array('filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter')));
 		$filter->init();
 	    $this->assertTrue($filter->getFilterValue() == 'defaultValue');
 	}
 	
 	
 	
 	public function testGetFilterValueAfterFirstInjectingConfigThenInjectingSessionData() {
 		$filter = $this->getStringFilterInstance();
 		$filter->injectSessionData(array('filterValue' => 'sessionFilterValue'));
 		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
           array('filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter')));
        $filter->init();
        $this->assertTrue($filter->getFilterValue() == 'sessionFilterValue');
 	}
 	
 	
 	
 	public function testGetFilterValueAfterFirstInjectingConfigThenInjectingSessionDataThenInjectingGpVars() {
 		$filter = $this->getStringFilterInstance();
 		
 		/* First test: GP vars holds value for filter --> gp var value should be returned */
        $filter->injectSessionData(array('filterValue' => 'sessionFilterValue'));
        $filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
           array('filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter')));
        $filter->injectGPVars(array('filterValue' => 'gpVarsValue'));
        $filter->init();
        $this->assertTrue($filter->getFilterValue() == 'gpVarsValue');
        
        /* Second test: GP vars holds no value for filter --> session data should be returned */
        $filter->injectGPVars(array());
        $filter->init();
        $this->assertTrue($filter->getFilterValue() == 'sessionFilterValue');
 	}
 	
 	
 	
 	public function testPersistToSession() {
 		$filter = $this->getStringFilterInstance();
 		$filter->injectGPVars(array('filterValue' => 'persistedFilterValue'));
 		$filter->init();
 		$sessionValue = $filter->persistToSession();
 		$this->assertTrue(array_key_exists('filterValue', $sessionValue));
 		$this->assertTrue($sessionValue['filterValue'] == 'persistedFilterValue');
 	}
 	
 	
 	
 	public function testSetAndGetFieldIdentifier() {
 		$filter = $this->getStringFilterInstance();
        $filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test',
            array('fieldIdentifier' => 'field1','filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter')));
        $filter->init();
        
        $this->assertTrue(is_a($filter->getFieldIdentifier(),'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig'));
 	}
 	
 	
 	
 	public function testThrowExceptionOnNonExistingFieldIdentifier() {
 		$filter = $this->getStringFilterInstance();
 		 try {
 			$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test',
            array('fieldIdentifier' => 'field1','filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => '', 'partialPath' => 'Filter/StringFilter')));
       
            $filter->init();
        } catch(Exception $e) {
        	return;
        }
        $this->fail('No exception has been thrown on missing field description identifier!');
        
 	}
 	
 	
 	
 	public function testCreateQuery() {
 		$filter = $this->getStringFilterInstance();
 		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test',
 		    array('fieldIdentifier' => 'field1','filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter')));
        $filter->injectGPVars(array('filterValue' => 'testValue'));
        $filter->init();
        

        $query = $filter->getFilterQuery();
        $this->assertTrue(is_a($query, 'Tx_PtExtlist_Domain_QueryObject_Query'));
        $this->assertTrue($this->queryHasCriteria($query, new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('testtable.testfield', '%testValue%', 'LIKE')));
 	}
 	
 	
 	
 	public function testValidate() {
 		$filter = $this->getStringFilterInstance();
 		$this->assertTrue($filter->validate() == true);
 	}
 	
 	
 	
 	public function testReset() {
 		$filter = $this->getStringFilterInstance();
 		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
 		    array('fieldIdentifier' => 'field1','filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'defaultValue' => 'defaultValue', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter')));
        $filter->injectSessionData(array('filterValue' => 'sessionFilterValue'));
 		$filter->injectGPVars(array('filterValue' => 'gpVarFilterValue'));
 		$filter->init();
 		$this->assertTrue($filter->getFilterValue() == 'gpVarFilterValue');
 		$filter->reset();
 		$this->assertTrue($filter->getFilterValue() =='defaultValue');
 	}
 	
 	
 	
 	/**
 	 * Returns an instance of a string filter
 	 * 
 	 * @return Tx_PtExtlist_Domain_Model_Filter_StringFilter
 	 */
 	protected function getStringFilterInstance() {
 		$filter = new Tx_PtExtlist_Domain_Model_Filter_StringFilter();
 		$filter->injectFilterConfig(new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
 		    array('filterIdentifier' => 'stringFilter1', 'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter', 'fieldIdentifier' => 'field1', 'partialPath' => 'Filter/StringFilter')));

 		$fieldConfigMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig', array('getTable', 'getField'), array('testfield', array('field' => 'testfield', 'table' => 'testtable')));
        $fieldConfigMock->expects($this->any())
            ->method('getTable')
            ->will($this->returnValue('testtable'));
        $fieldConfigMock->expects($this->any())
            ->method('getField')
            ->will($this->returnValue('testfield'));
            
        $fieldConfigCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection', array('getFieldConfigByIdentifier'));
        $fieldConfigCollectionMock->expects($this->any())
            ->method('getFieldConfigByIdentifier')
            ->will($this->returnValue($fieldConfigMock));
            
        $dataBackendMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend', array('getFieldConfigurationCollection'), array($this->configurationBuilderMock));
        $dataBackendMock->expects($this->any())
            ->method('getFieldConfigurationCollection')
            ->will($this->returnValue($fieldConfigCollectionMock));
            
        $sessionPersistenceManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array('loadFromSession', 'persistToSession'), array(), '', FALSE);
            
        $filter->injectDataBackend($dataBackendMock);
        $filter->injectSessionPersistenceManager($sessionPersistenceManagerMock);
 		
 		return $filter;
 	}
 	
 	
 	
 	/**
 	 * Checks whether a query has a criteria
 	 *
 	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
 	 * @param Tx_PtExtlist_Domain_QueryObject_Criteria $criteria
 	 * @return bool True, if criteria is contained by query
 	 */
 	protected function queryHasCriteria(Tx_PtExtlist_Domain_QueryObject_Query $query, Tx_PtExtlist_Domain_QueryObject_Criteria $criteria) {
 		$criterias = $query->getCriterias();
 		foreach($criterias as $queryCriteria) { /* @var $queryCriteria Tx_PtExtlist_Domain_QueryObject_Criteria */
 			if ($criteria->isEqualTo($queryCriteria)) {
 				return true;
 			}
 		}
 		return false;
 	}
 	
 	
 	
}
 
?>