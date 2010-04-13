<?php

class Tx_PtExtlist_Tests_Domain_Configuration_Filters_FilterConfig_testcase extends Tx_Extbase_BaseTestcase {
	
	protected $filterSettings = array();
	
	
	public function setup() {
		$this->filterSettings = array(
		    'filterIdentifier' => 'filterName1'
		);
	}
	
	
	
	public function testSetup() {
		$filterConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->filterSettings);
	}
	
	
	
	public function testExceptionOnEmptyFilterIdentifier() {
		try {
		    $filterConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(array());
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
}

?>