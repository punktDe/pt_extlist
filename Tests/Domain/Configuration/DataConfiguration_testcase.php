<?php

class Tx_PtExtlist_Domain_Configuration_DataConfiguration_testcase extends Tx_Extbase_BaseTestcase {
	
	protected $settingsFixture;
	protected $configurationBuilder;
	
	public function setUp() {
		$this->settingsFixture = array ( 'listConfig' => array ( 'test' => array (
				'data' => array(
					'backend' => 'mysql',
					'datasource' => array (
						'host' => 'localhost',
						'username' => 'username',
						'password' => 'password',
						'database' => 'database'
					),
					
					'query' => array (
						'from' => array (
							'10' => 'table1',
							'20' => 'table2',
							'_typoScriptNodeValue' => 'table1, table2'
						),
						
						'join' => array (
							'10' => array (
								'table' => 'table',
								'alias' => 'alias',
								'_typoScriptNodeValue' => 'INNER',
								'on' => array (
									'field' => 'field',
									'value' => 'value'
								)
							),
							'_typoScriptNodeValue' => 'ON table1.id = table2.id'
						),
						
						'where' => array (
							'and' => array (
								'10' => array (
									'field' => 'A',
									'value' => 'AA',
									'comparator' => 'lt'
								),
								'20' => array (
									'_typoScriptNodeValue' => 'OR',
									'10' => array (
										'field' => 'B',
										'value' => 'BB',
									),
									'20' => array ( 
										'field' => 'C',
										'value' => 'CC',
										'comparator' => 'eq',
									)
								)
							),
							'_typoScriptNodeValue' => 'A < AA AND (B = BB OR C = CC)',
						)
					)
				)
				)
				)
		);
		
		$this->configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		
	}
	
	public function testDataConfigurationBuilder() {
		$dataConfiguration = $this->configurationBuilder->buildDataConfiguration('test');
		
		$this->assertTrue( $dataConfiguration instanceof Tx_PtExtlist_Domain_Configuration_DataConfiguration);
	}
	
	public function testDataConfigurationSetup() {
		$dataConfiguration = $this->configurationBuilder->buildDataConfiguration('test');
		
		$backendType = $dataConfiguration->getBackendType();
		$host = $dataConfiguration->getHost();
		$username = $dataConfiguration->getUsername();
		$password = $dataConfiguration->getPassword();
		$source = $dataConfiguration->getSource();
		
		$this->assertEquals($backendType, 'mysql');
		$this->assertEquals($host, 'localhost');
		$this->assertEquals($username, 'username');
		$this->assertEquals($password, 'password');
		$this->assertEquals($source, 'database');
		
	}
	
	public function testQueryConfigurationBuilder() {
		$dataConfiguration = $this->configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$this->assertTrue( $queryConfiguration instanceof Tx_PtExtlist_Domain_Configuration_QueryConfiguration);
	}
	
}

?>