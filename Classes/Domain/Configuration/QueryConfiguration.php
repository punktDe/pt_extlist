<?php

class Tx_PtExtlist_Domain_Configuration_QueryConfiguration {
	
	protected $configuration;
	
	public function __construct($configuration) {
		$this->configuration = $configuration;
	}
	
	public function getConfiguration() {
		return $this->configuration;
	}
}

?>