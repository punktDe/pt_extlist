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
use PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\DataBackend\DataBackendConfiguration;
use PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig;
use PunktDe\PtExtlist\Domain\Model\Bookmark\BookmarkManager;
use PunktDe\PtExtlist\Domain\Model\Filter\FilterboxCollection;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\ListHeader;
use PunktDe\PtExtlist\Domain\Model\Lists\IterationListData;
use PunktDe\PtExtlist\Domain\Model\Lists\ListData;
use PunktDe\PtExtlist\Domain\Model\Pager\PagerCollection;
use PunktDe\PtExtlist\Domain\Model\Sorting\Sorter;
use PunktDe\PtExtlist\Domain\QueryObject\Query;


/**
 * Interface for all data backends
 *
 * @package Domain
 * @subpackage DataBackend
 * @author Michael Knoll 
 * @author Daniel Lienert 
 */
interface DataBackendInterface
{
    /**
     * Returns list identifier of list to which this backend belongs to.
     *
     * @return string List identifier of associated list
     */
    public function getListIdentifier();



    /**
     * Creates an instance of data source object to be used with current backend
     *
     * @param ConfigurationBuilder $configurationBuilder
     */
    public static function createDataSource(ConfigurationBuilder $configurationBuilder);
    
    
    
    /**
     * Returns mapped List structure
     *  
     * @return ListData
     */
    public function getListData();



    /**
     * @abstract
     * @return IterationListData
     */
    public function getIterationListData();


    
    /**
     * Returns the list header
     *  
     * @return ListHeader
     */
    public function getListHeader();
    
    
    
    /**
     * Returns th aggregate data
     *  
     * @return ListData
     */
    public function getAggregateListData();
    
    
    
    /**
     * Returns raw data for all filters excluding given filters. 
     *  
     * Result is given as associative array with fields given in query object.
     *
     * @param Query $groupDataQuery Query that defines which group data to get
     * @param array $excludeFilters List of filters to be excluded from query (<filterboxIdentifier>.<filterIdentifier>)
     * @param FilterConfig $filterConfig
     * @return array Array of group data with given fields as array keys
     */
    public function getGroupData(Query $groupDataQuery, $excludeFilters = [],
                                 FilterConfig $filterConfig = null);
    
    
    
    /**
     * Returns field configuraiton collection
     *
     * @return FieldConfigCollection
     */
    public function getFieldConfigurationCollection();
    
    
    
    /**
     * Injector for field config collection
     *
     * @param FieldConfigCollection $fieldConfigCollection
     */
    public function _injectFieldConfigurationCollection(FieldConfigCollection  $fieldConfigCollection);
    
  
    
    /**
     * Injector for pager collection.
     *
     * @param PagerCollection $pagerCollection
     *
     */
    public function _injectPagerCollection(PagerCollection $pagerCollection);
    
    
    
    /**
     * Returns the number of items for current settings without pager settings
     *
     * @return integer Total number of items for current data set
     */
    public function getTotalItemsCount();



    /**
     * Return an aggregate for a field and with a method defined in the given config
     *
     * @param AggregateConfigCollection $aggregateDataConfigCollection
     * @return
     */
    public function getAggregatesByConfigCollection(AggregateConfigCollection $aggregateDataConfigCollection);
    
    
    
    /**
     * Injector for data mapper
     *
     * @param MapperInterface $dataMapper
     */
    public function _injectDataMapper(MapperInterface $dataMapper);
    
    
    
    /**
     * Injector for data source
     *
     * @param mixed $dataSource
     */
    public function _injectDataSource($dataSource);
    
    
    
    /**
     * Injector for list header
     *
     * @param ListHeader $listHeader
     */
    #public function _injectListHeader(ListHeader $listHeader);
    /**
     * Injector for sorter
     *  
     * @param Sorter $sorter
     * @return void
     */
    public function _injectSorter(Sorter $sorter);

    
    
    /**
     * Injector for filterbox collection
     *
     * @param FilterBoxCollection $filterboxCollection
     */
    public function _injectFilterboxCollection(FilterboxCollection $filterboxCollection);
    
    
    
    /**
     * Injector for backend configuration
     *
     * @param DataBackendConfiguration $backendConfiguration
     */
    public function _injectBackendConfiguration(DataBackendConfiguration $backendConfiguration);
    
    
    
    /**
     * Injector for query interpreter
     *
     * @param mixed $queryInterpreter
     */
    public function _injectQueryInterpreter($queryInterpreter);
    
    
    
    /**
     * Injector for bookmark manager
     *
     * @param BookmarkManager $bookmarkManager
     */
    public function _injectBookmarkManager(BookmarkManager $bookmarkManager);
    
    
    
    /**
     * Returns associated filterbox collection
     *  
     * @return FilterboxCollection Associated filterbox collection
     */
    public function getFilterboxCollection();

    
    
    /**
     * Returns associated pager collection
     *  
     * @return PagerCollection
     */
    public function getPagerCollection();
    
    
    
    /**
     *  
     * Reset the List Data Cache
     */
    public function resetListDataCache();


    
    /**
     * Returns sorter for this data backend
     *  
     * @return Sorter
     */
    public function getSorter();



    /**
     * Reset sorting if sorting changes due to GP vars
     *
     * DOES NOT RESET SORTING TO DEFAULT SORTING!!! @see resetSortingToDefault()
     */
    public function resetSorting();



    /**
     * Reset sorting to default sorting (configured in TS)
     */
    public function resetSortingToDefault();



    /**
     * @return ConfigurationBuilder
     */
    public function getConfigurationBuilder();
}
