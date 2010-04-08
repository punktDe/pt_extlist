<?php
class Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollectionFactory_testcase extends Tx_Extbase_BaseTestcase {

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
	
	
	
	public function testGetFieldConfigCollection() {
		$fieldConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollectionFactory::getFieldConfigCollection($this->fieldSettings);
		$this->assertTrue(is_a($fieldConfigCollection, 'tx_pttools_objectCollection'));
	}
			
}
?>