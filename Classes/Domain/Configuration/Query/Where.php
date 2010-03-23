<?php

class Tx_PtExtlist_Domain_Configuration_Query_Where extends Tx_PtExtlist_Domain_Configuration_Query_BiQueryConfigurationPart {
	
	protected $rootConditionNode;
	protected $currentNode;
	
	public function addCondition(Tx_PtExtlist_Domain_Configuration_Query_Condition &$node) {
		$this->currentNode->addNode($node);
	}
	
	public function addOperation(Tx_PtExtlist_Domain_Configuration_Query_Operation &$operand) {
		$this->currentNode->addNode($operand);
		$this->currentNode = $operand;
	}
	
	public function isValid() {
		return false;
	}
	
}
	

?>