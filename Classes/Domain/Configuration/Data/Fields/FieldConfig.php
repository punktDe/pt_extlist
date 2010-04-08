<?php

class Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig {
	
	protected $table;
	
	
	
	protected $field;
	
	
	
	protected $isSortable = 1;
	
	
	
	protected $access = array();
	
	
	
	public function __construct($fieldSettings) {
		tx_pttools_assert::isNotEmptyString($fieldSettings['table']);
		tx_pttools_assert::isNotEmptyString($fieldSettings['field']);
		
		$this->table = $fieldSettings['table'];
		$this->field = $fieldSettings['field'];
		if (array_key_exists('isSortable', $fieldSettings)) {
			$this->isSortable = $fieldSettings['isSortable'];
		}
		
		if (array_key_exists('access', $fieldSettings)) {
			$this->access = explode(',', $fieldSettings['access']);
		}
	}
	
	
	public function getTable() {
		return $this->table;
	}
	
	
	
	public function getField() {
		return $this->field;
	}
	
	
	
	public function getIsSortable() {
		return $this->isSortable;
	}
	
	
	
	public function getAccess() {
		return $this->access;
	}
	
}


?>