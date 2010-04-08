<?php

class Tx_PtExtlist_Domain_DataBackend_DataBackendFactory {
	
	public static function createDataBackend(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$dataBackendSettings = $configurationBuilder->getBackendConfiguration();
		tx_pttools_assert::isNotEmptyString($dataBackendSettings['dataBackendClass']);	
		$dataBackendClassName = $dataBackendSettings['dataBackendClass'];
		
		// Check whether backend class exists
		if (!class_exists($dataBackendClassName)) {
			throw new Exception('Data Backend class ' . $dataBackendClassName . ' does not exist!');
		}
		$dataBackend = new $dataBackendClassName($configurationBuilder);
		
		// Check whether backend class implements abstract backend class
		if (!($dataBackend instanceof Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend)) {
			throw new Exception('Data Backend class ' . $dataBackendClassName . ' does not implement Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend');
		}
		
		return $dataBackend;
	}
	
}

?>