<?php
class Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig_testcase extends Tx_Extbase_BaseTestcase {

	/**
	 * Holds a dummy configuration for a column config object
	 * @var array
	 */
	protected $columnSettings = array();
	
	
	/**
	 * Holds an instance of column configuration object
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig
	 */
	protected $columnConfig = null; 
	
	
	/**
	 * @return void
	 * @author Daniel Lienert <lienert@punkt.de>
	 */
	public function setup() {
		$this->columnSettings = array(
		    'fieldIdentifier' => 'testField',
		    'columnIdentifier' => 'testColumn',
		    'label' => 'Test Column'
		);
		
		$this->columnConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->columnSettings);
	}
	
	
	
	public function testGetFieldIdentifier() {
		$this->assertEquals($this->columnConfig->getFieldIdentifier(), $this->columnSettings['fieldIdentifier']);
	}
	
	
	
	public function testGetColumnIdentifier() {
		$this->assertEquals($this->columnConfig->getColumnIdentifier(), $this->columnSettings['columnIdentifier']);
	}
	
	public function testGetLabelWhenLabelGiven() {
		$this->assertEquals($this->columnConfig->getLabel(), $this->columnSettings['label']);
	}
	
	public function testGetLabelWhenLabelNotGiven() {
		unset($this->columnSettings['label']);
		$this->columnConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->columnSettings);
		$this->assertEquals($this->columnConfig->getLabel(), $this->columnSettings['columnIdentifier']);
	}
	
	public function testNoColumnIdentifierGivenException() {
		try {
			new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig(array('fieldIdentifier' => 'test'));
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
	public function testNoFieldIdentifierGivenException() {
		try {
			new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig(array('columnIdentifier' => 'test'));
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
	
}
?>