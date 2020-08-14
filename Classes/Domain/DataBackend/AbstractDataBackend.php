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

use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\DataBackend\DataBackendConfiguration;
use PunktDe\PtExtlist\Domain\DataBackend\DataSource\AbstractDataSource;
use PunktDe\PtExtlist\Domain\DataBackend\Mapper\MapperInterface;
use PunktDe\PtExtlist\Domain\Model\Bookmark\BookmarkManager;
use PunktDe\PtExtlist\Domain\Model\Filter\FilterboxCollection;
use PunktDe\PtExtlist\Domain\Model\Lists\Aggregates\AggregateListFactory;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\ListHeader;
use PunktDe\PtExtlist\Domain\Model\Lists\ListData;
use PunktDe\PtExtlist\Domain\Model\Pager\PagerCollection;
use PunktDe\PtExtlist\Domain\Model\Sorting\Sorter;

/**
 * Abstract class as base class for all data backends
 *
 * @package Domain
 * @subpackage DataBackend
 * @author Michael Knoll
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Domain_DataBackend_AbstractDataBackendTest
 */
abstract class AbstractDataBackend implements DataBackendInterface
{
    /**
     * Holds backend configuration for current backend
     *
     * @var DataBackendConfiguration
     */
    protected $backendConfiguration;


    /**
     * @var string List identifier of list this data backend belongs to
     */
    protected $listIdentifier;


    /**
     *
     * @var MapperInterface
     */
    protected $dataMapper;


    /**
     * @var \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder
     */
    protected $configurationBuilder;


    /**
     * @var FilterboxCollection
     */
    protected $filterboxCollection;


    /**
     * @var ListHeader
     */
    protected $listHeader;


    /**
     * @var ListData
     */
    protected $listData = null;


    /**
     * @var ListData
     */
    protected $aggregateListData;


    /**
     * @var PagerCollection
     */
    protected $pagerCollection;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;


    /**
     * Holds an instance for data source
     *
     * @var AbstractDataSource
     */
    protected $dataSource;


    /**
     * Holds an instance of a field collection where field configurations can be found
     *
     * @var FieldConfigCollection
     */
    protected $fieldConfigurationCollection = [];


    /**
     * Holds an instance of a query interpreter to be used for
     * query objects
     *
     * TODO using abstract class as type here makes no sense!
     *
     * @var AbstractQueryInterpreter
     */
    protected $queryInterpreter;


    /**
     * Holds an instance of bookmark manager
     *
     * @var BookmarkManager
     */
    protected $bookmarkManager;


    /**
     * Holds an instance of a sorter
     *
     * @var Sorter
     */
    protected $sorter;


    /**
     * Constructor for data backend
     *
     * @param ConfigurationBuilder $configurationBuilder
     */
    public function __construct(ConfigurationBuilder $configurationBuilder)
    {
        $this->configurationBuilder = $configurationBuilder;
        $this->listIdentifier = $configurationBuilder->getListIdentifier();
    }


    /**
     * Per default, a data backend does not require a data source, so we return null here
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @return unknown
     */
    public static function createDataSource(ConfigurationBuilder $configurationBuilder)
    {
        return null;
    }


    /**
     * Injects backend configuration for current backend
     *
     * @param DataBackendConfiguration $backendConfiguration
     */
    public function _injectBackendConfiguration(DataBackendConfiguration $backendConfiguration)
    {
        $this->backendConfiguration = $backendConfiguration;
    }


    /**
     * Init method
     */
    public function init()
    {
        $this->initBackendByTsConfig();
        $this->initBackend();
    }


    /**
     * Init method to be overwritten in the
     * concrete backends
     */
    protected function initBackend()
    {
    }


    /**
     * Init the backend specific configuration from TS config
     */
    protected function initBackendByTsConfig()
    {
    }


