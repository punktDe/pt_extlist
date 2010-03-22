<?php

class Tx_PtExtlist_Domain_Configuration_DataConfiguration {

	protected $backendType;
	protected $host;
	protected $username;
	protected $password;
	protected $source;
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_QueryConfiguration
	 */
	protected $queryConfiguration;
	
	public function __construct($backendType, $host, $username, $password, $source) {
		$this->backendType = $backendType;
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->source = $source;
	}
	
	public function getBackendType() {
		return $this->backendType;
	}
	
	public function getHost() {
		return $this->host;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function getSource() {
		return $this->source;
	}
	
	public function setQueryConfiguration(Tx_PtExtlist_Domain_Configuration_QueryConfiguration $queryConfiguration) {
		$this->queryConfiguration = $queryConfiguration;
	}
	
	public function getQueryConfiguration() {
		return $this->queryConfiguration;
	}
	
	
}

?>