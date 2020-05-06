<?php

namespace PunktDe\PtExtlist\Utility;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class contains utility functions to access extlist objects
 * form external dependent plugins
 *  
 * DEPRECATED!
 *  
 * @author Daniel Lienert 
 * @package Utility
 * @deprecated
 */
class ExternalPlugin
{
    /**
     * Initialize and return a DataBackend with the given listIndentifier
     *
     * @param string $listIdentifier
     * @return \PunktDe\PtExtlist\Domain\DataBackend\AbstractDataBackend
     */
    public static function getDataBackend($listIdentifier)
    {
        $extListBackend = \PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory::getInstanceByListIdentifier($listIdentifier, false);

        if ($extListBackend == null) {
            $extListTs = self::getExtListTyposcriptSettings($listIdentifier);
            self::loadLifeCycleManager();

            // TODO Remove this, once we have DI

            $configurationBuilderFactory = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory'); /* @var $configurationBuilderFactory Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory */
            $configurationBuilderFactory->setSettings($extListTs);
            $configurationBuilder = $configurationBuilderFactory->getInstance($listIdentifier);

            $extListBackend = \PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory::createDataBackend($configurationBuilder);
        }

        return $extListBackend;
    }



    /**
     * Get the databackend by a custom list configuration ts array
     * The Typoscript is identified by the given listIdentifier and merged with the extlist configuration
     *
     * @param array $customTSArray Custom typoscript list configuration in extBase format
     * @param string $listIdentifier a listIdentifier to identify the custom list
     * @return \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface
     */
    public static function getDataBackendByCustomConfiguration(array $customTSArray, $listIdentifier)
    {
        try {
            $configurationBuilderFactory = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory'); /* @var $configurationBuilderFactory Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory */
            $configurationBuilder = $configurationBuilderFactory->getInstance($listIdentifier);
        } catch (Exception $e) {
            $extListTs = self::getExtListTyposcriptSettings($listIdentifier, $customTSArray);
            self::loadLifeCycleManager();

            // TODO Remove this, once we have DI

            $configurationBuilderFactory = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory'); /* @var $configurationBuilderFactory Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory */
            $configurationBuilderFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory'); /* @var $configurationBuilderFactory Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory */
            $configurationBuilderFactory->setSettings($extListTs);
            $configurationBuilder = $configurationBuilderFactory->getInstance($listIdentifier);
        }

        return  \PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory::createDataBackend($configurationBuilder);
    }



    /**
     * Return the list object by listIdentifier
     *
     * @param \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface $dataBackend
     * @return \PunktDe\PtExtlist\Domain\Model\Lists\List
     */
    public static function getListByDataBackend(\PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface $dataBackend)
    {
        return GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')
                ->get('Tx_PtExtlist_Domain_Model_List_ListFactory')
                ->createList($dataBackend, $dataBackend->getConfigurationBuilder());
    }



    /**
     * Read the Session data into the cache
     */
    protected static function loadLifeCycleManager()
    {

        // TODO use DI here once refactoring is finished

        $objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager'); /* @var $objectManager \TYPO3\CMS\Extbase\Object\ObjectManager */
        $lifecycleManager = $objectManager->get('\PunktDe\PtExtbase\Lifecycle\Manager'); /* @var $lifecycleManager \PunktDe\PtExtbase\Lifecycle\Manager */

        $sessionPersistenceManagerBuilder = $objectManager->get('PunktDe_PtExtbase_State_Session_SessionPersistenceManagerBuilder'); /* @var $sessionPersistenceManagerBuilder PunktDe_PtExtbase_State_Session_SessionPersistenceManagerBuilder */
        $sessionPersistenceManager = $sessionPersistenceManagerBuilder->getInstance();
        $lifecycleManager->register($sessionPersistenceManager);

        // SET LIFECYCLE TO START -> read session data into cache
        $lifecycleManager->updateState(\PunktDe\PtExtbase\Lifecycle\Manager::START);
    }



    /**
     * Get Typoscript for defined listIdentifier
     *
     * @param string $listIdentifier
     * @param array $customTSArray custom ts array
     * @throws Exception
     * @return array
     */
    protected static function getExtListTyposcriptSettings($listIdentifier, $customTSArray = null)
    {
        $extListTS = PunktDe_PtExtbase_Div::getTS('plugin.tx_ptextlist.settings.');
        $extListTSArray = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\TypoScriptService')->convertTypoScriptArrayToPlainArray($extListTS);

        if (!is_array($extListTSArray['listConfig'])) {
            $extListTSArray['listConfig'] = [];
        }

        if (is_array($customTSArray)) {
            \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($extListTSArray['listConfig'], [$listIdentifier => $customTSArray]);
        }

        if (!array_key_exists($listIdentifier, $extListTSArray['listConfig'])) {
            throw new Exception('No listconfig with listIdentifier ' . $listIdentifier . ' defined on this page! 1284655053');
        }

        $extListTSArray['listIdentifier'] = $listIdentifier;

        return $extListTSArray;
    }
}
