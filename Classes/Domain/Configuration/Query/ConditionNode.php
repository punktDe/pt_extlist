<?php

class Tx_PtExtlist_Domain_Configuration_Query_ConditionNode {
	
	protected $nodes;
	
	public function __construct() {
		$this->nodes = array();
	}
	
	public function addNode(Tx_PtExtlist_Domain_Configuration_Query_ConditionNode &$node) {
		$this->nodes[] = $node;
	}
	
	public function getNodeAt($index) {
		return $this->nodes[$index];
	}
	
	public function setNodeAt($index, Tx_PtExtlist_Domain_Configuration_Query_ConditionNode &$node) {
		$this->nodes[$index] = $node;
	}
	
	public function getNodes() {
		return $this->nodes;
	}
}

?>