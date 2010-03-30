<?php

require_once(t3lib_extMgm::extPath('pt_extlist') . 'Tests/ActiveAssertion.php');

class Tx_PtExtlist_Domain_Configuration_Query_Group_testcase extends Tx_Extbase_BaseTestcase {

	protected $group;
	
	public function setUp() {
		$this->group = new Tx_PtExtlist_Domain_Configuration_Query_Group();
	}
	
	public function testSetGetTable() {
		$this->group->addTable('table','field');
		$tables = $this->group->getTables();
		$this->assertTrue(array_key_exists('table',$tables));
		$this->assertEquals($tables['table']['field'], 'field');
	}
	
	public function testSetGetSql() {
		$this->group->setSql('SQL STRING');
		$this->assertEquals( $this->group->getSql(), 'SQL STRING' );
	}
	
	public function testIsSqlWithTable() {
		$this->group->addTable('table','field');
		$this->assertFalse($this->group->isSql());
	}
	
	public function testIsSqlWithSql() {
		$this->group->setSql('SQL STRING');
		$this->assertTrue($this->group->isSql());
	}
	
	public function testIsValidTable() {
		$this->group->addTable('table','field');
		$this->assertTrue($this->group->isValid());
	}
	
	
	public function testIsNotValidTable() {
		$this->group->addTable('table','');
		Tx_PtExtlist_ActiveAssertion::assertFailedAssertion('Tx_PtExtlist_Domain_Configuration_Query_Group','addTable');
		$this->assertFalse($this->group->isValid());
	}
	

}
?>