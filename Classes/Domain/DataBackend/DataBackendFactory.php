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
class DataBackendFactory extends \PunktDe\PtExtlist\Domain\AbstractComponentFactory
{
    // NO SINGLETON!!! see comment above!
    /**
     * Holds an instance of this class
     *
     * TODO remove later!
     *
     * @var \PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory
     * @deprecated for deprecated static use. Remove this, once we remove static functions.
     */
    private static $dataBackendFactoryInstance = null;



    /**
     * Holds an associative array of instances of data backend objects
     * Each list identifier holds its own data backend object
     *
     * @var \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInstancesContainer
     */
    private $instancesContainer;



    /**
     * @var \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderFactory
     */
    private $configurationBuilderFactory;



    /**
     * @var \PunktDe\PtExtlist\Domain\Model\Filter\FilterboxCollectionFactory
     */
    private $filterboxCollectionFactory;



    /**
     * @var bool
     */
    private $resetDataBackend = false;



    /**
     * @var \PunktDe\PtExtlist\Domain\DataBackend\Mapper\MapperFactory
     */
    private $dataMapperFactory;



    /**
     * @var \PunktDe\PtExtlist\Domain\Model\Pager\PagerCollectionFactory
     */
    private $pagerCollectionFactory;



    /**
     * @var \PunktDe\PtExtlist\Domain\Model\Sorting\SorterFactory
     */
    private $sorterFactory;



