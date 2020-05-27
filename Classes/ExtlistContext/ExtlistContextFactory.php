<?php
namespace PunktDe\PtExtlist\ExtlistContext;
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

use PunktDe\PtExtbase\Lifecycle\Manager;
use PunktDe\PtExtbase\State\Session\SessionPersistenceManagerBuilder;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderFactory;
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory;
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface;
use PunktDe\PtExtlist\Domain\Model\Bookmark\BookmarkManagerFactory;
use PunktDe\PtExtlist\Domain\StateAdapter\GetPostVarAdapterFactory;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * Class implements factory for ExtListContext
 *
 * TODO refactor this class to be "non-static" anymore
 *
 * @package ExtlistContext
 * @author Daniel Lienert
 * @see ExtlistContextFactoryTest
 */
class ExtlistContextFactory implements SingletonInterface
{
    /**
     * @var ConfigurationManager
     */
    protected $configurationMananger;



    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;



    /**
     * @var SessionPersistenceManagerBuilder
     */
    protected $sessionPersistenceManagerBuilder;



    /**
     * TODO probably this is never used --> remove it!
     *
     * @var array<ExtlistContext>
     */
    protected $instances = [];



    /**
     * Array of listContext Instances
     * @var array<ExtlistContext>
     */
    protected static $staticInstances = [];



    /**
     * @var array
     */
    protected static $extListTyposcript = null;



    /**
     * @var ObjectManagerInterface
     */
    protected static $staticObjectManager;



    /**
     * @param ConfigurationManager $configurationManager
     */
    public function injectConfigurationManager(ConfigurationManager $configurationManager)
    {
        $this->configurationMananger = $configurationManager;
    }



