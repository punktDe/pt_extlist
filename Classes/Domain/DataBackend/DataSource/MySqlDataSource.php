<?php

class Tx_PtExtlist_Domain_DataBackend_MySqlBackend_DataSource implements Tx_PtExtlist_Domain_DataBackend_DataSourceInterface{

	protected $connection;
	
	
	public function __construct(Tx_PtExtlist_Domain_Configuration_DataConfiguration &$configuration) {
		$this->connection = new mysqli(	$configuration->getHost(),
										$configuration->getUsername(),
										$configuration->getPassword(),
										$configuration->getSource());
	}
	
	public function execute(Tx_PtExtlist_Domain_Query_QueryInterface $query) {
		
	}
	
	
}

?>