<?php
class Tx_PtExtlist_Domain_Configuration_QueryConfiguration_testcase extends Tx_Extbase_BaseTestcase {
	
	protected $settingsFixture;

	
	public function setUp() {
		$this->settingsFixture = array ( 'listConfig' => array ( 'test' => array (
				'data' => array(
										
					'query' => array (
						'from' => array (
							'10' => array(
								'table' => 'table1',
								'alias' => 'alias1'
							),
							'20' => array(
								'table' => 'table2',
								'alias' => 'alias2'
							)
						),
						
						'join' => array (
							'10' => array (
								'table' => 'table1',
								'alias' => 'alias',
								'_typoScriptNodeValue' => 'INNER',
								'on' => array (
									'field' => 'field',
									'value' => 'value'
								)
							),
							
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
						),
						
						'mapping' => array (
							'property1' => 'field1',
							'property2' => 'field2',
						)
					)
				)
				)
				)
		);
	}

	public function testQueryConfigurationBuilder() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$this->assertTrue( $queryConfiguration instanceof Tx_PtExtlist_Domain_Configuration_QueryConfiguration);
	}
	
	public function testQuerySelectConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$select = $queryConfiguration->getSelect();
		
		$this->assertTrue($select instanceof Tx_PtExtlist_Domain_Configuration_Query_Select);
		
		$fields = $select->getFields();
		
		$this->assertTrue( array_key_exists('field1', $fields) );
		$this->assertTrue( array_key_exists('field2', $fields) );
		
	}
	
	public function testQuerySelectTrueValidation() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$select = $queryConfiguration->getSelect();
		
		$this->assertTrue($select->isValid());
	}
	
	public function testQuerySelectFalseValidation() {
		$this->settingsFixture['listConfig']['test']['data']['query']['mapping'] = array();
		
		
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$select = $queryConfiguration->getSelect();
		
		$this->assertFalse($select->isValid());
		$this->assertFalse($queryConfiguration->isValid());
	}
	
	public function testQueryFromConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$from = $queryConfiguration->getFrom();
		
		$this->assertTrue($from instanceof Tx_PtExtlist_Domain_Configuration_Query_From);
		
		$this->assertFalse($from->isSql());
		
		$tables = $from->getTables();

		$this->assertTrue( array_key_exists('table1',$tables) );
		$this->assertEquals( $tables['table1'], 'alias1');
		
		$this->assertTrue( array_key_exists('table2',$tables) );
		$this->assertEquals( $tables['table2'], 'alias2');
		
		
	}
	
	public function testQueryFromSqlOverrideConfiguration() {
		$this->settingsFixture['listConfig']['test']['data']['query']['from']['_typoScriptNodeValue'] = 'table1, table2';
		
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$from = $queryConfiguration->getFrom();
		
		$this->assertTrue($from instanceof Tx_PtExtlist_Domain_Configuration_Query_From);
		
		$this->assertTrue($from->isSql());
		
		$this->assertEquals($from->getSql(), 'table1, table2');

	}
	
	public function testQueryFromTrueValidation() {
		
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$from = $queryConfiguration->getFrom();
		
		$this->assertTrue($from->isValid());
	}
	
	public function testQueryFromFalseValidation() {
		/**
		 * Check if a empty configuration is checked correctly.
		 */
		$this->settingsFixture['listConfig']['test']['data']['query']['from'] = array();
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$from = $queryConfiguration->getFrom();
		
		$this->assertFalse($from->isValid());
		$this->assertFalse($queryConfiguration->isValid());
		
		/**
		 * Check if the normal array configuration is checked correctly.
		 */
		$this->settingsFixture['listConfig']['test']['data']['query']['from']['10'] = 'table1, table2';
		$this->settingsFixture['listConfig']['test']['data']['query']['from']['20'] = array();
		
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$from = $queryConfiguration->getFrom();
		
		$this->assertFalse($from->isValid());
		$this->assertFalse($queryConfiguration->isValid());
		
		/**
		 * Check if the sql override is checked correctly.
		 */
		$this->settingsFixture['listConfig']['test']['data']['query']['from']['_typoScriptNodeValue'] = '';
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$from = $queryConfiguration->getFrom();
		
		$this->assertFalse($from->isValid());
		$this->assertFalse($queryConfiguration->isValid());
	}
	
	public function testQueryValidation() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$this->assertTrue( $queryConfiguration->isValid() );
		
	}
	
	public function testQueryJoinConfiguration() {
//		$this->settingsFixture['listConfig']['test']['data']['query']['join']['_typoScriptNodeValue'] = 'table1 as t1 ON bla = foo';
		
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
//		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$join = $queryConfiguration->getJoin();
		
		$this->assertTrue($join instanceof Tx_PtExtlist_Domain_Configuration_Query_Join);
		
		$this->assertFalse($join->isSql());
		
		$tables = $join->getTables();
		
		$this->assertTrue(array_key_exists('table1', $tables));
		
		$this->assertEquals($tables['table1']['alias'], 'alias');
		$this->assertEquals($tables['table1']['onField'], 'field');
		$this->assertEquals($tables['table1']['onValue'], 'value');
	}
	
	public function testQueryJoinSqlOverrideConfiguration() {
		$this->settingsFixture['listConfig']['test']['data']['query']['join']['_typoScriptNodeValue'] = 'table1 as t1 ON bla = foo';
		
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$join = $queryConfiguration->getJoin();
		
		$this->assertTrue($join instanceof Tx_PtExtlist_Domain_Configuration_Query_Join);
		
		$this->assertTrue($join->isSql());
		
		$sql = $join->getSql();
		
		$this->assertEquals($sql, 'table1 as t1 ON bla = foo');

	}
	
	public function testQueryJoinTrueValidation() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$join = $queryConfiguration->getJoin();
		$this->assertTrue($join->isValid());
	}
	
	public function testQueryJoinFalseValidation() {
		
		/**
		 * Check if a empty onField configuration is correctly validated
		 */
		$this->settingsFixture['listConfig']['test']['data']['query']['join']['on']['field'] = '';
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$join = $queryConfiguration->getJoin();
		$this->assertFalse($join->isValid());
		$this->assertFalse($queryConfiguration->isValid());
		
		/**
		 * Check if a empty sql configuration is correctly validated
		 */
		$this->settingsFixture['listConfig']['test']['data']['query']['join']['_typoScriptNodeValue'] = '';
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$join = $queryConfiguration->getJoin();
		$this->assertFalse($join->isValid());
		$this->assertFalse($queryConfiguration->isValid());
		
		/**
		 * Check if a empty configuration is correctly validated
		 */
		$this->settingsFixture['listConfig']['test']['data']['query']['join'] = array();
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settingsFixture);
		$configurationBuilder->setSettings($this->settingsFixture);
		
		$dataConfiguration = $configurationBuilder->buildDataConfiguration('test');
		$queryConfiguration = $dataConfiguration->getQueryConfiguration();
		
		$join = $queryConfiguration->getJoin();
		$this->assertFalse($join->isValid());
		$this->assertFalse($queryConfiguration->isValid());
	}
	
	
}
?>