<?php

abstract class Tx_PtExtlist_Domain_Configuration_Query_BiQueryConfigurationPart implements Tx_PtExtlist_Domain_Configuration_Query_ValidQueryInterface {
	protected $sql;
	protected $sqlMode = false;
	
	public function isSql() {
		return $this->sqlMode;
	}
	
	public function getSql() {
		return $this->sql;
	}
	
	public function setSql($sql) {
		$this->sql = $sql;
		$this->sqlMode = true;
	}
}

?>