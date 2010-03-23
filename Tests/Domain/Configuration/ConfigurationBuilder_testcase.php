<?php

class Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder_testcase extends Tx_Extbase_BaseTestcase {
	
	public function testBuildDataConfiguration() {
		
		$configAdapter = $this->getMock('Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter',
										array(	'getDataConfiguration',
												'getSelectQueryConfiguration',
												'getFromQueryConfiguration',
												'getJoinQueryConfiguration'
										),
										array(),
										'',
										FALSE,
										FALSE,
										FALSE);

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
			
			
//		$configAdapter->expects($this->once())->method('getDataConfiguration')->with($this->equalTo('test'));
										
		
		$builder = $this->getMock( $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder'), 
										array('dummy'),
										array(),
										'',
										FALSE);
		
		$builder->_set('extensionConfigurationAdapter', $configAdapter);
		
		
		$builder->buildDataConfiguration('test');
		
	}
	
}

?>