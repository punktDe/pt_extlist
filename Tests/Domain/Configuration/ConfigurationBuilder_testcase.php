<?php
class Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilder_testcase extends Tx_Extbase_BaseTestcase {
	
	protected $settings = array();
	
	
	
	public function setup() {
		$this->settings = array(
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
			        )
		        )
		    )
		);
	}
	
	
	
	public function testSetup() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
	}
	
	

	public function testNoListConfigException() {
		try {
		  $configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance(array());
		} catch(Exception $e) {
			return;
		}
		$this->fail('No Exceptions has been raised for misconfiguration');
	}

	
	
	public function testSetAndMergeGlobalAndLocalConfig() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		$settings = $configurationBuilder->getSettings();
		$this->assertEquals($settings['abc'], 2);
		$this->assertEquals($settings['def'], 3);
	}
	
	
	
	public function testGetListIdentifier() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		$this->assertEquals($configurationBuilder->getListIdentifier(), $this->settings['listIdentifier']);
	}
	
	
	
	public function testBuildFieldsConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		$fieldConfigCollection = $configurationBuilder->buildFieldsConfiguration();
	}
	
	
	public function testBuildColumnsConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		$columnConfigCollection = $configurationBuilder->buildColumnsConfiguration();
	}
	
}
?>