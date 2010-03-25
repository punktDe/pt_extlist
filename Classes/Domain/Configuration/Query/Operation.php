<?php

abstract class Tx_PtExtlist_Domain_Configuration_Query_Operation extends Tx_PtExtlist_Domain_Configuration_Query_ConditionNode {
		
	protected $operand;
	protected $lastOperation;
	
	public function __construct($lastOperation, $operand = 'and') {
		$this->operand = $operand;
		
	}
	
	public function setLastOperation($operation) {
		$this->lastOperation = $operation;
	}
	
	
	public function getOperand() {
		return $this->operand;
	}
	
	
}

?>