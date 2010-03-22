<?php

class Tx_PtExtlist_Domain_Configuration_Query_Select implements Tx_PtExtlist_Domain_Configuration_Query_ValidQueryInterface {
	
	protected $fields;
	
	public function addField($field, $alias) {
		$this->fields[$field] = $alias;
	}
	
	public function setFields(array $fields, array $alias=array()) {
		foreach($fields as $key => $field) {
			$this->fields[$field] = $alias[$key];
		}
	}
	
	public function getFields() {
		return $this->fields;
	}
	
	public function isValid() {
		
		foreach($this->fields as $field => $alias) {
			if($field == '') {
				return false;
			}
		}
		
		return true;
	}
	
}

?>