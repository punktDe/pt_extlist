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

require_once t3lib_extMgm::extPath('pt_extbase') . 'Classes/Div.php';
require_once t3lib_extMgm::extPath('pt_extbase') . 'Classes/Assertions/Assert.php';

/**
 * Utilitty to get selectable options from typoscript
 *
 * @package Utility
 * @author Daniel Lienert 
 */
class user_Tx_PtExtlist_Utility_FlexformDataProvider {
	
	/**
	 * Current pid
	 * @var integer
	 */
	protected $currentPid;
	
	
	
	/**
	 * The extlist Typoscript part
	 * 
	 * @var array
	 */
	protected $extListTypoScript = NULL;
	
	
	
	/**
	 * Get a List of defined extList listconfigs
	 * 
	 * @param array $config
	 * @return array $config
	 */
	public function getDefinedListConfigs(array $config) {
		$this->initTsDataProvider($config);
		$config['items'] = array_merge($config['items'],$this->getTypoScriptKeyList('settings.listConfig'));
		
		return $config;
	}
	
	
	/**
	 * Get the defined export types
	 * 
	 * @param array $config
	 * @return array $config
	 */
	public function getDefinedExportConfigs(array $config) {
		
		$exportConfigs = array();
		if(!is_array($config['items'])) $config['items'] = array();
		
		$this->initTsDataProvider($config);
		
		$tsArray = $this->getTSArrayByPath('settings.export.exportConfigs');
		foreach($tsArray as $key => $exportConfig) {
			
			if(array_key_exists('viewClassName', $exportConfig) && $exportConfig['viewClassName']) {
				$exportConfigs[] = array($key, 'export.exportConfigs.'.$key);	
			} 
		}
		
		ksort($exportConfigs);
		
		$config['items'] = array_merge($config['items'], $exportConfigs); 
		
		return $config;
	}
	
	
	/**
	 * Init the DataProvider
	 * 
	 * @param array $config
	 */
	protected function initTsDataProvider($config) {
		$this->currentPid =  $this->getCurrentPID($config);
		$this->loadExtListTyposcriptArray();
	}
	
	
	
	/**
	 * get the current pid from config array
	 * 
	 * @param array $config
	 * @return int
	 */
	protected function getCurrentPID($config) {
		return (int) $config['row']['pid'];
	}
	
	
	/**
	 * Load the complete extlist part from typoscript
	 */
	protected function loadExtListTyposcriptArray() {
		if(is_null($this->extListTypoScript)) {
			$extListTS = Tx_PtExtbase_Div::typoscriptRegistry('plugin.tx_ptextlist.', $this->currentPid);
			$this->extListTypoScript =  Tx_PtExtbase_Compatibility_Extbase_Service_TypoScript::convertTypoScriptArrayToPlainArray($extListTS);
		}
	}
	


	/**
	 * Return a list of typoscript keys beneath the current path
	 * 
	 * @param string $typoScriptPath
	 * @return array
	 */
	protected function getTypoScriptKeyList($typoScriptPath) {
		
		$keyList = array();
		$tsArray = $this->getTSArrayByPath($typoScriptPath);
		
		ksort($tsArray);
		
		foreach($tsArray as $key => $valueArray) {
			$keyList[] = array($key, $key);
		}

		return $keyList;
	}


	
	/**
	 * return a typoscript array by given typoscript path
	 * 
	 * @param string $typoScriptPath
	 * @return array 
	 */
	protected function getTSArrayByPath($typoScriptPath) {
		$pathArray = explode('.', $typoScriptPath);
		$outTSArray = Tx_Extbase_Utility_Arrays::getValueByPath($this->extListTypoScript, $pathArray);
		
		if(!is_array($outTSArray)) $outTSArray = array();
		
		return $outTSArray;
	}
	
}
?>