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
 * Testcase for basic typoscript Settings
 * 
 * @author Daniel Lienert 
 * @package Tests
 * @subpackage Typoscript
 */
class Tx_PtExtlist_Tests_Typoscript_TypoScriptTest extends Tx_Extbase_BaseTestCase {
	
	protected $prototypePath;
	
	protected $baseConfigTSFile;
	
	protected $prototypeFiles;
	
	
	public function SetUp() {
		$this->baseConfigTSFile = t3lib_extMgm::extPath('pt_extlist') . 'Configuration/TypoScript/setup.txt';
		$this->prototypePath = t3lib_extMgm::extPath('pt_extlist') . 'Configuration/TypoScript/BaseConfig/Prototype/';
		$this->prototypeFiles = $this->loadProttypeFileNamesAsArray();	
	}
		
	public function testTsInclusion() {
		$TSIncludeString = $this->loadTSFile($this->baseConfigTSFile);
		$tsFileArray = $this->loadProttypeFileNamesAsArray();
		
		foreach($tsFileArray as $tsFile) {
			$includeCommand = '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pt_extlist/Configuration/TypoScript/BaseConfig/Prototype/'.$tsFile.'">';
			$this->assertTrue(strpos($TSIncludeString, $includeCommand) > 0, 'The Prototype File ' . $tsFile . ' is not included in BasicSettings file ' . $this->baseConfigTSFile. ' (' . $includeCommand . ')');
		}
	}
	
	
	
	public function testConfigurationBuilderWithTypo3Backend() {
		$settings = $this->buildTypoScriptConfigForConfigBuilder('t3BackendTestList');
		Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($settings);
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('t3BackendTestList');
		$dataBackendConfig = $configurationBuilder->buildDataBackendConfiguration();
		$this->assertTrue(is_a($dataBackendConfig, 'Tx_PtExtlist_Domain_Configuration_DataBackend_DatabackendConfiguration'));
	}
	
	
	
	public function testConfigurationBuilderWithMysqlBackend() {
		$settings = $this->buildTypoScriptConfigForConfigBuilder('mysqlBackendTestList');
		Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($settings);
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('t3BackendTestList');
		$dataBackendConfig = $configurationBuilder->buildDataBackendConfiguration();
		$this->assertTrue(is_a($dataBackendConfig, 'Tx_PtExtlist_Domain_Configuration_DataBackend_DatabackendConfiguration'));
	}

	
	
	public function testBuildColumnsConfiguration() {
		$settings = $this->buildTypoScriptConfigForConfigBuilder('tsTestList');
		Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($settings);
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('t3BackendTestList');
	
	}
	
	
	
	public function testBuildRendererConfiguration() {
		$this->markTestIncomplete('TODO refactor all rendering');
		$settings = $this->buildTypoScriptConfigForConfigBuilder('tsTestList');
		Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($settings);
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('t3BackendTestList');
		$configurationBuilder->buildRendererConfiguration();
	}
	
	public function testBuildPagerConfiguration() {
		$settings = $this->buildTypoScriptConfigForConfigBuilder('tsTestList');
		Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($settings);
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('t3BackendTestList');
		$configurationBuilder->buildPagerConfiguration();
	}
	
	public function testBuildFilterBoxConfiguration() {
		$settings = $this->buildTypoScriptConfigForConfigBuilder('tsTestList');
		Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($settings);
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('t3BackendTestList');
		$configurationBuilder->buildFilterConfiguration();
	}
	
	protected function buildTypoScriptConfigForConfigBuilder($listIdentifier) {
		$TSString = $this->loadAllPrototypeTS();
		$TSString .=$this->loadTestList();
		
	
		$parserInstance = t3lib_div::makeInstance('t3lib_tsparser');
		$parserInstance->parse($TSString);
 		
		//$cObj = t3lib_div::makeInstance('tslib_cObj');
		//$output = $cObj->COBJ_ARRAY($parserInstance->setup);
		
		$tsSettings = $parserInstance->setup;

		$settings = Tx_PtExtbase_Compatibility_Extbase_Service_TypoScript::convertTypoScriptArrayToPlainArray($tsSettings);
		
		$settings['plugin']['tx_ptextlist']['settings']['listIdentifier'] = $listIdentifier;
		
		return $settings['plugin']['tx_ptextlist']['settings'];
	}
	
	
	
	protected function loadAllPrototypeTS() {
		$tsFileArray = $this->prototypeFiles;
		$TSString = '';
		
		foreach($tsFileArray as $tsFile) {
			$TSString .= $this->loadTSFile($this->prototypePath . $tsFile);
		}
		
		return $TSString;
	}
	
	protected function loadProttypeFileNamesAsArray() {
		$dirHandler = dir($this->prototypePath);
		while (false !== ($entry = $dirHandler->read())) {
		  if($entry != '.' && $entry != '..') {
		  	$entries[] = $entry;
		  }
		}
		$dirHandler->close();
		
		return $entries;
	}
	
	protected function loadTestList() {
		return $this->loadTSFile(t3lib_extMgm::extPath('pt_extlist') . 'Tests/Typoscript/testlist.txt');
	}
	
	protected function loadTSFile($filePath) {
		$handle = fopen ($filePath, "r");
		while (!feof($handle)) {
		    $buffer .= fgets($handle, 4096);
		}
		fclose ($handle);
		
		return $buffer;
	}	
}
?>