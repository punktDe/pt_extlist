<?php

class Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter {

	/**
	 * @var Array $configuration
	 */
	protected $configuration;
	
	public function __construct($configuration) {
		$this->configuration = $configuration;
	}
	
	public function setConfiguration($configuration) {
		$this->configuration = $configuration;
	}

	public function getSelectQueryConfiguration($listIdentifier) {
		$query = $this->getQueryConfigurationRoot($listIdentifier);
		
		
		$select = new Tx_PtExtlist_Domain_Configuration_Query_Select();
		$querySelect = $query['mapping'];
		foreach($querySelect as $property => $field) {
			$select->addField($field);
		}
		return $select;
	}
	
	public function getFromQueryConfiguration($listIdentifier) {
		$query = $this->getQueryConfigurationRoot($listIdentifier);
		
		$from = new Tx_PtExtlist_Domain_Configuration_Query_From();
		$queryFrom = $query['from'];

		if( array_key_exists('_typoScriptNodeValue', $queryFrom) ) {
		
			$from->setSql($queryFrom['_typoScriptNodeValue']);
			
		} else {
	
			foreach($queryFrom as $key => $tableConfig) {
				$from->addTable($tableConfig['table'], $tableConfig['alias']);
			}
		}
		
		return $from;
	}
	
	public function getJoinQueryConfiguration($listIdentifier) {
		$query = $this->getQueryConfigurationRoot($listIdentifier);
		
		$join = new Tx_PtExtlist_Domain_Configuration_Query_Join();
		$queryJoin = $query['join'];
		
		if( array_key_exists('_typoScriptNodeValue',$queryJoin) ) {
			$join->setSql($queryJoin['_typoScriptNodeValue']);
		} else {
			foreach($queryJoin as $key => $table) {
				$join->addTable($table['table'], $table['alias'], $table['on']['field'], $table['on']['value']);
			}
		}
		return $join;
	}
	
	public function getWhereQueryConfiguration($listIdentifier) {
		$query = $this->getQueryConfigurationRoot($listIdentifier);
		
		
		
	}
	
	public function getDataConfiguration($listIdentifier) {
		$data = $this->getDataConfigurationRoot($listIdentifier);

		$backendType = $data['backend'];
		$host = $data['datasource']['host'];
		$username = $data['datasource']['username'];
		$password = $data['datasource']['password'];
		$source = $data['datasource']['database'];
					
		$dataConfiguration = new Tx_PtExtlist_Domain_Configuration_DataConfiguration($backendType, $host, $username, $password, $source);

		return $dataConfiguration;
	}
	
	
	
	
	
	protected function getDataConfigurationRoot($listIdentifier) {
		return $this->configuration['listConfig'][$listIdentifier]['data'];
	}
	
	protected function getQueryConfigurationRoot($listIdentifier) {
		$data = $this->getDataConfigurationRoot($listIdentifier);
		return $data['query'];
	}
	
	
	
	
	
}

?>