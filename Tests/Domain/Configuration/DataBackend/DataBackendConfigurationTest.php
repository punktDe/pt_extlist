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
 * Test for databackendConfiguration factory
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_Configuration_DataBackend_DataBackendConfigurationTest extends Tx_Extbase_BaseTestcase {
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration
	 */
	protected $dataBackendConfiguration = null;
	
	public function setUp() {
		
		if(is_null($this->dataBackendConfiguration)) {
			$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
			
			$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration');
			$this->dataBackendConfiguration = new $accessibleClassName($configurationBuilderMock);	
		}
	}
	
	public function testGetDataBackendSettingsAsArray() {
		$dataBackendSettings = $this->dataBackendConfiguration->getDataBackendSettings();
		$this->assertEquals($dataBackendSettings['dataBackendClass'], 'Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend');
	}
	
	public function testGetDataBackendSettingsWithKey() {
		$dataBackendClass = $this->dataBackendConfiguration->getDataBackendSettings('dataBackendClass');
		$this->assertEquals($dataBackendClass, 'Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend');
	}
	
	public function testGetDataSourceSettings() {
		$dataSourceSettings = $this->dataBackendConfiguration->getDataSourceSettings();
		$this->assertEquals($dataSourceSettings['testKey'], 'testValue');
	}
	
	public function testGetDataBackendClass() {
		$this->assertEquals($this->dataBackendConfiguration->getDataBackendClass(), 'Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend');
	}
	
	public function testGetDataMapperClass() {
		$this->assertEquals($this->dataBackendConfiguration->getDataMapperClass(), 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper');
	}
	
	public function testGetQueryInterpreterClass() {
		$this->assertEquals($this->dataBackendConfiguration->getQueryInterpreterClass(), 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter');
	}
	
	public function testCheckAndSetDataBackendClass() {
			
		try {
	        $this->dataBackendConfiguration->_call('checkAndSetDataBackendClass','');
	        $this->fail('No exception has been thrown, when no databaseClassName has been set!');
    	} catch(Exception $e) {
    	}
    	
    	try {
	    	$this->dataBackendConfiguration->_call('checkAndSetDataBackendClass','NotExistingClass');
		    $this->fail('No exception has been thrown, when a not existing databaseClassName has been set!');
		} catch(Exception $e) {
    	}
	}


	/**
	 * @test
	 */
	public function checkAndSetDataBackendClass() {

		try {
			$this->dataBackendConfiguration->_call('checkAndSetDataSourceClass','');
			$this->fail('No exception has been thrown, when no databaseClassName has been set!');
		} catch(Exception $e) {
		}

		try {
			$this->dataBackendConfiguration->_call('checkAndSetDataSourceClass','NotExistingClass');
			$this->fail('No exception has been thrown, when a not existing databaseClassName has been set!');
		} catch(Exception $e) {
		}
	}


	public function testCheckAndSetDataMapperClass() {
		try {
	        $this->dataBackendConfiguration->_call('checkAndSetDataMapperClass','');
	        $this->fail('No exception has been thrown, when no dataMapperClassName has been set!');
    	} catch(Exception $e) {
    	}
    	
    	try {
	    	$this->dataBackendConfiguration->_call('checkAndSetDataMapperClass','NotExistingClass');
		    $this->fail('No exception has been thrown, when a not existing dataMapperClassName has been set!');
		} catch(Exception $e) {
    	}
	}
	
	
	
	public function testCheckAndSetQueryInterpreterClass() {
		try {
	        $this->dataBackendConfiguration->_call('checkAndSetQueryInterpreterClass','');
	        $this->fail('No exception has been thrown, when no QueryInterpreterClass has been set!');
    	} catch(Exception $e) {
    	}
    	
    	try {
	    	$this->dataBackendConfiguration->_call('checkAndSetDataBackendClass','NotExistingClass');
		    $this->fail('No exception has been thrown, when a not existing QueryInterpreterClass has been set!');
		} catch(Exception $e) {
    	}
	}
}
?>