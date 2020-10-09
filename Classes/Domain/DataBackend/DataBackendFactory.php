<?php
namespace PunktDe\PtExtlist\Domain\DataBackend;


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

use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtlist\Domain\AbstractComponentFactory;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderFactory;
use PunktDe\PtExtlist\Domain\DataBackend\Mapper\MapperFactory;
use PunktDe\PtExtlist\Domain\Model\Filter\FilterboxCollectionFactory;
use PunktDe\PtExtlist\Domain\Model\Pager\PagerCollectionFactory;
use PunktDe\PtExtlist\Domain\Model\Sorting\SorterFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;


/**
 * Factory for data backend objects
 *
 * This class is no singleton, since we should get different backends for different settings that may change during
 * a single run of a TYPO3 request.
 *
 * @package Domain
 * @subpackage DataBackend
 * @author Michael Knoll
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Domain_DataBackend_DataBackendFactoryTest
 */
class DataBackendFactory extends AbstractComponentFactory
{
    // NO SINGLETON!!! see comment above!
    /**
     * Holds an instance of this class
     *
     * TODO remove later!
     *
     * @var DataBackendFactory
     * @deprecated for deprecated static use. Remove this, once we remove static functions.
     */
    private static $dataBackendFactoryInstance = null;



    /**
     * Holds an associative array of instances of data backend objects
     * Each list identifier holds its own data backend object
     *
     * @var DataBackendInstancesContainer
     */
    private $instancesContainer;



    /**
     * @var ConfigurationBuilderFactory
     */
    private $configurationBuilderFactory;



    /**
     * @var FilterboxCollectionFactory
     */
    private $filterboxCollectionFactory;



    /**
     * @var bool
     */
    private $resetDataBackend = false;



    /**
     * @var MapperFactory
     */
    private $dataMapperFactory;



    /**
     * @var PagerCollectionFactory
     */
    private $pagerCollectionFactory;



    /**
     * @var SorterFactory
     */
    private $sorterFactory;



