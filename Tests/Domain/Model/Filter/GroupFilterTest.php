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
 * Testcase for group filter
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_GroupFilter_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_GroupFilter'));
    	$groupFilter = new Tx_PtExtlist_Domain_Model_Filter_GroupFilter();
    	$this->assertTrue(is_a($groupFilter, 'Tx_PtExtlist_Domain_Model_Filter_FilterInterface'));
    }
    
    
    
    public function testInitOnCorrectConfiguration() {
    	$groupFilter = new Tx_PtExtlist_Domain_Model_Filter_GroupFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
           array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_GroupFilter',
               'partialPath' => 'Filter/GroupFilter',
               'fieldDescriptionIdentifier' => 'test',
               'displayFields' => 'test1,test2',
               'filterFields' => 'test'
        ));
        $sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        $groupFilter->injectFilterConfig($filterConfiguration);
        $groupFilter->injectSessionPersistenceManager($sessionManagerMock);
        $groupFilter->init();
    }
    
    
    
    public function testExceptionsOnMissingFieldDescriptionIdentifierConfiguration() {
    	$groupFilter = new Tx_PtExtlist_Domain_Model_Filter_GroupFilter();
    	$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
    	   array(
    	       'filterIdentifier' => 'test', 
    	       'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_GroupFilter',
    	       'partialPath' => 'Filter/GroupFilter',
               'partialPath' => 'Filter/GroupFilter',
               'optionsSourceFields' => 'test1,test2'
    	));
    	$sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        $groupFilter->injectSessionPersistenceManager($sessionManagerMock);
    	$groupFilter->injectFilterConfig($filterConfiguration);
    	try {
            $groupFilter->init();
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No error has been thrown on configuration without fieldDescriptionIdentifier');
    }
    
    
    
    public function testExceptionOnMissingFilterFieldsConfiguration() {
    	$groupFilter = new Tx_PtExtlist_Domain_Model_Filter_GroupFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
           array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_GroupFilter',
               'partialPath' => 'Filter/GroupFilter',
               'partialPath' => 'Filter/GroupFilter',
               'fieldDescriptionIdentifier' => 'test',
               'displayFields' => 'test'
        ));
        $sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        $groupFilter->injectSessionPersistenceManager($sessionManagerMock);
        $groupFilter->injectFilterConfig($filterConfiguration);
        try {
            $groupFilter->init();
        } catch(Exception $e) {
            return;
        }
        $this->fail('No error has been thrown on configuration without filter fields configuration');
    }
    
    
    
    public function testExceptionOnMissingDisplayFieldsConfiguration() {
        $groupFilter = new Tx_PtExtlist_Domain_Model_Filter_GroupFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
           array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_GroupFilter',
               'partialPath' => 'Filter/GroupFilter',
               'partialPath' => 'Filter/GroupFilter',
               'fieldDescriptionIdentifier' => 'test',
               'filterFields' => 'test'
        ));
        $sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        $groupFilter->injectSessionPersistenceManager($sessionManagerMock);
        $groupFilter->injectFilterConfig($filterConfiguration);
        try {
            $groupFilter->init();
        } catch(Exception $e) {
            return;
        }
        $this->fail('No error has been thrown on configuration without display fields configuration');
    }
    
    
    
    public function testBuildExcludeFiltersArray() {
    	$groupFilter = new Tx_PtExtlist_Domain_Model_Filter_GroupFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, 'test', 
           array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_GroupFilter',
               'partialPath' => 'Filter/GroupFilter',
               'partialPath' => 'Filter/GroupFilter',
               'fieldDescriptionIdentifier' => 'test',
               'filterFields' => 'test.test',
               'displayFields' => 'test.test',
               'excludeFilters' => 'filterbox1.filter1'
        ));
        $groupFilter->injectFilterConfig($filterConfiguration);
        $sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        $groupFilter->injectSessionPersistenceManager($sessionManagerMock);
        $groupFilter->init();
        $excludeFiltersArray = $groupFilter->buildExcludeFiltersArray();
        $this->assertTrue(array_key_exists('filterbox1', $excludeFiltersArray) && in_array('filter1', $excludeFiltersArray['filterbox1']));
    }
	
}

?>