<?php
class Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig_testcase extends Tx_Extbase_BaseTestcase {

	/**
	 * Holds a dummy configuration for a field config object
	 * @var array
	 */
	protected $fieldSettings = array();
	
	
	/**
	 * Holds an instance of field configuration object
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	protected $fieldConfig = null; 
	
	
	
	public function setup() {
		$this->fieldSettings = array(
		    'table' => 'tableName',
		    'field' => 'fieldName',
		    'isSortable' => '0',
		    'access' => '1,2,3,4'
		);
		$this->fieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->fieldSettings);
	}
	
	
	
	public function testSetup() {
		$fieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->fieldSettings);
	}
	
	
	
	public function testGetTable() {
		$this->assertEquals($this->fieldConfig->getTable(), $this->fieldSettings['table']);
	}
	
	
	
	public function testGetField() {
		$this->assertEquals($this->fieldConfig->getField(), $this->fieldSettings['field']);
	}
	
	
	
	public function testGetIsSortable() {
		$this->assertEquals($this->fieldConfig->getIsSortable(), $this->fieldSettings['isSortable']);
	}
	
	
	
	public function testDefaultGetIsSortable() {
		$newFieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig(array('table' => '1', 'field' => '2'));
		$this->assertEquals($newFieldConfig->getIsSortable(), 1);
	}
	
	
	
	public function testGetAccess() {
		$accessArray = $this->fieldConfig->getAccess();
		$this->assertTrue(in_array('1', $accessArray));
		$this->assertTrue(in_array('2', $accessArray));
		$this->assertTrue(in_array('3', $accessArray));
		$this->assertTrue(in_array('4', $accessArray));
	}
	
	
	
	public function testNoTableNameGivenException() {
		try {
			new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig(array('field' => '2'));
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
	
	
    public function testNoFieldNameGivenException() {
        try {
            new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig(array('table' => '2'));
        } catch(Exception $e) {
            return;
        }
        $this->fail();
    }
	
	
}
?>