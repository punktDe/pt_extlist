<?php

class Tx_PtExtlist_Configuration_ConfigurationBuilder {

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
	 * 
	 * @return Tx_PtExtlist_Configuration_ConfigurationBuilder   Singleton instance of this class
	 */
	public static function getInstance() {
		if (self::instance === null) {
			self::initInstance();
		}
		return self::instance;
	}

	
	
	protected __construct() {
		// use getInstance!
	}
	
	
	/**
	 * Initialize configuration builder
	 */
	protected static function initInstance() {
		$this->sessionAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_SessionAdapter');
		$this->getPostVarAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_GetPostVarAdapter');
		$this->extensionConfigurationAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter');
	}
	
	
	
	/**
	 * @return Tx_PtExtlist_Configuration_MapperConfiguration
	 */
	public function getMapperConfiguratione() {
        $mapperConfiguration = new Tx_PtExtlist_Configuration_MapperConfiguration();
		return $mapperConfiguration;	
	}
	
	
	/**
	 * @return Tx_PtExtlist_Configuration_RendererConfiguration
	 */
	public function getRendererConfiguration() {
		$rendererConfiguration = new Tx_PtExtlist_Configuration_RendererConfiguration();
		return $rendererConfiguration;
	}
	
	
	/**
	 * @return Tx_PtExtlist_Configuration_DataConfiguration
	 */
	public function getDataConfiguration() {
		$dataConfiguration = new Tx_PtExtlist_Configuration_DataConfiguration();
		return $dataConfiguration;
	}
	
	
	/**
	 * 
	 */
	public function getFilterConfiguration() {
	}
}

?>