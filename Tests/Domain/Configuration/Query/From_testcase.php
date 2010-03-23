<?php

class Tx_PtExtlist_Domain_Configuration_Query_From_testcase extends Tx_Extbase_BaseTestcase {
	
	public function testSetGetTable() {
		$from = new Tx_PtExtlist_Domain_Configuration_Query_From();
		$from->addTable('table','alias');
		
		$tables = $from->getTables();
	
		
		$this->assertTrue( array_key_exists('table',$tables) );
		$this->assertEquals( $tables['table'], 'alias');
		
	}

	public function testSetGetSql() {
		$from = new Tx_PtExtlist_Domain_Configuration_Query_From();
		$from->setSql('SQL STRING');
		
		$this->assertEquals( $from->getSql(), 'SQL STRING' );
		
	}
	
	public function testIsSqlWithTable() {
		$from = new Tx_PtExtlist_Domain_Configuration_Query_From();
		$from->addTable('table','alias');
		
		$this->assertFalse($from->isSql());
	}
	
	public function testIsSqlWithSql() {
		$from = new Tx_PtExtlist_Domain_Configuration_Query_From();
		$from->setSql('SQL STRING');
		
		$this->assertTrue($from->isSql());
	}
	
	public function testTableWithAliasValidation() {
		$from = new Tx_PtExtlist_Domain_Configuration_Query_From();
		$from->addTable('table','alias');
		
		$this->assertTrue( $from->isValid() );
	}
	
	public function testTableValidation() {
		$from = new Tx_PtExtlist_Domain_Configuration_Query_From();
		$from->addTable('table');
		
		$this->assertTrue( $from->isValid() );
	}
	
	public function testEmptyTableValidation() {
		$from = new Tx_PtExtlist_Domain_Configuration_Query_From();
		$from->addTable('');
		
		$this->assertFalse( $from->isValid() );
	}
	
	public function testNoTableOrSqlValidation() {
		$from = new Tx_PtExtlist_Domain_Configuration_Query_From();

		$this->assertFalse( $from->isValid() );
	}
	
	public function testSqlValidation() {
		$from = new Tx_PtExtlist_Domain_Configuration_Query_From();
		$from->setSql('SQL STRING');
		
		$this->assertTrue($from->isValid());
	}
	
	public function testEmptySqlValidation() {
		$from = new Tx_PtExtlist_Domain_Configuration_Query_From();
				
		$this->assertFalse($from->isValid());
	}
}

?>