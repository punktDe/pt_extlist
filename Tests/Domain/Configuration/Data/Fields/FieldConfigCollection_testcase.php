<?php
class Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection_testcase extends Tx_Extbase_BaseTestcase {

	/**
	 * Holds a dummy configuration for a field config collection object
	 * @var array
	 */
	protected $fieldSettings = array();
	
	
	
	public function setup() {
		$this->fieldSettings = array(
		    'field1' => array( 
		        'table' => 'tableName1',
		        'field' => 'fieldName1',
		        'isSortable' => '0',
		        'access' => '1,2,3,4'
		    ),
		    'field2' => array( 
                'table' => 'tableName2',
                'field' => 'fieldName2',
                'isSortable' => '0',
                'access' => '1,2,3,4'
            )
		);
	}
	
	
	
	public function testSetup() {
		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
	}
	
	
	
	public function testExceptionOnNonCorrectItemAdded() {
		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		try {
		    $fieldConfigCollection->addFieldConfig('test');
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
	
	
	public function testExceptionOnGettingNonAddedItem() {
		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
        try {
            $fieldConfigCollection->getFieldConfigByIdentifier('test');
        } catch(Exception $e) {
            return;
        }
        $this->fail();
	}
	
	
	
	public function testAddGetCorrectItems() {
		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		$fieldConfigCollection->addFieldConfig(new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig('field1', $this->fieldSettings['field1']));
		$fieldConfigCollection->addFieldConfig(new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig('field2', $this->fieldSettings['field2']));
		$fieldConfig1 = $fieldConfigCollection->getFieldConfigByIdentifier('field1');
		$this->assertEquals($fieldConfig1->getIdentifier(), 'field1');
		$fieldConfig2 = $fieldConfigCollection->getFieldConfigByIdentifier('field2');
		$this->assertEquals($fieldConfig2->getIdentifier(), 'field2');
	}
	
}
?>