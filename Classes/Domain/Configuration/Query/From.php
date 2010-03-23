<?php

class Tx_PtExtlist_Domain_Configuration_Query_From  extends Tx_PtExtlist_Domain_Configuration_Query_BiQueryConfigurationPart {
		
	protected $tables;
	
	
	public function addTable($table, $alias='') {
		$this->tables[$table] = $alias;
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