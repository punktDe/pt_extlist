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
 * Test for datasource configuration
 *
 * @package Tests
 * @subpackage pt_extlist
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_Configuration_DataBackend_DataSource_DataBaseDataSourceConfigurationTest extends Tx_Extbase_BaseTestcase {

	protected $dataBackendConfiguration;
	
	public function setUp() {
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
		$this->dataBackendConfiguration = $configurationBuilderMock->buildDataBackendConfiguration();
	}
	
	public function testGetUsername() {
		$dataSourceConfig = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($this->dataBackendConfiguration->getDataSourceSettings());
		$this->assertEquals($dataSourceConfig->getUsername(), 'user');
	}
	
	public function testGetPassword() {
		$dataSourceConfig = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($this->dataBackendConfiguration->getDataSourceSettings());
		$this->assertEquals($dataSourceConfig->getPassword(), 'pass');
	}
		
	public function testGetHost() {
		$dataSourceConfig = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($this->dataBackendConfiguration->getDataSourceSettings());
		$this->assertEquals($dataSourceConfig->getHost(), 'localhost');
	}
	
	public function testGetPort() {
		$dataSourceConfig = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($this->dataBackendConfiguration->getDataSourceSettings());
		$this->assertEquals($dataSourceConfig->getPort(), 3306);
	}
	
	public function testGetDataBaseName() {
		$dataSourceConfig = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($this->dataBackendConfiguration->getDataSourceSettings());
		$this->assertEquals($dataSourceConfig->getDatabaseName(), 'typo3');
	}
	
}
?>