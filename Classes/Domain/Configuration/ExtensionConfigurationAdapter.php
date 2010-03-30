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
		$queryWhere = $query['where'];
		
		$where = new Tx_PtExtlist_Domain_Configuration_Query_Where();
		
		if(array_key_exists('_typoScriptNodeValue', $queryWhere)){
			$where->setSql($queryWhere['_typoScriptNodeValue']);
		} else {
			$this->buildWhereTree($queryWhere, $where);
		}
		
		return $where;
		
	}
	
	protected function buildWhereTree(array $config, Tx_PtExtlist_Domain_Configuration_Query_Where $where) {
		
		foreach($config as $key => $conf) {
			$key = strtoupper($key);
			
			if($key == 'AND' || ( array_key_exists('_typoScriptNodeValue',$conf) && strtoupper($conf['_typoScriptNodeValue']) == 'AND') ) {
				$where->add( new Tx_PtExtlist_Domain_Configuration_Query_And() );
				$this->buildWhereTree($conf, $where);
				$break;
			} elseif($key == 'OR' || ( array_key_exists('_typoScriptNodeValue',$conf) && strtoupper($conf['_typoScriptNodeValue']) == 'OR') ) {
				$where->add( new Tx_PtExtlist_Domain_Configuration_Query_Or() );
				$this->buildWhereTree($conf, $where);
				break;
			} elseif(is_array($conf)) {
			
				if( array_key_exists('field',$conf) && array_key_exists('value',$conf) ) {
					if( array_key_exists('comparator',$conf) ) {
						$condition = new Tx_PtExtlist_Domain_Configuration_Query_Condition($conf['field'],$conf['value'],$conf['comparator']);
					} else {
						$condition = new Tx_PtExtlist_Domain_Configuration_Query_Condition($conf['field'],$conf['value']);
					}
					
					$where->add($condition);
					
				} else {
					throw new Tx_PtExtlist_Exception_InvalidQueryConfigurationException('A where condition have to have a field and value configuration.');
				}
				
			}
			
		} 
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