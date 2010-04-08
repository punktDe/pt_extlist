<?php
class Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection_testcase extends Tx_Extbase_BaseTestcase {

	/**
	 * Holds a dummy configuration for a column config collection object
	 * @var array
	 */
	protected $columnSettings = array();
	
	
	
	public function setup() {
		$this->columnSettings = array(
		    10 => array( 
		        'columnIdentifier' => 'column1',
		        'fieldIdentifier' => 'field1',
		        'label' => 'Column 1'
		    ),
		    20 => array( 
		        'columnIdentifier' => 'column2',
		        'fieldIdentifier' => 'field2',
		        'label' => 'Column 2'
		    )
		 );
	}
		
	public function testExceptionOnNonCorrectItemAdded() {
		$columnConfigCollection = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection($this->columnSettings);
		try {
		    $columnConfigCollection->addColumnConfig('test');
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
	
	
	public function testExceptionOnGettingNonAddedItem() {
		$columnConfigCollection = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection($this->columnSettings);
        try {
            $columnConfigCollection->getColumnConfigByIdentifier(30);
        } catch(Exception $e) {
            return;
        }
        $this->fail();
	}
	
	
	
	public function testAddGetCorrectItems() {
		$columnConfigCollection = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection();
		$columnConfigCollection->addColumnConfig(10,new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->columnSettings[10]));
		$columnConfigCollection->addColumnConfig(20,new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->columnSettings[20]));
		$columnConfig10 = $columnConfigCollection->getColumnConfigByIdentifier(10);
		$this->assertEquals($columnConfig10->getColumnIdentifier(), 'column1');
		$columnConfig20 = $columnConfigCollection->getColumnConfigByIdentifier(20);
		$this->assertEquals($columnConfig20->getColumnIdentifier(), 'column2');
	}
	
}
?>