<?php

class Tx_PtExtlist_Domain_Configuration_Query_Where extends Tx_PtExtlist_Domain_Configuration_Query_BiQueryConfigurationPart {
	
	protected $rootConditionNode;
	protected $currentNode;
	
	public function add(Tx_PtExtlist_Domain_Configuration_Query_ConditionNode &$node) {
		if($node instanceof Tx_PtExtlist_Domain_Configuration_Query_Condition) {
			$this->addCondition($node);
		} else {
			$this->addOperation($node);
		}

	}
	
	public function addCondition(Tx_PtExtlist_Domain_Configuration_Query_Condition $node) {
		if($this->currentNode == NULL) {
			
			$this->currentNode = $node;
			$this->rootConditionNode = &$node;
		} else {
			$this->currentNode->addNode($node);			
		}

	}
	
	public function addOperation(Tx_PtExtlist_Domain_Configuration_Query_Operation $operand) {
		if($this->currentNode == NULL) {
			$this->currentNode = $operand;
			$this->rootConditionNode = &$operand;
		} else {
			
			$this->currentNode->addNode($operand);
			$this->currentNode = $operand;
		}
		
	}
	
	public function getConditions() {
		return $this->rootConditionNode;
	}
	
	public function isValid() {
		return false;
	}
	
}
	

?>