    /**
     * Returns an instance of this class with optionally given settings injected in associated configuration builder.
     *
     * This is a helper method to create an instance of this class in static environment.
     *
     * @deprecated Remove this method, once all factories are non-static and properly use DI.
     * @param array $settings
     * @return \PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory
     */
    public static function getInstance(array $settings = null)
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $instance = $objectManager->get('Tx_PtExtlist_Domain_DataBackend_DataBackendFactory'); /* @var $instance Tx_PtExtlist_Domain_DataBackend_DataBackendFactory */
        return $instance;
    }



    /**
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderFactory $configurationBuilderFactory
     */
    public function injectConfigurationBuilderFactory(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderFactory $configurationBuilderFactory)
    {
        $this->configurationBuilderFactory = $configurationBuilderFactory;
    }



    /**
     * @param \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInstancesContainer $instancesContainer
     */
    public function injectInstancesContainer(\PunktDe\PtExtlist\Domain\DataBackend\DataBackendInstancesContainer $instancesContainer)
    {
        $this->instancesContainer = $instancesContainer;
    }



    /**
     * @param \PunktDe\PtExtlist\Domain\Model\Filter\FilterboxCollectionFactory $filterboxCollectionFactory
     */
    public function injectFilterboxCollectionFactory(\PunktDe\PtExtlist\Domain\Model\Filter\FilterboxCollectionFactory $filterboxCollectionFactory)
    {
        $this->filterboxCollectionFactory = $filterboxCollectionFactory;
    }



    /**
     * @param \PunktDe\PtExtlist\Domain\DataBackend\Mapper\MapperFactory $mapperFactory
     */
    public function injectMapperFactory(\PunktDe\PtExtlist\Domain\DataBackend\Mapper\MapperFactory $mapperFactory)
    {
        $this->dataMapperFactory = $mapperFactory;
    }



    /**
     * @param \PunktDe\PtExtlist\Domain\Model\Pager\PagerCollectionFactory $pagerCollectionFactory
     */
    public function injectPagerCollectionFactory(\PunktDe\PtExtlist\Domain\Model\Pager\PagerCollectionFactory $pagerCollectionFactory)
    {
        $this->pagerCollectionFactory = $pagerCollectionFactory;
    }



    /**
     * @param \PunktDe\PtExtlist\Domain\Model\Sorting\SorterFactory $sorterFactory
     */
    public function injectSorterFactory(\PunktDe\PtExtlist\Domain\Model\Sorting\SorterFactory $sorterFactory)
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
     * @return \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface
     * @throws Exception
     * @deprecated Use non-static method!
     */
    public static function getInstanceByListIdentifier($listIdentifier, $throwExceptionOnNonExistingListIdentifier = true)
    {
        self::createStaticInstance();
        if (self::$dataBackendFactoryInstance->instancesContainer->contains($listIdentifier)) {
            return self::$dataBackendFactoryInstance->instancesContainer->get($listIdentifier);
        } else {
            if ($throwExceptionOnNonExistingListIdentifier) {
                throw new Exception('No data backend found for list identifier ' . $listIdentifier, 1280770617);
            } else {
                return null;
            }
        }
    }



    /**
     * Create new data backend object for given configuration
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @param boolean $resetDataBackend
     * @return \PunktDe\PtExtlist\Domain\DataBackend\AbstractDataBackend
     * @deprecated Use non-static method getDataBackendInstanceByListIdentifier() instead!
     */
    public static function createDataBackend(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder, $resetDataBackend = false)
    {
        self::createStaticInstance();
        return self::$dataBackendFactoryInstance->getDataBackendInstanceByListIdentifier($configurationBuilder->getListIdentifier(), $resetDataBackend);
    }



    /**
     * Returns data backend instance for given list identifier
     *
     * @param $listIdentifier
     * @param bool $resetDataBackend
     * @return \PunktDe\PtExtlist\Domain\DataBackend\AbstractDataBackend
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
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @return \PunktDe\PtExtlist\Domain\DataBackend\AbstractDataBackend
     */
    public function getDataBackendInstanceByConfigurationBuilder(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $listIdentifier = $configurationBuilder->getListIdentifier();
        return $this->getDataBackendInstanceByListIdentifier($listIdentifier);
    }



    private static function createStaticInstance()
    {
        if (self::$dataBackendFactoryInstance === null) {
            self::$dataBackendFactoryInstance = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('Tx_PtExtlist_Domain_DataBackend_DataBackendFactory');
        }
    }



    private function buildDataBackendForListIdentifier($listIdentifier)
    {
        $configurationBuilderForListIdentifier = $this->configurationBuilderFactory->getInstance($listIdentifier);
        $this->buildDataBackendForConfigurationBuilder($configurationBuilderForListIdentifier);
    }



    private function buildDataBackendForConfigurationBuilder(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $dataBackendConfiguration = $configurationBuilder->buildDataBackendConfiguration(); /* @var $dataBackendClassName string */
        $dataBackendClassName = $dataBackendConfiguration->getDataBackendClass();

        $dataBackend = $this->objectManager->get($dataBackendClassName, $configurationBuilder); /* @var $dataBackend Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend */

        // The reference has to be set here because otherwise every filter will create the databackend again -> recursion!
        $this->instancesContainer->set($dataBackend);

        // Check whether backend class implements backend interface
        PunktDe_PtExtbase_Assertions_Assert::isTrue($dataBackend instanceof \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface, ['message' => 'Data Backend class ' . $dataBackendClassName . ' does not implement Tx_PtExtlist_Domain_DataBackend_DataBackendInterface 1280400022']);

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



    private function getDataSource($dataBackendClassName, \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        // Use data backend class to create data source, as only backend knows which data source to use and how to configure it!
        $dataSource = call_user_func($dataBackendClassName . '::createDataSource', $configurationBuilder);
        return $dataSource;
    }



    private function getDataMapper(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $dataMapper = $this->dataMapperFactory->createDataMapper($configurationBuilder);
        return $dataMapper;
    }



    private function getFilterboxCollection(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $filterboxCollection = $this->filterboxCollectionFactory->createInstance($configurationBuilder, $this->resetDataBackend);
        return $filterboxCollection;
    }



    private function getPagerCollection(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        return $this->pagerCollectionFactory->getInstance($configurationBuilder);
    }



    private function getQueryInterpreter(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $backendConfiguration = $configurationBuilder->buildDataBackendConfiguration();
        $queryInterpreterClassName = $backendConfiguration->getQueryInterpreterClass();
        $queryInterpreter = new $queryInterpreterClassName;

        return $queryInterpreter;
    }



    private function getSorter(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        return $this->sorterFactory->getInstance($configurationBuilder);
    }
}
