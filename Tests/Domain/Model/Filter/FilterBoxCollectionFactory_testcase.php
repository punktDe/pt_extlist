<?php

require_once t3lib_extMgm::extPath('pt_extlist') . 'Tests/Domain/Model/Filter/Stubs/FilterBoxConfigurationCollectionMock.php';

class Tx_PtExtlist_Tests_Domain_Model_Filter_FilterBoxCollectionFactory_testcase extends Tx_Extbase_BaseTestcase {
    
    public function testCreateInstanceByFilterBoxConfigurationCollection() {
        $filterBoxConfigurationMock = new Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterBoxConfigurationCollectionMock();
        $filterBoxConfigurationCollection = $filterBoxConfigurationMock->getFilterBoxConfigurationCollectionMock();
        $filterBoxCollection = Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollectionFactory::createInstanceByFilterBoxConfigCollection($filterBoxConfigurationCollection); 
    }
    
}

?>