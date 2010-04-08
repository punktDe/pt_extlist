<?php

class Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder {

	/**
	 * Holds an associative array of instances of configuration builder objects
	 * Each list identifier holds its own configuration builder object
	 * @var array<Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder>
	 */
	private static $instances = null;

	
	
	/**
	 * Holds an instance of a session adapter
	 * @var Tx_PtExtlist_Domain_Configuration_SessionAdapter
	 */
	protected $sessionAdapter;
	
	
	
	/**
	 * Merged settings of global and local configuration
	 * @var array
	 */
	protected $settings;
	
	
	
	/**
	 * Non-merged settings of plugin
	 * @var array
	 */
	private $origSettings;
	
	
	
	/**
	 * Holds list identifier of current list
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * Holds an instance of getPostVar adapter
	 * @var Tx_PtExtlist_Domain_Configuration_GetPostVarAdapter
	 */
	protected $getPostVarAdapter;
	
	
	
	/**
	 * Holds an instance of extension configuration adapter
	 * @var Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter
	 */
	protected $extensionConfigurationAdapter; 
	
	
	
	/**
	 * Returns a singleton instance of this class
	 * @param $settings The current settings for this extension.
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder   Singleton instance of this class
	 */
	public static function getInstance(array $settings) {
	    if ($settings['listIdentifier'] != '') {
            if (!array_key_exists($settings['listIdentifier'],self::$instances)) {
            	self::$instances[$settings['listIdentifier']] = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($settings);
            }
        } else {
            throw new Exception('No list identifier set!');
        }
        return self::$instances[$settings['listIdentifier']];
	}

	
	
	/**
	 * Constructor is private, use getInstance instead!
	 * 
	 * @param array $settings  Settings of extension
	 */
	protected function __construct(array $settings) {
		$this->setListIdentifier($settings);
		$this->origSettings = $settings;
		$this->mergeAndSetGlobalAndLocalConf();
		
		$this->sessionAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_SessionAdapter');
		$this->getPostVarAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_GetPostVarAdapter');
		$this->extensionConfigurationAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter', $settings);
	}
	
	
	
	protected function setListIdentifier($settings) {
	    if ($settings['listIdentifier'] != '') {
            $this->listIdentifier = $settings['listIdentifier'];    
        } else {
            throw new Exception('No list identifier set!');
        }
	}
	
	
	
	protected function mergeAndSetGlobalAndLocalConf() {
		$mergedSettings = t3lib_div::array_merge_recursive_overrule(
            $this->origSettings,
            $this->origSettings['listConfig'][$this->listIdentifier]
        );
        $this->settings = $mergedSettings['listConfig'][$this->listIdentifier];
	}

	
	
	/**
	 * @return Tx_PtExtlist_Domain_Configuration_MapperConfiguration
	 */
	public function buildMapperConfiguration() {
        $mapperConfiguration = new Tx_PtExtlist_Domain_Configuration_MapperConfiguration();
		return $mapperConfiguration;	
	}
	
	
	
	/**
	 * @return Tx_PtExtlist_Domain_Configuration_RendererConfiguration
	 */
	public function buildRendererConfiguration() {
		$rendererConfiguration = new Tx_PtExtlist_Domain_Configuration_RendererConfiguration();
		return $rendererConfiguration;
	}
	
	
	
	/**
	 * @return Tx_PtExtlist_Domain_Configuration_DataConfiguration
	 */
	public function buildDataConfiguration($listIdentifier) {
		
//		$select = $this->extensionConfigurationAdapter->getSelectQueryConfiguration($listIdentifier);
//		$from = $this->extensionConfigurationAdapter->getFromQueryConfiguration($listIdentifier);
//		$join = $this->extensionConfigurationAdapter->getJoinQueryConfiguration($listIdentifier);
//		
//		$queryConfiguration = new Tx_PtExtlist_Domain_Configuration_QueryConfiguration($select, $from);
//		$queryConfiguration->setJoin($join);
//
//		$dataConfiguration = $this->extensionConfigurationAdapter->getDataConfiguration($listIdentifier);
//		$dataConfiguration->setQueryConfiguration($queryConfiguration);
//		
//		return $dataConfiguration;
	}
	
	
	
	/**
	 * 
	 */
	public function buildFilterConfiguration() {
	}
	
	
	
	/**
	 * Returns array of settings for current plugin configuration
	 *
	 * @return array
	 * @author Michael Knoll <knoll@punkt.de>
	 */
	public function getSettings() {
		return $this->settings;
	}
	
	
	
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
}

?>