<?php

class Tx_PtExtlist_Domain_DataBackend_DataBackendFactory {
	
	public static function createDataBackend(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		// TODO finish this!		
		//$dataBackendSettings = $configurationBuilder->getBackendConfiguration();
		//tx_pttools_assert::isNotEmptyString($dataBackendSettings['dataBackendClass']);	
		//$dataBackendClassName = $dataBackendSettings['dataBackendClass'];
		
		// TODO remove this after testing!
		return new Tx_PtExtlist_Domain_DataBackend_DummyDataBackend_DummyDataBackend($configurationBuilder);
	}
	
}

?>