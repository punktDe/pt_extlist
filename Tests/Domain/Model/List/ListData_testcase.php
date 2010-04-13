<?php

/**
 * Testcase for list data class
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_Model_List_ListData_testcase extends Tx_Extbase_BaseTestcase {
	
	public function testSetup() {
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
	}
	
	public function testAddRow() {
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		$listData->addRow($row);
	}
	
}

?>