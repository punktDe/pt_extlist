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
		             'def' => '3'
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
	
}
?>