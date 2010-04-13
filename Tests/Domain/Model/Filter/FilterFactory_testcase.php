<?php

require_once t3lib_extMgm::extPath('pt_extlist') . 'Tests/Domain/Model/Filter/Stubs/FilterBoxConfigurationCollectionMock.php';

class Tx_PtExtlist_Tests_Domain_Model_Filter_FilterFactory_testcase extends Tx_Extbase_BaseTestcase {
	
	public function testCreateInstanceByConfiguration() {
		$filterConfigurationMock = new Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterBoxConfigurationCollectionMock();
		$filter = Tx_PtExtlist_Domain_Model_Filter_FilterFactory::createInstanceByFilterConfig($filterConfigurationMock->getFilterConfigurationMock('filter1'));
		$this->assertEquals($filter->getFilterIdentifier(), 'filter1');
		
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