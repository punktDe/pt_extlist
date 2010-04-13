<?php

/**
 * Testcase for pt_list row object. 
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Model_List_Row_testcase extends Tx_Extbase_BaseTestcase {

	public function testSetUp() {
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
	}
	
	public function testAddCell() {
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		$row->addCell('testKey', 'testContent');
	}
	
	public function testRowContent() {
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
        $row->addCell('testKey', 'testContent');
        foreach($row as $columnName => $cellContent) {
        	$this->assertEquals($columnName, 'testKey');
        	$this->assertEquals($cellContent, 'testContent');
        }
	}
	
	public function testRowArrayAccess() {
        $row = new Tx_PtExtlist_Domain_Model_List_Row();
        $row->addCell('testKey', 'testContent');
		$this->assertEquals($row['testKey'], 'testContent');
	}
	
}

?>