    /**
     * inject the objectManager
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     * @return void
     */
    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }


    /**
     * Injector for data mapper
     *
     * @param MapperInterface $mapper
     */
    public function _injectDataMapper(MapperInterface $mapper)
    {
        $this->dataMapper = $mapper;
    }


    /**
     * Injector for field configuration collection
     *
     * @param FieldConfigCollection $fieldConfigurationCollection
     */
    public function _injectFieldConfigurationCollection(FieldConfigCollection $fieldConfigurationCollection)
    {
        $this->fieldConfigurationCollection = $fieldConfigurationCollection;
    }


    /**
     * Injector for filter box collection
     *
     * @param FilterboxCollection $filterboxCollection
     */
    public function _injectFilterboxCollection(FilterboxCollection $filterboxCollection)
    {
        $this->filterboxCollection = $filterboxCollection;
    }


    /**
     * Injector for pager collection
     *
     * @param PagerCollection $pagerCollection
     */
    public function _injectPagerCollection(PagerCollection $pagerCollection)
    {
        $this->pagerCollection = $pagerCollection;
    }


    /**
     * Injector for data source
     *
     * @param mixed $dataSource
     */
    public function _injectDataSource($dataSource)
    {
        $this->dataSource = $dataSource;
    }


    /**
     * Injector for List Header
     *
     * @param ListHeader $listHeader
     */
    public function _injectListHeader(ListHeader $listHeader)
    {
        $this->listHeader = $listHeader;
    }


    /**
     * Injector for query interpreter
     *
     * @param mixed $queryInterpreter
     */
    public function _injectQueryInterpreter($queryInterpreter)
    {
        $this->queryInterpreter = $queryInterpreter;
    }


    /**
     * Injector for bookmark manager
     *
     * @param BookmarkManager $bookmarkManager
     */
    public function _injectBookmarkManager(BookmarkManager $bookmarkManager)
    {
        $this->bookmarkManager = $bookmarkManager;
    }


    /**
     * Injector for sorter
     *
     * @param Sorter $sorter
     * @return void
     */
    public function _injectSorter(Sorter $sorter)
    {
        $this->sorter = $sorter;
    }


    /**
     * Returns list identifier of the list to which this backend belongs to
     *
     * @return String
     */
    public function getListIdentifier()
    {
        return $this->configurationBuilder->getListIdentifier();
    }


    /**
     * @return ConfigurationBuilder ;
     */
    public function getConfigurationBuilder()
    {
        return $this->configurationBuilder;
    }


    /**
     * @return AbstractDataSource
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }


    /**
     * Returns filterbox collection attached to this data backend
     *
     * @return FilterboxCollection Filterbox collection attached to this data backend
     */
    public function getFilterboxCollection()
    {
        return $this->filterboxCollection;
    }


    /**
     * Returns the pager collection attached to this data backend.
     *
     * @return PagerCollection The pager collection attached to this data backend.
     */
    public function getPagerCollection()
    {
        return $this->pagerCollection;
    }


    /**
     * Returns the listHeader with sorting informations
     * @return ListHeader
     */
    public function getListHeader()
    {
        return $this->listHeader;
    }


    /**
     * Returns raw list data
     *
     * @return ListData
     */
    public function getListData()
    {
        if ($this->listData === null) {
            // TODO: buildListData() should set listData on backend itself!
            $this->listData = $this->buildListData();
        }

        return $this->listData;
    }


    /**
     *
     * Build the listData and cache it in $this->listData
     */
    abstract protected function buildListData();


    /**
     * ResetlisData and query
     */
    public function resetListDataCache()
    {
        unset($this->listData);
        unset($this->listQueryParts);
    }


    /**
     * (non-PHPdoc)
     * @see Classes/Domain/DataBackend/DataBackendInterface::getAggregateListData()
     * @return ListData
     */
    public function getAggregateListData()
    {
        return AggregateListFactory::getAggregateListData($this, $this->configurationBuilder);
    }


    /**
     * Returns associated field config collection
     *
     * @return FieldConfigCollection
     */
    public function getFieldConfigurationCollection()
    {
        return $this->fieldConfigurationCollection;
    }


    /**
     * Returns sorter registered for this data backend
     *
     * @return Sorter
     */
    public function getSorter()
    {
        return $this->sorter;
    }


    /**
     * Reset sorting if sorting changes due to GP vars
     *
     * DOES NOT RESET SORTING TO DEFAULT SORTING!!! @see resetSortingToDefault()
     */
    public function resetSorting()
    {
        $this->sorter->reset();
        $this->resetListDataCache();
    }


    /**
     * Reset sorting to default sorting (configured in TS)
     */
    public function resetSortingToDefault()
    {
        $this->sorter->resetToDefault();
        $this->resetListDataCache();
    }
}
