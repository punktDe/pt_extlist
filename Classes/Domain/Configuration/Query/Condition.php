<?php

class Tx_PtExtlist_Domain_Configuration_Query_Condition extends Tx_PtExtlist_Domain_Configuration_Query_ConditionNode {
	protected $field;
	protected $value;
	protected $comparator;
	
	public function __construct($field, $value, $comparator='eq') {
		$this->field = $field;
		$this->value = $value;
		$this->comparator = $comparator;	
	}
	
	public function getField() {
		return $this->field;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function getComparator() {
		return $this->comparator;
	}
	
	
	
}

?>