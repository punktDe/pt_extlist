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
 * TODO refactor this class to be "non-static" anymore
 *
 * @package ExtlistContext
 * @author Daniel Lienert
 */
class Tx_PtExtlist_ExtlistContext_ExtlistContextFactory implements t3lib_Singleton {


	/**
	 * @var Tx_Extbase_Configuration_ConfigurationManager
	 */
	protected $configurationMananger;



	/**
	 * @var Tx_Extbase_Object_ObjectManager
	 */
	protected $objectManager;



	/**
	 * @var Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder
	 */
	protected $sessionPersistenceManagerBuilder;



	/**
	 * @var array<Tx_PtExtlist_ExtlistContext_ExtlistContext>
	 */
	protected $instances = array();



	/**
	 * Array of listContext Instances
	 * @var array<Tx_PtExtlist_ExtlistContext_ExtlistContext>
	 */
	protected static $staticInstances = array();



	/**
	 * @var array
	 */
	protected static $extListTyposcript = NULL;



	/**
	 * @var Tx_Extbase_Object_ObjectManager
	 */
	protected static $staticObjectManager;



	/**
	 * @param Tx_Extbase_Configuration_ConfigurationManager $configurationManager
	 */
	public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManager $configurationManager){
		$this->configurationMananger = $configurationManager;
	}



	/**
	 * @param Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder
	 */
	public function injectSessionPersistenceManagerBuilder(Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder){
		$this->sessionPersistenceManagerBuilder = $sessionPersistenceManagerBuilder;
	}



	/**
	 * @param Tx_Extbase_Object_ObjectManager $objectManager
	 */
	public function injectObjectManager(Tx_Extbase_Object_ObjectManager $objectManager){
		$this->objectManager = $objectManager;
	}



	/**
	 * Initialize and return a DataBackend with the given listIndentifier
	 *
	 * @param string $listIdentifier
	 * @return Tx_PtExtlist_ExtlistContext_ExtlistContext
	 */
	public static function getContextByListIdentifier($listIdentifier) {

		if(!array_key_exists($listIdentifier, self::$staticInstances)) {

			self::$staticObjectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');

			$extListBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::getInstanceByListIdentifier($listIdentifier, false);

			if($extListBackend === NULL) {
				$extListTs = self::getExtListTyposcriptSettings($listIdentifier);

				// TODO resolve this properly with Dependency Injection once we have cascading container
				#Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($extListTs);
				$configurationBuilderFactory = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory'); /* @var $configurationBuilderFactory Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory */
				$configurationBuilderFactory->setSettings($extListTs);
				$configurationBuilder = $configurationBuilderFactory->getInstance($listIdentifier);
				#$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($listIdentifier);

				self::loadLifeCycleManager($configurationBuilder);

				$extListBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($configurationBuilder);
			}

			self::$staticInstances[$listIdentifier] = self::buildContext($extListBackend);

		}

		return self::$staticInstances[$listIdentifier];
	}



	/**
	 * Non-static wrapper for getContextByListIdentifier
	 *
	 * @param $listIdentifier
	 * @return Tx_PtExtlist_ExtlistContext_ExtlistContext
	 */
	public function getContextByListIdentifierNonStatic($listIdentifier) {
		return self::getContextByListIdentifier($listIdentifier);
	}


	
	/**
	 * Get the databackend by a custom list configuration ts array
	 * The Typoscript is identified by the given listIdentifier and merged with the extlist configuration
	 *
	 * @param array $customTSArray Custom typoscript list configuration in extBase format
	 * @param string $listIdentifier a listIdentifier to identify the custom list
	 * @param $useCache boolean
	 * @return Tx_PtExtlist_ExtlistContext_ExtlistContext
	 */
	public static function getContextByCustomConfiguration(array $customTSArray, $listIdentifier, $useCache = TRUE) {
		
		if(!array_key_exists($listIdentifier, self::$staticInstances) || !$useCache) {

			self::$staticObjectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');

			if($useCache) {

				try {
					// TODO Remove this, once we have DI
					$configurationBuilderFactory = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory'); /* @var $configurationBuilderFactory Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory */
					$configurationBuilder = $configurationBuilderFactory->getInstance($listIdentifier);
				} catch (Exception $e) {
					$configurationBuilder = self::buildConfigurationBuilder($customTSArray, $listIdentifier);
				}

			} else {
				$configurationBuilder = self::buildConfigurationBuilder($customTSArray, $listIdentifier, TRUE);
			}

			$extListBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($configurationBuilder, !$useCache);
			self::$staticInstances[$listIdentifier] = self::buildContext($extListBackend);
		}

		return self::$staticInstances[$listIdentifier];
	}




	/**
	 * Non-static wrapper for getContextByCustomConfiguration
	 *
	 * @param array $customTSArray
	 * @param $listIdentifier
	 * @param bool $useCache
	 * @return Tx_PtExtlist_ExtlistContext_ExtlistContext
	 */
	public function getContextByCustomConfigurationNonStatic(array $customTSArray, $listIdentifier, $useCache = true) {
		return self::getContextByCustomConfiguration($customTSArray, $listIdentifier, $useCache);
	}



	/**
	 * @static
	 * @param $extListTypoScript array
	 * @return void
	 */
	public static function setExtListTyposSript($extListTypoScript) {
		self::$extListTyposcript = $extListTypoScript;
	}



	/**
	 * @static
	 * @param array $customTSArray
	 * @param $listIdentifier
	 * @param boolean $resetConfigurationBuilder
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected static function buildConfigurationBuilder(array $customTSArray, $listIdentifier, $resetConfigurationBuilder = FALSE) {
		$extListTs = self::getExtListTyposcriptSettings($listIdentifier, $customTSArray);

		// TODO remove this, once we have DI
		$configurationBuilderFactory = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory'); /* @var $configurationBuilderFactory Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory */
		$configurationBuilderFactory->setSettings($extListTs);
		$configurationBuilder = $configurationBuilderFactory->getInstance($listIdentifier, $resetConfigurationBuilder);

		self::loadLifeCycleManager($configurationBuilder);

		return $configurationBuilder;
	}



	/**
	 * Build the extbaseContext
	 *
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 * @return Tx_PtExtlist_ExtlistContext_ExtlistContext $extbaseContext
	 */
	protected static function buildContext(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend) {
		$extListContext = self::$staticObjectManager->get('Tx_PtExtlist_ExtlistContext_ExtlistContext');

		$extListContext->_injectDataBackend($dataBackend);
		$extListContext->init();
		
		return $extListContext;
	}



	/**
	 * Read the Session data into the cache
	 */
	protected static function loadLifeCycleManager(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		// TODO use DI here once refactoring is finished
		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager'); /* @var $objectManager Tx_Extbase_Object_ObjectManager */
		$lifecycleManager = $objectManager->get('Tx_PtExtbase_Lifecycle_Manager'); /* @var $lifecycleManager Tx_PtExtbase_Lifecycle_Manager */
		$sessionPersistenceManagerBuilder = $objectManager->get('Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder'); /* @var $sessionPersistenceManagerBuilder Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder */
		$sessionPersistenceManager = $sessionPersistenceManagerBuilder->getInstance();
		$lifecycleManager->registerAndUpdateStateOnRegisteredObject($sessionPersistenceManager);

		// If we have resetOnEmptySubmit, we reset session data here
		if ($configurationBuilder->buildBaseConfiguration()->getResetOnEmptySubmit() && Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory::getInstance()->isEmptySubmit()) {
			$sessionPersistenceManager->resetSessionData();
		}
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
			unset($extListTSArray['listConfig'][$listIdentifier]); // We remove the listConfiguration completely if it was there before
			$extListTSArray['listConfig'] = t3lib_div::array_merge_recursive_overrule($extListTSArray['listConfig'], array($listIdentifier => $customTSArray));
		}

		if(!array_key_exists($listIdentifier, $extListTSArray['listConfig'])) {
			throw new Exception('No listconfig with listIdentifier ' . $listIdentifier . ' defined on this page!', 1284655053);
		}

		$extListTSArray['listIdentifier'] = $listIdentifier;

		return $extListTSArray;
	}



	/**
	 * Get current typoscript settings for pt_extlist plugin
	 *
	 */
	protected static function getCurrentTyposcript() {

		if(self::$extListTyposcript !== NULL) return self::$extListTyposcript;
		
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
		$configurationManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_Extbase_Configuration_BackendConfigurationManager'); /* @var $configurationManager Tx_Extbase_Configuration_BackendConfigurationManager */
		$completeTS = $configurationManager->getTypoScriptSetup();
		return Tx_PtExtbase_Compatibility_Extbase_Service_TypoScript::convertTypoScriptArrayToPlainArray($completeTS['plugin.']['tx_ptextlist.']);
	}



	/**
	 * Retrieve Typoscript Configuration from selected frontend pid
	 *
	 * @return array typoscript array
	 */
	protected static function getTyposcriptOfCurrentFrontendPID() {

		$configurationManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_Extbase_Configuration_FrontendConfigurationManager'); /* @var $configurationManager Tx_Extbase_Configuration_FrontendConfigurationManager */
		$completeTS = $configurationManager->getTypoScriptSetup();
		return Tx_PtExtbase_Compatibility_Extbase_Service_TypoScript::convertTypoScriptArrayToPlainArray($completeTS['plugin.']['tx_ptextlist.']);
	}

}