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
 * Testcase for abstract filter class
 *
 * @package Tests
 * @subpackage pt_extlist
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_AbstractFilterTest extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	


	/** @test */
    public function makeSureClassExists() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_AbstractFilter'));
    }
    
    

	/** @test */
    public function filterConfigCanBeInjected() {
        $filter = $this->getExtendingFilterMock();    	
        $filterConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, array('fieldIdentifier' => 'field1','filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub', 'partialPath' => 'partialPath'), 'test');
        $filter->injectFilterConfig($filterConfig);
    }
    


	/** @test */
    public function gpVarAdapterCanBeInjected() {
    	$filter = $this->getExtendingFilterMock();
    	$gpVarAdapter = new Tx_PtExtbase_State_GpVars_GpVarsAdapter();
    	$filter->injectGpVarAdapter($gpVarAdapter);
    }
    
    

	/** @test */
    public function gettersReturnExpectedResults() {
    	$filter = $this->getExtendingFilterMock();     
        $filterConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, array('fieldIdentifier' => 'field1','filterIdentifier' => 'test', 'filterClassName' => 'Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub', 'partialPath' => 'partialPath'),	'test');
        $filter->injectFilterConfig($filterConfig);
        
        $this->assertEquals($filter->getFilterIdentifier(), 'test');
        $this->assertEquals($filter->getFilterBoxIdentifier(), 'test');
        $this->assertEquals($filter->getListIdentifier(),'test');
        $this->assertEquals($filter->getObjectNamespace(), 'test.filters.test.test', 'Object namespace was expected to be tx_ptextlist_pi1.test.filters.test.test but was ' . $filter->getObjectNamespace());
    }
    
    

	/** @test */
    public function sessionDataCanBeInjected() {
    	$filter = $this->getExtendingFilterMock();
    	$filter->injectSessionData(array('filterValue' => 'filterValueValue'));
    }
    
    

	/** @test */
    public function getFilterConfigurationReturnsExpectedConfiguration() {
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



    /** @test */
    public function getDisplayValueSingleReturnsExpectedValue() {
        $filterClass = $this->buildAccessibleProxy('Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub', 'testfilter');
        $filter = new $filterClass;
        $filter->_set('filterValue', 'test');
        $this->assertEquals('test', $filter->getDisplayValue());
    }



    /** @test */
    public function getDisplayValueMultipleReturnsExpectedValues() {
        $filterClass = $this->buildAccessibleProxy('Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub', 'testfilter');
        $filter = new $filterClass;
        $filter->_set('filterValue', array('val1', 'val2'));
        $this->assertEquals('val1, val2', $filter->getDisplayValue());
    }

}
?>