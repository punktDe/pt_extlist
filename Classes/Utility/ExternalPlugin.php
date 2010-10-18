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
 * Class contains utility functions to access extlist objects 
 * form external dependent plugins
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Utility
 */
class Tx_PtExtlist_Utility_ExternalPlugin {
	
	
	/**
	 * Initialize and return a DataBackend with the given listIndentifier
	 *  
	 * @param string $listIdentifier
	 * @return Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 */
	public static function getDataBackend($listIdentifier) {
		
		$extListBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::getInstanceByListIdentifier($listIdentifier, false);
		
		if($extListBackend == NULL) {
			$extListTs = self::getExtListTyposcriptSettings($listIdentifier);
			self::loadLifeCycleManager();
			
			$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($extListTs);
			$extListBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($configurationBuilder);	
		}
		
		return $extListBackend;
	}
	
	/**
	 * Read the Session data into the cache
	 */
	protected function loadLifeCycleManager() {
		$lifecycleManager = Tx_PtExtlist_Domain_Lifecycle_LifecycleManagerFactory::getInstance();
		$lifecycleManager->register(Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManagerFactory::getInstance());
		// SET LIFECYCLE TO START -> read session data into cache
		$lifecycleManager->updateState(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START);
	}
	
	
	/**
	 * Get Typoscript for defined listIdentifier
	 * 
	 * @param string $listIdentifier
	 * @throws Exception
	 * @return array
	 */
	protected static function getExtListTyposcriptSettings($listIdentifier) {
		$extListTS = tx_pttools_div::getTS('plugin.tx_ptextlist.settings.');
		$extListTSArray = Tx_Extbase_Utility_TypoScript::convertTypoScriptArrayToPlainArray($extListTS);

		if(!array_key_exists($listIdentifier, $extListTSArray['listConfig'])) {
			throw new Exception('No listconfig with listIdentifier ' . $listIdentifier . ' defined on this page! 1284655053');
		}
		
		$extListTSArray['listIdentifier'] = $listIdentifier;
		
		return $extListTSArray;
	}
	
}