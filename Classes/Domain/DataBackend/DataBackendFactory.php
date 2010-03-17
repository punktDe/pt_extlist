<?php

class Tx_PtExtlist_Domain_DataBackend_DataBackendFactory {
	
	public static function createDataBackend(Tx_PtExtlist_Domain_Configuration_DataConfiguration &$configuration) {
		
		$backendType = $configuration->getBackendType();
		
		$backend = NULL;
		switch($backendType) {
			case 'mysql':
				$backend = self::createMysqlBackend($configuration);
				break;
				
			default:
				throw new Tx_PtExtlist_Exception_InvalidBackendException('No or invalid backend type configured.');
		}
		
		return $backend;
	}
	
	protected static function createMysqlBackend(Tx_PtExtlist_Domain_Configuration_DataConfiguration &$configuration) {
		$backend = new Tx_PtExtlist_Domain_DataBackend_MySqlBackend_MySqlDataBackend($configuration);
		
		$mapper = new Tx_PtExtlist_Domain_DataBackend_MySqlBackend_Mapper();
		
		$dataSource = new Tx_PtExtlist_Domain_DataBackend_MySqlBackend_DataSource();
		
		$backend->injectMapper($mapper);
		$backend->injectDataSource($dataSource);
		
		return $backend;
	}
	
	
}

?>