    /**
     * Returns an instance of this class with optionally given settings injected in associated configuration builder.
     *
     * This is a helper method to create an instance of this class in static environment.
     *
     * @deprecated Remove this method, once all factories are non-static and properly use DI.
     * @param array $settings
     * @return DataBackendFactory
     */
    public static function getInstance(array $settings = null)
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class);
        $instance = $objectManager->get(DataBackendFactory::class); /* @var $instance DataBackendFactory */
        return $instance;
    }



    /**
     * @param ConfigurationBuilderFactory $configurationBuilderFactory
     */
    public function injectConfigurationBuilderFactory(ConfigurationBuilderFactory $configurationBuilderFactory)
    {
        $this->configurationBuilderFactory = $configurationBuilderFactory;
    }



    /**
     * @param DataBackendInstancesContainer $instancesContainer
     */
    public function injectInstancesContainer(DataBackendInstancesContainer $instancesContainer)
    {
        $this->instancesContainer = $instancesContainer;
    }



    /**
     * @param FilterboxCollectionFactory $filterboxCollectionFactory
     */
    public function injectFilterboxCollectionFactory(FilterboxCollectionFactory $filterboxCollectionFactory)
    {
        $this->filterboxCollectionFactory = $filterboxCollectionFactory;
    }



    /**
     * @param Mapper\MapperFactory $mapperFactory
     */
    public function injectMapperFactory(Mapper\MapperFactory $mapperFactory)
    {
        $this->dataMapperFactory = $mapperFactory;
    }



    /**
     * @param PagerCollectionFactory $pagerCollectionFactory
     */
    public function injectPagerCollectionFactory(PagerCollectionFactory $pagerCollectionFactory)
    {
        $this->pagerCollectionFactory = $pagerCollectionFactory;
    }



    /**
     * @param SorterFactory $sorterFactory
     */
    public function injectSorterFactory(SorterFactory $sorterFactory)
    {
        $this->sorterFactory = $sorterFactory;
    }



    /**
     * Initialize object after creation in DI
     */
    public function initializeObject()
    {
        // TODO we need to resolve cyclic dependency here
        $this->filterboxCollectionFactory->setDataBackendFactory($this);
    }



    /**
     * Returns an instance of data backend for a given list identifier.
     *
     * @param string $listIdentifier
     * @param bool $throwExceptionOnNonExistingListIdentifier
     * @return DataBackendInterface
     * @throws \Exception
     * @deprecated Use non-static method!
     */
    public static function getInstanceByListIdentifier($listIdentifier, $throwExceptionOnNonExistingListIdentifier = true)
    {
        self::createStaticInstance();
        if (self::$dataBackendFactoryInstance->instancesContainer->contains($listIdentifier)) {
            return self::$dataBackendFactoryInstance->instancesContainer->get($listIdentifier);
        } else {
            if ($throwExceptionOnNonExistingListIdentifier) {
                throw new \Exception('No data backend found for list identifier ' . $listIdentifier, 1280770617);
            } else {
                return null;
            }
        }
    }



    /**
     * Create new data backend object for given configuration
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @param boolean $resetDataBackend
     * @return AbstractDataBackend
     * @deprecated Use non-static method getDataBackendInstanceByListIdentifier() instead!
     */
    public static function createDataBackend(ConfigurationBuilder $configurationBuilder, $resetDataBackend = false)
    {
        self::createStaticInstance();
        return self::$dataBackendFactoryInstance->getDataBackendInstanceByListIdentifier($configurationBuilder->getListIdentifier(), $resetDataBackend);
    }



    /**
     * Returns data backend instance for given list identifier
     *
     * @param $listIdentifier
     * @param bool $resetDataBackend
     * @return AbstractDataBackend
     */
    public function getDataBackendInstanceByListIdentifier($listIdentifier, $resetDataBackend = false)
    {
        if (!$this->instancesContainer->contains($listIdentifier) || $resetDataBackend) {
            $this->resetDataBackend = $resetDataBackend;
            $this->buildDataBackendForListIdentifier($listIdentifier);
        }
        return $this->instancesContainer->get($listIdentifier);
    }



    /**
     * Returns data backend instance for given configuration builder
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @return AbstractDataBackend
     */
    public function getDataBackendInstanceByConfigurationBuilder(ConfigurationBuilder $configurationBuilder)
    {
        $listIdentifier = $configurationBuilder->getListIdentifier();
        return $this->getDataBackendInstanceByListIdentifier($listIdentifier);
    }



    private static function createStaticInstance()
    {
        if (self::$dataBackendFactoryInstance === null) {
            self::$dataBackendFactoryInstance = GeneralUtility::makeInstance(ObjectManager::class)->get(DataBackendFactory::class);
        }
    }



    private function buildDataBackendForListIdentifier($listIdentifier)
    {
        $configurationBuilderForListIdentifier = $this->configurationBuilderFactory->getInstance($listIdentifier);
        $this->buildDataBackendForConfigurationBuilder($configurationBuilderForListIdentifier);
    }



    private function buildDataBackendForConfigurationBuilder(ConfigurationBuilder $configurationBuilder)
    {
        $dataBackendConfiguration = $configurationBuilder->buildDataBackendConfiguration(); /* @var $dataBackendClassName string */
        $dataBackendClassName = $dataBackendConfiguration->getDataBackendClass();

        $dataBackend = $this->objectManager->get($dataBackendClassName, $configurationBuilder); /* @var $dataBackend AbstractDataBackend */

        // The reference has to be set here because otherwise every filter will create the databackend again -> recursion!
        $this->instancesContainer->set($dataBackend);

        // Check whether backend class implements backend interface
        Assert::isTrue($dataBackend instanceof DataBackendInterface, ['message' => 'Data Backend class ' . $dataBackendClassName . ' does not implement DataBackendInterface 1280400022']);

        $dataBackend->_injectBackendConfiguration($configurationBuilder->buildDataBackendConfiguration());
        $dataBackend->_injectFieldConfigurationCollection($configurationBuilder->buildFieldsConfiguration());
        $dataBackend->_injectDataMapper($this->getDataMapper($configurationBuilder));
        $dataBackend->_injectDataSource($this->getDataSource($dataBackendClassName, $configurationBuilder));
        $dataBackend->_injectPagerCollection($this->getPagerCollection($configurationBuilder));
        $dataBackend->_injectSorter($this->getSorter($configurationBuilder));

        $dataBackend->_injectFilterboxCollection($this->getfilterboxCollection($configurationBuilder));

        if ($this->getQueryInterpreter($configurationBuilder) != null) {
            $dataBackend->_injectQueryInterpreter($this->getQueryInterpreter($configurationBuilder));
        }

        $dataBackend->init();
    }



    private function getDataSource($dataBackendClassName, ConfigurationBuilder $configurationBuilder)
    {
        // Use data backend class to create data source, as only backend knows which data source to use and how to configure it!
        $dataSource = call_user_func($dataBackendClassName . '::createDataSource', $configurationBuilder);
        return $dataSource;
    }



    private function getDataMapper(ConfigurationBuilder $configurationBuilder)
    {
        $dataMapper = $this->dataMapperFactory->createDataMapper($configurationBuilder);
        return $dataMapper;
    }



    private function getFilterboxCollection(ConfigurationBuilder $configurationBuilder)
    {
        $filterboxCollection = $this->filterboxCollectionFactory->createInstance($configurationBuilder, $this->resetDataBackend);
        return $filterboxCollection;
    }



    private function getPagerCollection(ConfigurationBuilder $configurationBuilder)
    {
        return $this->pagerCollectionFactory->getInstance($configurationBuilder);
    }



    private function getQueryInterpreter(ConfigurationBuilder $configurationBuilder)
    {
        $backendConfiguration = $configurationBuilder->buildDataBackendConfiguration();
        $queryInterpreterClassName = $backendConfiguration->getQueryInterpreterClass();
        $queryInterpreter = new $queryInterpreterClassName;

        return $queryInterpreter;
    }



    private function getSorter(ConfigurationBuilder $configurationBuilder)
    {
        return $this->sorterFactory->getInstance($configurationBuilder);
    }
}
