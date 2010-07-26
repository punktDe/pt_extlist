<?php
class Tx_PtExtlist_Tests_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter'));
	}
	
	
	
	public function testInterpret() {
		$query = new Tx_PtExtlist_Domain_QueryObject_Query();
		$interpreter = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter();
		$this->assertTrue(method_exists($interpreter, 'interpretQuery'));
		$result = $interpreter->interpretQuery($query);
	}
	
	
}
?>