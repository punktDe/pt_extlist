<?php

class Tx_PtExtlist_Domain_Configuration_Query_ConditionNode implements ArrayAccess, Iterator{
	
	protected $nodes;
	protected $currentIndex;
	
	public function __construct() {
		$this->nodes = array();
		$this->currentIndex = 0;
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
	
	public function isValid() {
		return false;
	}
	
	/**
	ArrayAccess implementation
	***************************************************************
	 */
	
	public function offsetExists($offset) {
		return isset($this->nodes[$offset]);
	}
	
	public function offsetGet($offset) {
		return $this->nodes[$offset];
	}
	
	public function offsetSet($offset, $value) {
		return $this->nodes[$offset] = $value;
	}
	
	public function offsetUnset($offset) {
		unset($this->nodes[$offset]);
	}
	
	/**
	Iterator implementation
	***************************************************************
	 */
	
	public function current() {
		return $this->nodes[$this->currentIndex];
	}
	
	public function key() {
		return $this->currentIndex;
	}
	
	public function next() {
		$this->currentIndex++;
	}
	
	public function rewind() {
		$this->currentIndex--;
	}
	
	public function valid() {
		return $this->currentIndex < count($this->nodes);
	}
	
	
}

?>