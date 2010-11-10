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
 * Testcase for abstract filter class
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_AbstractFilterTest extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_AbstractFilter'));
    }
    
    
    
    public function testInjectFilterConfig() {
        $filter = $this->getExtendingFilterMock();    	
        $filterConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig( array('fieldIdentifier' => 'test','filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub', 'partialPath' => 'partialPath'), 'test');
        $filter->injectFilterConfig($filterConfig);
    }
    
    
    
    public function testInjectGpVarAdapter() {
    	$filter = $this->getExtendingFilterMock();
    	$gpVarAdapter = new Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter();
    	$filter->injectGpVarAdapter($gpVarAdapter);
    }
    
    
    
    public function testGetters() {
    	$filter = $this->getExtendingFilterMock();     
        $filterConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(array('fieldIdentifier' => 'test','filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub', 'partialPath' => 'partialPath'),	'test');
        $filterConfig->injectConfigurationBuilder($this->configurationBuilderMock);
        $filter->injectFilterConfig($filterConfig);
        
        $this->assertEquals($filter->getFilterIdentifier(), 'test');
        $this->assertEquals($filter->getFilterBoxIdentifier(), 'test');
        $this->assertEquals($filter->getListIdentifier(),'test');
        $this->assertEquals($filter->getObjectNamespace(), 'tx_ptextlist_pi1.test.filters.test.test', 'Object namespace was expected to be tx_ptextlist_pi1.test.filters.test.test but was ' . $filter->getObjectNamespace());
    }
    
    
    
    public function testInjectSessionData() {
    	$filter = $this->getExtendingFilterMock();
    	$filter->injectSessionData(array('filterValue' => 'filterValueValue'));
    }
    
    
    
    public function testGetFilterConfiguration() {
    	$filterConfiguration = $this->getMock('Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig', array(), array(), '', FALSE);
    	$filter = new Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub('testfilter');
    	$filter->injectFilterConfig($filterConfiguration);
    	$this->assertTrue($filter->getFilterConfig() == $filterConfiguration);
    }
    
    
    
    /**
     * Returns a filter mock object for testing abstract class
     *
     * @return Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub
     */
    protected function getExtendingFilterMock() {
    	$filter = new Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub('testFilter');
    	return $filter;
    }
	
}



?>