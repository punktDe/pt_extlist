<?php

class Tx_PtExtlist_Domain_Configuration_Query_From implements Tx_PtExtlist_Domain_Configuration_Query_ValidQueryInterface {
	
	protected $tables;
	
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
		
		foreach($this->tables as $table => $alias) {
			if($table == '') {
				return false;
			}
		}
		
		return true;
	}
}

?>