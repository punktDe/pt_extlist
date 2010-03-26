<?php

class Tx_PtExtlist_Domain_Configuration_Query_Join_testcase extends Tx_Extbase_BaseTestcase {
	
	protected $join;
	
	public function setUp() {
		$this->join = new Tx_PtExtlist_Domain_Configuration_Query_Join();
	}
	
	public function testSetGetTable() {
		
		$this->join->addTable('table','alias','onField','onValue');
		
		$tables = $this->join->getTables();
	
		
		$this->assertTrue( array_key_exists('table', $tables) );
		$this->assertEquals( $tables['table']['alias'], 'alias' );
		$this->assertEquals( $tables['table']['onField'], 'onField' );
		$this->assertEquals( $tables['table']['onValue'], 'onValue' );
		
	}

	public function testSetGetSql() {
		
		$this->join->setSql('SQL STRING');
		
		$this->assertEquals( $this->join->getSql(), 'SQL STRING' );
		
	}
	
	public function testIsSqlWithTable() {
		
		$this->join->addTable('table','alias', 'onField', 'onValue');
		
		$this->assertFalse($this->join->isSql());
	}
	
	public function testIsSqlWithSql() {
		
		$this->join->setSql('SQL STRING');
		
		$this->assertTrue($this->join->isSql());
	}
	
	public function testTableWithAllOnValuesValidation() {
		
		$this->join->addTable('table','alias', 'onField', 'onValue');
		
		$this->assertTrue( $this->join->isValid() );
	}
	
	public function testTableOnlyOnFieldValidation() {
		
		$this->join->addTable('table','alias','onField');
		
		$this->assertFalse( $this->join->isValid() );
	}
	
	public function testEmptyTableValidation() {

		$this->join->addTable('');
		
		$this->assertFalse( $this->join->isValid() );
	}
	
	public function testNoTableOrSqlValidation() {

		$this->assertFalse( $this->join->isValid() );
	}
	
	public function testSqlValidation() {

		$this->join->setSql('SQL STRING');
		
		$this->assertTrue($this->join->isValid());
	}
	
	public function testEmptySqlValidation() {
				
		$this->assertFalse($this->join->isValid());
	}
	
}

?>