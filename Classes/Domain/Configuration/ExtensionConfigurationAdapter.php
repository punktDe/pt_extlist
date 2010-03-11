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
	
	public function getConfiguration() {
		return $this->configuration;
	}
	
	public function getDataConfigurationRoot($listIdentifier) {
		return $this->configuration['listConfig'][$listIdentifier]['data'];
	}
	
}

?>