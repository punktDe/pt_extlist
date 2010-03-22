<?php

class Tx_PtExtlist_Domain_Configuration_Query_From implements Tx_PtExtlist_Domain_Configuration_Query_ValidQueryInterface {
		
	protected $tables;
	protected $sql;
	protected $sqlMode;
	
	public function __construct() {
		$this->sqlMode = false;
	}
	
	public function setSql($fromSql) {
		$this->sql = $fromSql;
		$this->sqlMode = true;
	}
	
	public function isSql() {
		return $this->sqlMode;
	}
	
	public function getSql() {
		return $this->sql;
	}
	
	public function addTable($table, $alias='') {
		$this->tables[$table] = $alias;
	}
	
	public function setTables(array $tables, array $alias=array()) {
		foreach($tables as $key => $table) {
			$this->tables[$table] = $alias[$key];
		}
	}
	
	public function getTables() {
		return $this->tables;
	}
	
	public function isValid() {
		
		if(!$this->sqlMode) {
			
			if(count($this->tables) == 0) return false;
			
			foreach($this->tables as $table => $alias) {
				if($table == '') {
					return false;
				}
			}
		} else {
			if($this->sql == '') return false;
		}
		
		return true;
	}
}

?>