<?php

class Tx_PtExtlist_Domain_Configuration_Query_Where_testcase extends Tx_Extbase_BaseTestcase {
	
	protected $where;
	
	
	public function setUp() {
		$this->where = new Tx_PtExtlist_Domain_Configuration_Query_Where();
	}
	
	public function testAddOperation() {
		$where = new Tx_PtExtlist_Domain_Configuration_Query_Where();
		
		$operation = new Tx_PtExtlist_Domain_Configuration_Query_And();
		$where->addOperation($operation);
		
		$current = $where->current();
		
		$this->assertEquals($current, $operation);
	}
	
	public function testAddCondition() {
		$where = new Tx_PtExtlist_Domain_Configuration_Query_Where();
		
		$condition = new Tx_PtExtlist_Domain_Configuration_Query_Condition();
		$where->addCondition($condition);	
	}
	
	
	
}

?>