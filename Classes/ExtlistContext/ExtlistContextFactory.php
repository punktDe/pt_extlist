<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Class implements factory for ExtListContext
 *
 * @package ExtlistContext
 * @author Daniel Lienert
 */
class Tx_PtExtlist_ExtlistContext_ExtlistContextFactory {

	/**
	 * Array of listContext Instances
	 * @var Tx_PtExtlist_Domain_ListContext_ListContext
	 */
	protected static $instances = array();



	/**
	 * Initialize and return a DataBackend with the given listIndentifier
	 *
	 * @param string $listIdentifier
	 * @return Tx_PtExtlist_ExtlistContext_ExtlistContext
	 */
	public static function getContextByListIdentifier($listIdentifier) {

		if(!array_key_exists($listIdentifier, self::$instances)) {

			$extListBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::getInstanceByListIdentifier($listIdentifier, false);

			if($extListBackend === NULL) {
				$extListTs = self::getExtListTyposcriptSettings($listIdentifier);
				self::loadLifeCycleManager();

				Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($extListTs);
				$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($listIdentifier);

				$extListBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($configurationBuilder);
			}

			self::$instances[$listIdentifier] = self::buildContext($extListBackend);

		}

		return self::$instances[$listIdentifier];
	}



	/**
	 * Get the databackend by a custom list configuration ts array
	 * The Typoscript is identified by the given listIdentifier and merged with the extlist configuration
	 *
	 * @param array $customTSArray Custom typoscript list configuration in extBase format
	 * @param string $listIdentifier a listIdentifier to identify the custom list
	 * @return Tx_PtExtlist_ExtlistContext_ExtlistContext
	 */
	public static function getContextByCustomConfiguration(array $customTSArray, $listIdentifier) {

		if(!array_key_exists($listIdentifier, self::$instances)) {

			try {
				$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($listIdentifier);
			} catch (Exception $e) {
				$extListTs = self::getExtListTyposcriptSettings($listIdentifier, $customTSArray);
				self::loadLifeCycleManager();

				Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($extListTs);
				$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($listIdentifier);
			}

			$extListBackend =   Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($configurationBuilder);
			self::$instances[$listIdentifier] = self::buildContext($extListBackend);
		}

		return self::$instances[$listIdentifier];
	}



	/**
	 * Build the extlistContext
	 *
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 * @return Tx_PtExtlist_ExtlistContext_ExtlistContext $extlistContext
	 */
	protected static function buildContext(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend) {
		$extlistContext = new Tx_PtExtlist_ExtlistContext_ExtlistContext();

		$extlistContext->injectDataBackend($dataBackend);
		$extlistContext->init();

		return $extlistContext;
	}



	/**
	 * Read the Session data into the cache
	 */
	protected static function loadLifeCycleManager() {
		$lifecycleManager = Tx_PtExtbase_Lifecycle_ManagerFactory::getInstance();
		$lifecycleManager->register(Tx_PtExtbase_State_Session_SessionPersistenceManagerFactory::getInstance());
		// SET LIFECYCLE TO START -> read session data into cache
		$lifecycleManager->updateState(Tx_PtExtbase_Lifecycle_Manager::START);
	}



	/**
	 * Get Typoscript for defined listIdentifier
	 *
	 * @param string $listIdentifier
	 * @param array $customTSArray custom ts array
	 * @throws Exception
	 * @return array
	 */
	protected static function getExtListTyposcriptSettings($listIdentifier, $customTSArray = NULL) {

		$extListTSArray = self::getCurrentTyposcript();
		$extListTSArray = $extListTSArray['settings'];

		if(!is_array($extListTSArray['listConfig'])) $extListTSArray['listConfig'] = array();

		if(is_array($customTSArray)) {
			$extListTSArray['listConfig'] = t3lib_div::array_merge_recursive_overrule($extListTSArray['listConfig'], array($listIdentifier => $customTSArray));
		}

		if(!array_key_exists($listIdentifier, $extListTSArray['listConfig'])) {
			throw new Exception('No listconfig with listIdentifier ' . $listIdentifier . ' defined on this page! 1284655053');
		}

		$extListTSArray['listIdentifier'] = $listIdentifier;

		return $extListTSArray;
	}



	/**
	 * Get current typoscript settings for pt_extlist plugin
	 *
	 */
	protected static function getCurrentTyposcript() {
		if(TYPO3_MODE === 'BE') {
			return self::getTyposcriptOfCurrentBackendPID();
		} else {
			return self::getTyposcriptOfCurrentFrontendPID();
		}
	}



	/**
	 * Retrieve Typoscript Configuration from selected backend pid
	 *
	 * @return array typoscript array
	 */
	protected static function getTyposcriptOfCurrentBackendPID() {
		$configurationManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_Extbase_Configuration_BackendConfigurationManager');
		$completeTS = $configurationManager->getTypoScriptSetup();
		return Tx_Extbase_Utility_TypoScript::convertTypoScriptArrayToPlainArray($completeTS['plugin.']['tx_ptextlist.']);
	}



	/**
	 * Retrieve Typoscript Configuration from selected frontend pid
	 *
	 * @return array typoscript array
	 */
	protected static function getTyposcriptOfCurrentFrontendPID() {

		$configurationManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_Extbase_Configuration_FrontendConfigurationManager');
		$completeTS = $configurationManager->getTypoScriptSetup();
		return Tx_Extbase_Utility_TypoScript::convertTypoScriptArrayToPlainArray($completeTS['plugin.']['tx_ptextlist.']);
	}
}
?>