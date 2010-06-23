<?php

class Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock extends Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder {
	
	public function getBackendConfiguration() {
		return array('dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_DummyDataBackend',
		             'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
		             'dataSourceClass' => 'Tx_PtExtlist_Domain_DataBackend_DataSource_DummyDataSource');
	}
	
    /**
     * Returns a singleton instance of this class
     * @param $settings The current settings for this extension.
     * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder   Singleton instance of this class
     */
    public static function getInstance(array $settings) {
        return new Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock($settings);
    }
	
}

?>