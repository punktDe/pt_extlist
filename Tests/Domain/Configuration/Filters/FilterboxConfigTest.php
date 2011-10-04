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
 * Class implementing testcase for filterbox configuration factory
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll , Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Filters_FilterboxConfig_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setUp() {
		$this->initDefaultConfigurationBuilderMock();
	}


    
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig'), 'Class Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig does not exist!');
    }



    public function testGetFilterIdentifier() {
    	$filterBoxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array());
    	$this->assertEquals('testfilterbox', $filterBoxConfig->getFilterboxIdentifier());
    }



    public function testGetshowReset() {
    	$filterBoxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array());
    	$this->assertEquals(true, $filterBoxConfig->getShowReset());
    	
    	$filterBoxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('showReset' => 0));
    	$this->assertEquals(false, $filterBoxConfig->getShowReset());
    }


    
	public function testGetshowSubmit () {
    	$filterBoxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array());
    	$this->assertEquals(true, $filterBoxConfig->getShowSubmit());
    	
    	$filterBoxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('showSubmit' => 0));
    	$this->assertEquals(false, $filterBoxConfig->getShowSubmit());
    }
    


    public function testGetRedirectOnSubmitPageId() {
    	$pageId = 10;
    	$filterboxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('redirectOnSubmit' => array('action'=>'action', 'pageId' => $pageId)));
    	$this->assertEquals($pageId, $filterboxConfig->getRedirectOnSubmitPageId());
    }
    


    public function testGetRedirectOnSubmitControllerName() {
    	$testController = 'testController';
    	$filterboxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('redirectOnSubmit' => array('action'=>'action', 'controller' => $testController)));
    	$this->assertEquals($testController, $filterboxConfig->getRedirectOnSubmitControllerName());
    }
    


    public function testGetRedirectOnSubmitActionName() {
    	$testAction = 'testAction';
    	$filterboxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('redirectOnSubmit' => array('action' => $testAction)));
    	$this->assertEquals($testAction, $filterboxConfig->getRedirectOnSubmitActionName());
    }


    
    public function testDoRedirectOnSubmit() {
    	$filterboxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('redirectOnSubmit' => array('action'=>'action')));
        $this->assertTrue($filterboxConfig->doRedirectOnSubmit(), 'Filterboxconfig says no redirect although we gave a redirect page id!');
    	$filterboxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('redirectOnSubmit' => array('action'=>'action', 'pageId' => 10)));
    	$this->assertTrue($filterboxConfig->doRedirectOnSubmit(), 'Filterboxconfig says no redirect although we gave a redirect page id!');
    	
    	$filterboxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('redirectOnSubmit' => array('action'=>'action', 'controller' => 'test')));
    	$this->assertTrue($filterboxConfig->doRedirectOnSubmit(), 'Filterboxconfig says no redirect although we gave a redirect controller name!');
    	
    	$filterboxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array());
    	$this->assertFalse($filterboxConfig->doRedirectOnSubmit(), 'Filterbox says redirect althout we configured no redirect after submit!');
    }


    
    public function testSettingPageIdRedirectParametersWithoutActionThrowsException() {
    	try {
    		$filterboxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('redirectOnSubmit' => array('pageId' => 10)));
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No Exception has been thrown when trying to set redirect parameters without an action');
    }
    


    public function testSettingControllerRedirectParametersWithoutActionThrowsException() {
        try {
            $filterboxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('redirectOnSubmit' => array('controller' => 'test')));
        } catch(Exception $e) {
            return;
        }
        $this->fail('No Exception has been thrown when trying to set redirect parameters without an action');
    }


     /** @test */
    public function getExcludeFiltersReturnsExcludeFiltersFromGivenSettings() {
        $filterboxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('excludeFilters' => 'filterbox1.filter1, filterbox1.filter2, filterbox2.filter1'));
        $expectedExcludeFiltersArray = array(
            'filterbox1' => array('filter1', 'filter2'),
            'filterbox2' => array('filter1')
        );
        $this->assertEquals($filterboxConfig->getExcludeFilters(), $expectedExcludeFiltersArray);
    }



    /** @test */
    public function getSubmitToPageReturnsPidSetInSettings() {
        $filterboxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('submitToPage' => '10'));
        $this->assertEquals($filterboxConfig->getSubmitToPage(), 10);
    }

}

?>