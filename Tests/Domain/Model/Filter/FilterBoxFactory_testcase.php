<?php

require_once t3lib_extMgm::extPath('pt_extlist') . 'Tests/Domain/Model/Filter/Stubs/FilterBoxConfigurationCollectionMock.php';

class Tx_PtExtlist_Tests_Domain_Model_Filter_FilterBoxFactory_testcase extends Tx_Extbase_BaseTestcase {
	
	public function testCreateInstanceByFilterBoxConfiguration() {
		$filterBoxConfigurationMock = new Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterBoxConfigurationCollectionMock();
		$filterBoxConfiguration = $filterBoxConfigurationMock->getFilterboxConfigurationMock('filterbox1');
        
        $filterBox = Tx_PtExtlist_Domain_Model_Filter_FilterBoxFactory::createFilterBoxByFilterBoxConfiguration($filterBoxConfiguration);
	}
	
}

?>