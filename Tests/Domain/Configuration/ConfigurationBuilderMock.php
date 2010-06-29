<?php

class Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock extends Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder {
	
    public function setup() {
        $this->origSettings = array(
            'listIdentifier' => 'test',
            'abc' => '1',
            'listConfig' => array(
                 'test' => array(
                     'abc' => '2',
                     'def' => '3',
                     'fields' => array(
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
                    ),
                    'columns' => array(
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
                    ),
                    'filters' => array(
                         'testfilterbox' => array(
                             'testkey' => 'testvalue'
                         )
                    )
                )
            )
        );
        $this->settings = $this->origSettings['listConfig']['test'];
    }
	
	
	public function getBackendConfiguration() {
		return array('dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_DummyDataBackend',
		             'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
		             'dataSourceClass' => 'Tx_PtExtlist_Domain_DataBackend_DataSource_DummyDataSource');
	}
	
    /**
     * Returns a singleton instance of this class
     * @param $settings The current settings for this extension.
     * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder   Singleton instance of this class
     */
    public static function getInstance($settings = null) {
    	if ($settings != null) {
    		$configurationBuilderMock = new Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock($settings);
    	} else {
            $configurationBuilderMock = new Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock(array('listIdentifier' => 'test'));
            $configurationBuilderMock->setup();
    	}
    	return $configurationBuilderMock;
    }
	
}

?>