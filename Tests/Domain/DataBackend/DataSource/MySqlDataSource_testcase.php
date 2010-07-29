<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_DataSource_MySqlDataSource_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource'));
	}
	
	
	
	public function testInjectDataSource() {
		$dataSourceConfig = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration(array());
		$mysqlDataSource = new Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource($dataSourceConfig);
		$pdo = new PDO();
		$mysqlDataSource->injectDbObject($pdo);
	}
	
	
	
	public function testExecuteQuery() {
		$dataSourceConfig = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration(array());
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
        $result = $mysqlDataSource->execute('SELECT * FROM test');
        
        $this->assertTrue($fakedReturnArray == $result);
	}
	
	
	
	public function testErrorOnDbError() {
		$this->markTestIncomplete();
	}
	
}


?>