<?php

class Tx_PtExtlist_Domain_Configuration_Query_Join extends Tx_PtExtlist_Domain_Configuration_Query_BiQueryConfigurationPart {
	
	protected $tables;
	
	
	public function addTable($table, $alias, $onField, $onValue) {
		$this->tables[$table]['alias'] = $alias;
		$this->tables[$table]['onField'] = $onField;
		$this->tables[$table]['onValue'] = $onValue;
	}
	
	public function getTables() {
		return $this->tables;	
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Query/Tx_PtExtlist_Domain_Configuration_Query_ValidQueryInterface#isValid()
	 */
	public function isValid() {
		
		if(!$this->sqlMode) {
			
			if(empty($this->tables)) return false;
			
			foreach($this->tables as $table => $values) {
				if(empty($table) || empty($values['onField']) || empty($values['onValue'])) {
					return false;
				}
			}
		} else {
			if(empty($this->sql)) return false;
		}
		
		return true;
		
	}
}

?>