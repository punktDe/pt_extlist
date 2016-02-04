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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class implements a base testcase for pt_extlist testcases
 *
 * TODO we should use pt_extbase base testcase to be extended here. Add some generic functions there!
 *
 * @package Tests
 * @author Michael Knoll 
 */
abstract class Tx_PtExtlist_Tests_BaseTestcase extends Tx_PtExtbase_Tests_Unit_AbstractBaseTestcase
{
    protected $extBaseSettings = array();

    /**
     * @var Tx_Phpunit_Framework
     */
    protected $testingFramework;

    
    protected $extBaseSettingsString = '
    plugin.tx_ptextlist.settings.persistence.storagePid = 12
    
    extensionName = PtExtlist
    pluginName = pi1
    controller = List
    action = list
    switchableControllerActions {
       10 {
           controller = List
           action = list
       }
    
    }
    
    # Required for requestBuilder
    
    persistence{
        enableAutomaticCacheClearing = 1
        updateReferenceIndex = 0
        classes {
            Tx_Extbase_Domain_Model_FrontendUser {
                mapping {
                    tableName = fe_users
                    recordType = Tx_Extbase_Domain_Model_FrontendUser
                    columns {
                        lockToDomain.mapOnProperty = lockToDomain
                    }
                }
            }
            Tx_Extbase_Domain_Model_FrontendUserGroup {
                mapping {
                    tableName = fe_groups
                    recordType =
                    columns {
                        lockToDomain.mapOnProperty = lockToDomain
                    }
                }
            }
        }
    }';
    
    
    
    /**
     * Holds a configuration builder mock for testcase
     * @var Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock
     */
    protected $configurationBuilderMock = null;
    
    
    
    /**
     * Holds settings for testcase setup
     * @var array
     */
    protected $settings = array();
    
    
    
    public function setup()
    {
        $typoScriptParser = GeneralUtility::makeInstance('TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser'); /* @var $typoScriptParser \TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser */
        $typoScriptParser->parse($this->extBaseSettingsString);

        $this->extBaseSettings = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\TypoScriptService')->convertTypoScriptArrayToPlainArray($typoScriptParser->setup);

        $this->testingFramework = new Tx_Phpunit_Framework('pt_extlist');
    }
    
    
    
    /**
     * Initializes default configuration builder mock used throughout different testcases
     */
    protected function initDefaultConfigurationBuilderMock($overwriteSettings = null)
    {
        $this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($this->settings, $overwriteSettings);
    }
    
    
    
    /**
     * Returns a renderer configuration created by current configuration builder mock settings
     *
     * @return Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig
     */
    public function getRendererConfiguration()
    {
        $rendererChainConfig = Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfigFactory::getInstance($this->configurationBuilderMock);
        $rendererConfiguration = $rendererChainConfig->getItemById('100');
        return $rendererConfiguration;
    }



    /**
     * Returns mock object for given class name with methods given in override methods overwritten in returned object.
     *
     * @param string $className Name of class to be mocked
     * @param array $overrideMethods Methods to be overwritten in mocked class
     * @return PHPUnit_Framework_MockObject_MockObject Mock instance of $className
     */
    public function getSimpleMock($className, $overrideMethods = array())
    {
        return $this->getMock($className, array(), $overrideMethods, '', false);
    }



    public function getDataBackendFactoryMockForListConfigurationAndListIdentifier($listConfigurationArray, $listIdentifier)
    {
        return $this->getDataBackendFactoryMock(
            array(
                'listIdentifier' => $listIdentifier,
                'prototype' => array(),
                'listConfig' => array(
                    $listIdentifier => $listConfigurationArray
                )
            ),
            $listIdentifier
        );
    }



    /**
     * Returns a data backend factory for given TS settings and list identifier. If no list identifier is given,
     * list identifier is taken from $settings['listIdentifier'].
     *
     * @param array $typoScriptSettingsForListIdentifier The settings array as we get it from configurationManager ATTENTION: This is not the settings array for the list identifier!!!
     * @param string $listIdentifier
     * @return Tx_PtExtlist_Domain_DataBackend_DataBackendFactory
     * @throws Exception if no list identifier can be determined
     */
    public function getDataBackendFactoryMock($typoScriptSettingsForListIdentifier, $listIdentifier = null)
    {
        if (!$listIdentifier) {
            $listIdentifier = $typoScriptSettingsForListIdentifier['listIdentifier'];
        }
        if (!$listIdentifier) {
            throw new Exception('No list identifier was given and no list identifier could be found in given TS settings. 1363856864');
        }
        $configurationManagerMock = $this->getMock('\TYPO3\CMS\Extbase\Configuration\ConfigurationManager', array('getConfiguration'), array(), '', false);
        $configurationManagerMock->expects($this->any())->method('getConfiguration')->will($this->returnValue($typoScriptSettingsForListIdentifier)); /* @var $configurationManagerMock \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */

        $configurationBuilderInstancesContainer = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderInstancesContainer();

        $configurationBuilderFactory = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory();
        $configurationBuilderFactory->injectConfigurationManager($configurationManagerMock);
        $configurationBuilderFactory->setSettings($typoScriptSettingsForListIdentifier);
        $configurationBuilderFactory->injectConfigurationBuilderInstancesContainer($configurationBuilderInstancesContainer);
        $configurationBuilderFactory->getInstance($listIdentifier);

        $instancesContainer = GeneralUtility::makeInstance('Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer'); /* @var $instancesContainer Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer  */

        $objectManagerMock = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager', array('get'), array(), '', false);
        $objectManagerMock->expects($this->any())
                ->method('get')
                ->with('Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend', $configurationBuilderFactory->getInstance($listIdentifier))
                ->will($this->returnValue(new Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend($configurationBuilderFactory->getInstance($listIdentifier)))); /* @var $objectManagerMock \TYPO3\CMS\Extbase\Object\ObjectManager */

        $dataBackendFactory = new Tx_PtExtlist_Domain_DataBackend_DataBackendFactory();
        $dataBackendFactory->injectObjectManager($objectManagerMock);
        $dataBackendFactory->injectConfigurationBuilderFactory($configurationBuilderFactory);
        $dataBackendFactory->injectInstancesContainer($instancesContainer);
        $dataBackendFactory->injectMapperFactory($this->objectManager->get('Tx_PtExtlist_Domain_DataBackend_Mapper_MapperFactory'));
        $dataBackendFactory->injectFilterboxCollectionFactory($this->objectManager->get('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollectionFactory'));
        $dataBackendFactory->injectPagerCollectionFactory($this->objectManager->get('Tx_PtExtlist_Domain_Model_Pager_PagerCollectionFactory'));
        $dataBackendFactory->injectSorterFactory($this->objectManager->get('Tx_PtExtlist_Domain_Model_Sorting_SorterFactory'));

        return $dataBackendFactory;
    }
}