    /**
     * @param SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder
     */
    public function injectSessionPersistenceManagerBuilder(SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder)
    {
        $this->sessionPersistenceManagerBuilder = $sessionPersistenceManagerBuilder;
    }



    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }



    /**
     * Initialize and return a DataBackend with the given listIndentifier
     *
     * @param string $listIdentifier
     * @return ExtlistContext
     * @throws \Exception
     */
    public static function getContextByListIdentifier($listIdentifier)
    {
        if (!array_key_exists($listIdentifier, self::$staticInstances)) {
            self::$staticObjectManager = GeneralUtility::makeInstance(ObjectManager::class);
            $extListTs = self::getExtListTyposcriptSettings($listIdentifier);
            self::getContextByCustomConfiguration($extListTs['listConfig'][$listIdentifier], $listIdentifier);
        }

        return self::$staticInstances[$listIdentifier];
    }



    /**
     * Non-static wrapper for getContextByListIdentifier
     *
     * @param $listIdentifier
     * @return ExtlistContext
     */
    public function getContextByListIdentifierNonStatic($listIdentifier)
    {
        return self::getContextByListIdentifier($listIdentifier);
    }


    /**
     * Get the databackend by a custom list configuration ts array
     * The Typoscript is identified by the given listIdentifier and merged with the extlist configuration
     *
     * @param array $customTSArray Custom typoscript list configuration in extBase format
     * @param string $listIdentifier a listIdentifier to identify the custom list
     * @param boolean $useCache
     * @param null|int $bookmarkUidToRestore
     * @return ExtlistContext
     * @throws \Exception
     */
    public static function getContextByCustomConfiguration(array $customTSArray, $listIdentifier, $useCache = true, $bookmarkUidToRestore = null)
    {
        if (!array_key_exists($listIdentifier, self::$staticInstances) || !$useCache) {
            self::$staticObjectManager = GeneralUtility::makeInstance(ObjectManager::class);
            if ($useCache) {
                try {
                    // TODO Remove this, once we have DI

                    $configurationBuilderFactory = GeneralUtility::makeInstance(ObjectManager::class)->get(ConfigurationBuilderFactory::class); /* @var $configurationBuilderFactory ConfigurationBuilderFactory */
                    $configurationBuilder = $configurationBuilderFactory->getInstance($listIdentifier);
                } catch (\Exception $e) {
                    $configurationBuilder = self::buildConfigurationBuilder($customTSArray, $listIdentifier);
                }
            } else {
                $configurationBuilder = self::buildConfigurationBuilder($customTSArray, $listIdentifier, true);
            }

            if ($bookmarkUidToRestore) {
                $bookmarkManagerFactory =GeneralUtility::makeInstance(ObjectManager::class)->get(BookmarkManagerFactory::class); /* @var $bookmarkManagerFactory BookmarkManagerFactory */
                $bookmarkManager = $bookmarkManagerFactory->getInstanceByConfigurationBuilder($configurationBuilder);
                $bookmarkManager->restoreBookmarkByUid($bookmarkUidToRestore);
            }


            $extListBackend = DataBackendFactory::createDataBackend($configurationBuilder, !$useCache);
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
     * @param null|int $bookmarkUidToRestore
     * @return ExtlistContext
     */
    public function getContextByCustomConfigurationNonStatic(array $customTSArray, $listIdentifier, $useCache = true, $bookmarkUidToRestore = null)
    {
        return self::getContextByCustomConfiguration($customTSArray, $listIdentifier, $useCache, $bookmarkUidToRestore);
    }



    /**
     * @static
     * @param array $extListTypoScript
     * @return void
     */
    public static function setExtListTyposSript($extListTypoScript)
    {
        self::$extListTyposcript = $extListTypoScript;
    }



    /**
     * @static
     * @param array $customTSArray
     * @param $listIdentifier
     * @param boolean $resetConfigurationBuilder
     * @return \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder
     * @throws \Exception
     */
    protected static function buildConfigurationBuilder(array $customTSArray, $listIdentifier, $resetConfigurationBuilder = false)
    {
        $extListTs = self::getExtListTyposcriptSettings($listIdentifier, $customTSArray);

        // TODO remove this, once we have DI

        $configurationBuilderFactory = GeneralUtility::makeInstance(ObjectManager::class)->get(ConfigurationBuilderFactory::class); /* @var $configurationBuilderFactory ConfigurationBuilderFactory */
        $configurationBuilderFactory->setSettings($extListTs);
        $configurationBuilder = $configurationBuilderFactory->getInstance($listIdentifier, $resetConfigurationBuilder);

        self::loadLifeCycleManager($configurationBuilder);

        return $configurationBuilder;
    }



    /**
     * Build the extbaseContext
     *
     * @param \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface $dataBackend
     * @return ExtlistContext $extbaseContext
     */
    protected static function buildContext(DataBackendInterface $dataBackend)
    {
        $extListContext = GeneralUtility::makeInstance(ObjectManager::class)->get(ExtlistContext::class); /* @var ExtlistContext $extListContext */

        $extListContext->_injectDataBackend($dataBackend);
        $extListContext->init();
        
        return $extListContext;
    }



    /**
     * Read the Session data into the cache
     * @param ConfigurationBuilder $configurationBuilder
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     * @throws \Exception
     */
    protected static function loadLifeCycleManager(ConfigurationBuilder $configurationBuilder)
    {
        // TODO use DI here once refactoring is finished

        $objectManager = GeneralUtility::makeInstance(ObjectManager::class); /* @var $objectManager ObjectManager */
        $lifecycleManager = $objectManager->get(Manager::class); /* @var $lifecycleManager Manager */
        $sessionPersistenceManagerBuilder = $objectManager->get(SessionPersistenceManagerBuilder::class); /* @var $sessionPersistenceManagerBuilder SessionPersistenceManagerBuilder */
        $sessionPersistenceManager = $sessionPersistenceManagerBuilder->getInstance();
        $getPostVarAdapterFactory = $objectManager->get(GetPostVarAdapterFactory::class); /* @var $getPostVarAdapterFactory GetPostVarAdapterFactory */
        $getPostVarAdapter = $getPostVarAdapterFactory->getInstance();
        $lifecycleManager->registerAndUpdateStateOnRegisteredObject($sessionPersistenceManager);

        // If we have resetOnEmptySubmit, we reset session data here
        if ($configurationBuilder->buildBaseConfiguration()->getResetOnEmptySubmit() && $getPostVarAdapter->isEmptySubmit()) {
            $sessionPersistenceManager->resetSessionData();
        }
    }



    /**
     * Get Typoscript for defined listIdentifier
     *
     * @param string $listIdentifier
     * @param array $customTSArray custom ts array
     * @throws \Exception
     * @return array
     */
    protected static function getExtListTyposcriptSettings($listIdentifier, $customTSArray = null)
    {
        $extListTSArray = self::getCurrentTyposcript();
        $extListTSArray = $extListTSArray['settings'];
        
        if (!is_array($extListTSArray['listConfig'])) {
            $extListTSArray['listConfig'] = [];
        }

        if (is_array($customTSArray)) {
            unset($extListTSArray['listConfig'][$listIdentifier]); // We remove the listConfiguration completely if it was there before
            ArrayUtility::mergeRecursiveWithOverrule($extListTSArray['listConfig'], [$listIdentifier => $customTSArray]);
        }

        if (!array_key_exists($listIdentifier, $extListTSArray['listConfig'])) {
            throw new \Exception('No listconfig with listIdentifier ' . $listIdentifier . ' defined on this page!', 1284655053);
        }

        $extListTSArray['listIdentifier'] = $listIdentifier;

        return $extListTSArray;
    }



    /**
     * Get current typoscript settings for pt_extlist plugin
     */
    protected static function getCurrentTyposcript()
    {
        if (self::$extListTyposcript !== null) {
            return self::$extListTyposcript;
        }
        
        if (TYPO3_MODE === 'BE') {
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
    protected static function getTyposcriptOfCurrentBackendPID()
    {
        $configurationManager = GeneralUtility::makeInstance(ObjectManager::class)->get(BackendConfigurationManager::class); /* @var $configurationManager BackendConfigurationManager */
        $completeTS = $configurationManager->getTypoScriptSetup();
        return GeneralUtility::makeInstance(TypoScriptService::class)->convertTypoScriptArrayToPlainArray($completeTS['plugin.']['tx_ptextlist.']);
    }



    /**
     * Retrieve Typoscript Configuration from selected frontend pid
     *
     * @return array typoscript array
     */
    protected static function getTyposcriptOfCurrentFrontendPID()
    {
        $configurationManager = GeneralUtility::makeInstance(ObjectManager::class)->get(FrontendConfigurationManager::class); /* @var $configurationManager FrontendConfigurationManager */
        $completeTS = $configurationManager->getTypoScriptSetup();
        return GeneralUtility::makeInstance(TypoScriptService::class)->convertTypoScriptArrayToPlainArray($completeTS['plugin.']['tx_ptextlist.']);
    }
}
