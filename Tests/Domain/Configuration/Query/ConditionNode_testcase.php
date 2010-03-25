<?php

class Tx_PtExtlist_Domain_Configuration_Query_ConditionNode_testcase extends Tx_Extbase_BaseTestcase {
	
	protected $condition;
	
	public function setUp() {
		$this->condition = new Tx_PtExtlist_Domain_Configuration_Query_ConditionNode();
	}
	
	public function testTypes() {
		$this->assertTrue($this->condition instanceof ArrayAccess);
		$this->assertTrue($this->condition instanceof Iterator);
	}
	
	public function testAddNode() {
		$node = new Tx_PtExtlist_Domain_Configuration_Query_Condition();
		
		$this->condition->addNode($node);
		$this->assertEquals($node, $this->condition->current());
	}
	
	public function testIterator() {
		
		$nodes[] = new Tx_PtExtlist_Domain_Configuration_Query_Condition();
		$nodes[] = new Tx_PtExtlist_Domain_Configuration_Query_And();
		$nodes[] = new Tx_PtExtlist_Domain_Configuration_Query_Or();
		$nodes[] = new Tx_PtExtlist_Domain_Configuration_Query_Condition();
		
		$this->condition->addNode($nodes[0]);
		$this->condition->addNode($nodes[1]);
		$this->condition->addNode($nodes[2]);
		$this->condition->addNode($nodes[3]);
		
		$i=0;
		while($this->condition->valid()) {
			$node = $this->condition->current();
			
			$this->assertEquals($node, $nodes[$i]);
			$this->assertEquals($i, $this->condition->key());
			
			$i++;
			$this->condition->next();
		}
	}
	
	public function testArrayAccess() {
		$nodes[] = new Tx_PtExtlist_Domain_Configuration_Query_Condition();
		$nodes[] = new Tx_PtExtlist_Domain_Configuration_Query_And();
		$nodes[] = new Tx_PtExtlist_Domain_Configuration_Query_Or();
		$nodes[] = new Tx_PtExtlist_Domain_Configuration_Query_Condition();
		
		$this->condition->addNode($nodes[0]);
		$this->condition->addNode($nodes[1]);
		$this->condition->addNode($nodes[2]);
		$this->condition->addNode($nodes[3]);
		
		foreach($this->condition as $key => $value) {
			$this->assertEquals($nodes[$key], $value);
		}
		
		
	}
	
}

?>