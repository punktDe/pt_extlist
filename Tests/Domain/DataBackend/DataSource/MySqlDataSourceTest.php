<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
 *  All rights reserved
 *
 *  For further information: http://extlist.punkt.de <extlist@punkt.de>
 *
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Testcase for mysql data source
 * 
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Tests
 * @subpackage Domain/DataBackend/DataSource
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_DataSource_MySqlDataSource_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource'));
	}
	
	
	
	public function testInjectDataSource() {
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
		$dataSourceConfig = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($configurationBuilderMock->buildDataBackendConfiguration()->getDataSourceSettings());
		$mysqlDataSource = new Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource($dataSourceConfig);
		$pdo = new PDO();
		$mysqlDataSource->injectDbObject($pdo);
	}


	/**
	 * @test
	 */
	public function executeQuery() {
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
		$dataSourceConfig = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($configurationBuilderMock->buildDataBackendConfiguration()->getDataSourceSettings());
        $mysqlDataSource = new Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource($dataSourceConfig);
        
        $fakedReturnArray = array('test' => 'test');
        
        $pdoStatementMock = $this->getMock('PDOStatement', array('fetchAll'));
        $pdoStatementMock->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue($fakedReturnArray));
            
        $pdoMock = $this->getMock('TestPDO', array('prepare'));
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($pdoStatementMock));
        
        $mysqlDataSource->injectDbObject($pdoMock);
        $result = $mysqlDataSource->executeQuery('SELECT * FROM test')->fetchAll();
        
        $this->assertEquals($fakedReturnArray, $result);
	}


	/**
	 * @test
	 */
	public function errorOnDbError() {
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
        $dataSourceConfig = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($configurationBuilderMock->buildDataBackendConfiguration()->getDataSourceSettings());
        $mysqlDataSource = new Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource($dataSourceConfig);
        
        $pdoMock = $this->getMock('TestPDO', array('prepare'));
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue(new Tx_PtExtlist_Tests_Domain_DataBackend_DataSource_PDOErrorMock()));
        
        $mysqlDataSource->injectDbObject($pdoMock);
        
        try {
            $result = $mysqlDataSource->executeQuery('SELECT * FROM test')->fetchAll();
        } catch(Exception $e) {
        	return;        
        }
        $this->fail('No exception has been thrown on DB error!');
	}
	
}



/**
 * Private class for mocking data source that throws an error
 *
 * @author Michael Knoll
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_DataSource_PDOErrorMock {
	
	public function execute() {
		throw new Exception("STUB Exception");
	}
	
	
	
	public function errorInfo() {
		return '';
	}
	
}

?>