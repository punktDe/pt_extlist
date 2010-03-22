<?php

class Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder {

	/**
	 * Holds a singleton instance of configuration builder object
	 *
	 * @var Tx_PtExtlist_Configuration_ConfigurationBuilder
	 */
	private static $instance = null;

	protected $sessionAdapter;
	protected $getPostVarAdapter;
	protected $extensionConfigurationAdapter; 
	
	
	
	/**
	 * Returns a singleton instance of this class
	 * @param $settings The current settings for this extension.
	 * @return Tx_PtExtlist_Configuration_ConfigurationBuilder   Singleton instance of this class
	 */
	public static function getInstance(array $settings) {
		if (self::$instance === null) {
			self::$instance = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($settings);
		}
		return self::$instance;
	}

	
	
	protected function __construct(array $settings) {
		// use getInstance!
		
		$this->sessionAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_SessionAdapter');
		$this->getPostVarAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_GetPostVarAdapter');
		$this->extensionConfigurationAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter', $settings);
		
	}

	public function setSettings(array $settings) {
		$this->extensionConfigurationAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter', $settings);
	}
	
	/**
	 * @return Tx_PtExtlist_Configuration_MapperConfiguration
	 */
	public function buildMapperConfiguratione() {
        $mapperConfiguration = new Tx_PtExtlist_Domain_Configuration_MapperConfiguration();
		return $mapperConfiguration;	
	}
	
	
	/**
	 * @return Tx_PtExtlist_Configuration_RendererConfiguration
	 */
	public function buildRendererConfiguration() {
		$rendererConfiguration = new Tx_PtExtlist_Domain_Configuration_RendererConfiguration();
		return $rendererConfiguration;
	}
	
	
	/**
	 * @return Tx_PtExtlist_Configuration_DataConfiguration
	 */
	public function buildDataConfiguration($listIdentifier) {
		
		$root = $this->extensionConfigurationAdapter->getDataConfigurationRoot($listIdentifier);

		$backendType = $root['backend'];
		$host = $root['datasource']['host'];
		$username = $root['datasource']['username'];
		$password = $root['datasource']['password'];
		$source = $root['datasource']['database'];
		
		$query = $root['query'];
		
		$select = $this->createSelectQueryConfiguration($query);
		$from = $this->createFromQueryConfiguration($query);
		$join = $this->createJoinQueryConfiguration($query);
		$queryConfiguration = new Tx_PtExtlist_Domain_Configuration_QueryConfiguration($select, $from);
		
		$queryConfiguration->setJoin($join);
		
		
		$dataConfiguration = new Tx_PtExtlist_Domain_Configuration_DataConfiguration($backendType, $host, $username, $password, $source);
		$dataConfiguration->setQueryConfiguration($queryConfiguration);
		
		return $dataConfiguration;
	}
	
	protected function createSelectQueryConfiguration(array &$query) {
		$select = new Tx_PtExtlist_Domain_Configuration_Query_Select();
		$querySelect = $query['mapping'];
		foreach($querySelect as $property => $field) {
			$select->addField($field);
		}
		return $select;
	}
	
	protected function createFromQueryConfiguration(array &$query) {
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
	
	protected function createJoinQueryConfiguration(array &$query) {
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
	
	
	/**
	 * 
	 */
	public function buildFilterConfiguration() {
	}
}

?>