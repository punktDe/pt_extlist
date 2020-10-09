<?php
namespace PunktDe\PtExtlist\Tests;

/*
 *  (c) 2020 punkt.de GmbH - Karlsruhe, Germany - https://punkt.de
 *  All rights reserved.
 */


use PHPUnit\Framework\MockObject\MockObject;
use PunktDe\PtExtbase\Testing\Unit\AbstractBaseTestcase;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderFactory;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderInstancesContainer;
use PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererChainConfigFactory;
use PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererConfig;
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory;
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendInstancesContainer;
use PunktDe\PtExtlist\Domain\DataBackend\Mapper\MapperFactory;
use PunktDe\PtExtlist\Domain\DataBackend\Typo3DataBackend\Typo3DataBackend;
use PunktDe\PtExtlist\Domain\Model\Filter\FilterboxCollectionFactory;
use PunktDe\PtExtlist\Domain\Model\Pager\PagerCollectionFactory;
use PunktDe\PtExtlist\Domain\Model\Sorting\SorterFactory;
use PunktDe\PtExtlist\Tests\Domain\Configuration\ConfigurationBuilderMock;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class implements a base testcase for pt_extlist testcases
 *
 * TODO we should use pt_extbase base testcase to be extended here. Add some generic functions there!
 *
 * @package Tests
 * @author Michael Knoll 
 */
abstract class PtExtlistBaseTestcase extends AbstractBaseTestcase
{
    protected $extBaseSettings = [];

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
            PunktDe_Extbase_Domain_Model_FrontendUser {
                mapping {
                    tableName = fe_users
                    recordType = PunktDe_Extbase_Domain_Model_FrontendUser
                    columns {
                        lockToDomain.mapOnProperty = lockToDomain
                    }
                }
            }
            PunktDe_Extbase_Domain_Model_FrontendUserGroup {
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
     * @var ConfigurationBuilderMock
     */
    protected $configurationBuilderMock = null;
    
    
    
    /**
     * Holds settings for testcase setup
     * @var array
     */
    protected $settings = [];
    
    
    
    public function setup(): void
    {
        $typoScriptParser = GeneralUtility::makeInstance(TypoScriptParser::class); /* @var $typoScriptParser \TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser */
        $typoScriptParser->parse($this->extBaseSettingsString);

        $this->extBaseSettings = GeneralUtility::makeInstance(TypoScriptService::class)->convertTypoScriptArrayToPlainArray($typoScriptParser->setup);
    }
    
    
    
    /**
     * Initializes default configuration builder mock used throughout different testcases
     */
    protected function initDefaultConfigurationBuilderMock($overwriteSettings = null)
    {
        $this->configurationBuilderMock = ConfigurationBuilderMock::getInstance($this->settings, $overwriteSettings);
    }
    
    
    
    /**
     * Returns a renderer configuration created by current configuration builder mock settings
     *
     * @return RendererConfig
     */
    public function getRendererConfiguration()
    {
        $rendererChainConfig = RendererChainConfigFactory::getInstance($this->configurationBuilderMock);
        $rendererConfiguration = $rendererChainConfig->getItemById('100');
        return $rendererConfiguration;
    }



    /**
     * Returns mock object for given class name with methods given in override methods overwritten in returned object.
     *
     * @param string $className Name of class to be mocked
     * @param array $overrideMethods Methods to be overwritten in mocked class
     * @return MockObject Mock instance of $className
     */
    public function getSimpleMock($className, $overrideMethods = [])
    {
        return $this->getMock($className, [], $overrideMethods, '', false);
    }



    public function getDataBackendFactoryMockForListConfigurationAndListIdentifier($listConfigurationArray, $listIdentifier)
    {
        return $this->getDataBackendFactoryMock(
            [
                'listIdentifier' => $listIdentifier,
                'prototype' => [],
                'listConfig' => [
                    $listIdentifier => $listConfigurationArray
                ]
            ],
            $listIdentifier
        );
    }



    /**
     * Returns a data backend factory for given TS settings and list identifier. If no list identifier is given,
     * list identifier is taken from $settings['listIdentifier'].
     *
     * @param array $typoScriptSettingsForListIdentifier The settings array as we get it from configurationManager ATTENTION: This is not the settings array for the list identifier!!!
     * @param string $listIdentifier
     * @return DataBackendFactory
     * @throws \Exception if no list identifier can be determined
     */
    public function getDataBackendFactoryMock($typoScriptSettingsForListIdentifier, $listIdentifier = null)
    {
        if (!$listIdentifier) {
            $listIdentifier = $typoScriptSettingsForListIdentifier['listIdentifier'];
        }
        if (!$listIdentifier) {
            throw new \Exception('No list identifier was given and no list identifier could be found in given TS settings. 1363856864');
        }
        $configurationManagerMock = $this->getMock(ConfigurationManager::class, ['getConfiguration'], [], '', false);
        $configurationManagerMock->expects($this->any())->method('getConfiguration')->will($this->returnValue($typoScriptSettingsForListIdentifier)); /* @var $configurationManagerMock \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */

        $configurationBuilderInstancesContainer = new ConfigurationBuilderInstancesContainer();

        $configurationBuilderFactory = new ConfigurationBuilderFactory();
        $configurationBuilderFactory->injectConfigurationManager($configurationManagerMock);
        $configurationBuilderFactory->setSettings($typoScriptSettingsForListIdentifier);
        $configurationBuilderFactory->injectConfigurationBuilderInstancesContainer($configurationBuilderInstancesContainer);
        $configurationBuilderFactory->getInstance($listIdentifier);

        $instancesContainer = GeneralUtility::makeInstance('DataBackendInstancesContainer'); /* @var $instancesContainer DataBackendInstancesContainer  */

        $objectManagerMock = $this->getMock(ObjectManager::class, ['get'], [], '', false);
        $objectManagerMock->expects($this->any())
                ->method('get')
                ->with('Typo3DataBackend_Typo3DataBackend', $configurationBuilderFactory->getInstance($listIdentifier))
                ->will($this->returnValue(new Typo3DataBackend($configurationBuilderFactory->getInstance($listIdentifier)))); /* @var $objectManagerMock \TYPO3\CMS\Extbase\Object\ObjectManager */

        $dataBackendFactory = new DataBackendFactory();
        $dataBackendFactory->injectObjectManager($objectManagerMock);
        $dataBackendFactory->injectConfigurationBuilderFactory($configurationBuilderFactory);
        $dataBackendFactory->injectInstancesContainer($instancesContainer);
        $dataBackendFactory->injectMapperFactory($this->objectManager->get(MapperFactory::class));
        $dataBackendFactory->injectFilterboxCollectionFactory($this->objectManager->get(FilterboxCollectionFactory::class));
        $dataBackendFactory->injectPagerCollectionFactory($this->objectManager->get(PagerCollectionFactory::class));
        $dataBackendFactory->injectSorterFactory($this->objectManager->get(SorterFactory::class));

        return $dataBackendFactory;
    }
}
