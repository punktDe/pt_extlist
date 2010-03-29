<?php

abstract class Tx_PtExtlist_Domain_Configuration_Query_Operation extends Tx_PtExtlist_Domain_Configuration_Query_ConditionNode {
		
	protected $operand;
	protected $lastOperation;
	
	public function __construct($operand = 'and') {
		$this->operand = $operand;	
	}

	public function getOperand() {
		return $this->operand;
	}
	
	
}

?>