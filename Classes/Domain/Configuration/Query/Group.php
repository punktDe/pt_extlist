<?php

class Tx_PtExtlist_Domain_Configuration_Query_Group extends Tx_PtExtlist_Domain_Configuration_Query_BiQueryConfigurationPart {
	protected $tables;
	
	public function __construct($tables=null) {
		$this->tables = $tables;
	}
	
	public function addTable($table, $field) {
		assert($field != '');
		
//		if($field == '') {
//			throw new Tx_PtExtlist_Exception_InvalidQueryConfigurationException();
//		}
		
		$this->tables[$table]['field'] = $field;
		
	}
	
	public function getTables() {
		return $this->tables;
	}
	
	public function isValid() {
		
		if($this->isSql()) {
			return false;
		} else {
			if (count($this->tables) == 0) return false;
			
			foreach($this->tables as $table => $field) {
				if(strlen(trim($field['field'])) <= 0) {
					return false;
				}
			}
		}
		
		return true;
		
	}
}

?>