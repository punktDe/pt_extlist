<?php

class Tx_PtExtlist_Domain_Configuration_QueryConfiguration {
	
	protected $configuration;
	
//	protected $from;
//	protected $join;
//	protected $where;
//	protected $groupBy;
//	protected $limit;
//	protected $orderBy;
	
	public function __construct($configuration) {
		$this->configuration = $configuration;
	}
	
	public function getConfiguration() {
		return $this->configuration;
	}
}

?>