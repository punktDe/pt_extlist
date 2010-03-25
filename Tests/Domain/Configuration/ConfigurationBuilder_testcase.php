<?php

class Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder_testcase extends Tx_Extbase_BaseTestcase {
	
	protected $builder;
	
	public function setUp() {
		
		$configAdapter = $this->getMock('Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter',
										array(	'getDataConfiguration',
												'getSelectQueryConfiguration',
												'getFromQueryConfiguration',
												'getJoinQueryConfiguration'
										));

		$configAdapter
			->expects($this->once())
			->method('getSelectQueryConfiguration')
			->with($this->equalTo('test'))
			->will($this->returnValue(new Tx_PtExtlist_Domain_Configuration_Query_Select()));
		
		$configAdapter
			->expects($this->once())
			->method('getFromQueryConfiguration')
			->with($this->equalTo('test'))
			->will($this->returnValue(new Tx_PtExtlist_Domain_Configuration_Query_From()));
			
		$configAdapter
			->expects($this->once())
			->method('getJoinQueryConfiguration')
			->with($this->equalTo('test'))
			->will($this->returnValue(new Tx_PtExtlist_Domain_Configuration_Query_Join()));
			
		$configAdapter
			->expects($this->once())
			->method('getDataConfiguration')
			->with($this->equalTo('test'))
			->will($this->returnValue(new Tx_PtExtlist_Domain_Configuration_DataConfiguration()));
								
		
		$this->builder = $this->getMock( $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder'), 
										array('dummy'),
										array(),
										'',
										FALSE
										);
		
		$this->builder->_set('extensionConfigurationAdapter', $configAdapter);
	}
	
		
	public function testDataConfigurationType() {
		$dataConfig = $this->builder->buildDataConfiguration('test');		
		
		$this->assertTrue($dataConfig instanceof Tx_PtExtlist_Domain_Configuration_DataConfiguration);
		$this->assertTrue($dataConfig->getQueryConfiguration() instanceof Tx_PtExtlist_Domain_Configuration_QueryConfiguration);
		
	}
	
	public function testQueryConfigurationTypes() {
		$dataConfig = $this->builder->buildDataConfiguration('test');
		$queryConfig = $dataConfig->getQueryConfiguration();
		
		$this->assertTrue($queryConfig->getSelect() instanceof Tx_PtExtlist_Domain_Configuration_Query_Select);
		$this->assertTrue($queryConfig->getFrom() instanceof Tx_PtExtlist_Domain_Configuration_Query_From);
		$this->assertTrue($queryConfig->getJoin() instanceof Tx_PtExtlist_Domain_Configuration_Query_Join);
	}
	
	
	
}

?>