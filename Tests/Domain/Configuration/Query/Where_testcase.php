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
		
		$conditions = $where->getConditions();
				
		$this->assertTrue( $conditions instanceof Tx_PtExtlist_Domain_Configuration_Query_And);
	}
	
	public function testAddCondition() {
		$where = new Tx_PtExtlist_Domain_Configuration_Query_Where();
		
		$condition = new Tx_PtExtlist_Domain_Configuration_Query_Condition();

		$where->addCondition($condition);	
		
		$conditions = $where->getConditions();
				
		$this->assertTrue($conditions instanceof Tx_PtExtlist_Domain_Configuration_Query_Condition);
	}
	
	public function testSetGetSql() {
		$where = new Tx_PtExtlist_Domain_Configuration_Query_Where();
		$where->setSql("SQL STRING");
		$this->assertEquals( $where->getSql(), 'SQL STRING' );	
	}
	
	public function testIsSqlWithSql() {
		$where = new Tx_PtExtlist_Domain_Configuration_Query_Where();
		$where->setSql("SQL STRING");
		$this->assertTrue($where->isSql());
	}
	
	public function testIsSqlWithTree() {
		$where = new Tx_PtExtlist_Domain_Configuration_Query_Where();
		$operation = new Tx_PtExtlist_Domain_Configuration_Query_And();
		$where->add($operation);
		$this->assertFalse($where->isSql());
	}

	public function testConditionTree() {
		$where = new Tx_PtExtlist_Domain_Configuration_Query_Where();
		
		$operation = new Tx_PtExtlist_Domain_Configuration_Query_And();
		$where->add($operation);
		
		$condition = new Tx_PtExtlist_Domain_Configuration_Query_Condition();
		$where->add($condition);
		
		$operation = new Tx_PtExtlist_Domain_Configuration_Query_Or();
		$where->add($operation);
		
		$condition = new Tx_PtExtlist_Domain_Configuration_Query_Condition();
		$where->add($condition);
		
		$condition = new Tx_PtExtlist_Domain_Configuration_Query_Condition();
		$where->add($condition);
		
		$conditions = $where->getConditions();
		
		$this->assertTrue($conditions instanceof Tx_PtExtlist_Domain_Configuration_Query_And);
		$this->assertTrue($conditions[0] instanceof Tx_PtExtlist_Domain_Configuration_Query_Condition);
		$this->assertTrue($conditions[1] instanceof Tx_PtExtlist_Domain_Configuration_Query_Or);
		
		$conditions = $conditions[1];
		$this->assertTrue($conditions[0] instanceof Tx_PtExtlist_Domain_Configuration_Query_Condition);
		$this->assertTrue($conditions[1] instanceof Tx_PtExtlist_Domain_Configuration_Query_Condition);
	}
	
	
}

?>