<?php

class Tx_PtExtlist_Domain_Configuration_Query_Where extends Tx_PtExtlist_Domain_Configuration_Query_BiQueryConfigurationPart implements Iterator{
	
	protected $rootConditionNode;
	protected $currentNode;
	
	public function addCondition(Tx_PtExtlist_Domain_Configuration_Query_Condition &$node) {
		if($this->currentNode == NULL)
			$this->currentNode = $node;
		else
			$this->currentNode->addNode($node);
			
		$this->currentIndex++;
	}
	
	public function addOperation(Tx_PtExtlist_Domain_Configuration_Query_Operation &$operand) {
		if($this->currentNode == NULL) {
			$this->currentNode = $operand;
		} else {
			$operand->setLastOperation($this->currentNode);
			$this->currentNode->addNode($operand);
			$this->currentNode = $operand;
		}
	}
	
	public function current() {
		return $this->currentNode;
	}
	
	public function key() {
		return $this->currentIndex;
	}
	
	public function next() {
		
	}
	
	public function rewind() {
		
	}
	
	public function valid() {
		
	}
	
	public function isValid() {
		return false;
	}
	
}
	

?>