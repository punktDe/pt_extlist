<?php

class Tx_PtExtlist_Tests_Domain_Configuration_Filters_Stubs_FilterboxConfigurationCollectionMock extends Tx_Extbase_BaseTestcase {
	
	public function getFilterboxConfigurationCollectionMock() {
		$filterBoxConfigurationCollection = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfigCollection();
		$filterBoxConfigurationCollection->addItem($this->getFilterboxConfigurationMock('filterbox1'));
		$filterBoxConfigurationCollection->addItem($this->getFilterboxConfigurationMock('filterbox2'));
		return $filterBoxConfigurationCollection;
	}
	
	
	
	public function getFilterboxConfigurationMock($filterBoxIdentifier) {
		
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
		
		$mockFilterConfiguration1 = $this->getFilterConfigurationMock('filter1');
		$mockFilterConfiguration2 = $this->getFilterConfigurationMock('filter2');
		
        $filterBoxConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($configurationBuilderMock, $filterBoxIdentifier, array());
        
        $filterBoxConfiguration->addItem($mockFilterConfiguration1);
        $filterBoxConfiguration->addItem($mockFilterConfiguration2);
        
        return $filterBoxConfiguration;
        
	}
	
	
	
	public function getFilterConfigurationMock($filterIdentifier) {
		$mockFilterConfiguration1 = $this->getMock(
            'Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig',
            array('getFilterIdentifier', 'getFilterClassName', 'getListIdentifier'),array(),'',FALSE,FALSE,FALSE);
        
        $mockFilterConfiguration1->expects($this->once())
            ->method('getFilterIdentifier')
            ->will($this->returnValue($filterIdentifier));
        
        $mockFilterConfiguration1->expects($this->once())
            ->method('getFilterClassName')
            ->will($this->returnValue('Tx_PtExtlist_Domain_Model_Filter_FilterStub'));
            
        $mockFilterConfiguration1->expects($this->once())
            ->method('getListIdentifier')
            ->will($this->returnValue('test'));
            
        return $mockFilterConfiguration1;
         
	}
	
}


?>