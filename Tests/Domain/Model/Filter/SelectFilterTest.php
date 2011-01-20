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
 * @package Tests
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_SelectFilter_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_SelectFilter'));
    	$selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();
    	$this->assertTrue(is_a($selectFilter, 'Tx_PtExtlist_Domain_Model_Filter_FilterInterface'));
    }
       
    
    
    public function testGetMultiple() {
    	$selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
        $this->configurationBuilderMock, 
           array(
               'filterIdentifier' => 'test', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
               'partialPath' => 'Filter/SelectFilter',
               'fieldIdentifier' => 'field1',
               'filterField' => 'field2',
               'displayFields' => 'field1',
               'excludeFilters' => 'filterbox1.filter1',
               'multiple' => 1
        ),'test');
        $selectFilter->injectFilterConfig($filterConfiguration);
        $sessionManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array(), array(), '', FALSE);
        
        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());
    	$selectFilter->injectDataBackend($dataBackendMock);
        
        $selectFilter->injectSessionPersistenceManager($sessionManagerMock);
        $selectFilter->init();
        $this->assertEquals($selectFilter->getMultiple(), 1);
    }
    
}

?>