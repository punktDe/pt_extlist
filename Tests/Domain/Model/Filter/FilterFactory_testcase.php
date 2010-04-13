<?php

class Tx_PtExtlist_Tests_Domain_Model_Filter_FilterFactory_testcase extends Tx_Extbase_BaseTestcase {
	
	public function testCreateInstanceByConfiguration() {
		
		$mockFilterConfiguration = $this->getMock(
            'Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig',
            array('getFilterIdentifier', 'getFilterClassName'),array(),'',FALSE,FALSE,FALSE);
		
        $mockFilterConfiguration->expects($this->once())
            ->method('getFilterIdentifier')
            ->will($this->returnValue('testFilterIdentifier'));
        
        $mockFilterConfiguration->expects($this->once())
            ->method('getFilterClassName')
            ->will($this->returnValue('Tx_PtExtlist_Domain_Model_Filter_FilterStub'));
            
		$filter = Tx_PtExtlist_Domain_Model_Filter_FilterFactory::createInstanceByFilterConfig($mockFilterConfiguration);
		
		$this->assertEquals($filter->getFilterIdentifier(), 'testFilterIdentifier');
		
	}
	
	
	public function testCreateNonInterfaceImplementingClass() {
		$mockFilterConfiguration = $this->getMock(
            'Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig',
            array('getFilterIdentifier', 'getFilterClassName'),array(),'',FALSE,FALSE,FALSE);
        
        $mockFilterConfiguration->expects($this->once())
            ->method('getFilterIdentifier')
            ->will($this->returnValue('testFilterIdentifier'));
        
        $mockFilterConfiguration->expects($this->once())
            ->method('getFilterClassName')
            ->will($this->returnValue(__CLASS__));
        
        try {
            $filter = Tx_PtExtlist_Domain_Model_Filter_FilterFactory::createInstanceByFilterConfig($mockFilterConfiguration);
        } catch(Exception $e) {
        	return;
        }
        $this->fail('No Exception thrown on creating non-filterinterface implementing class');
	}
	
}

?>