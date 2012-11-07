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

/**
 * Interface for all data backends
 *
 * @package Domain
 * @subpackage DataBackend
 * @author Michael Knoll 
 * @author Daniel Lienert 
 */
interface Tx_PtExtlist_Domain_DataBackend_DataBackendInterface {

	/**
	 * Creates an instance of data source object to be used with current backend
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public static function createDataSource(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder);
	
	
	
	/**
	 * Returns mapped List structure
	 * 
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
    public function getListData();


	/**
	 * @abstract
	 * @return Tx_PtExtlist_Domain_Model_List_IterationListData
	 */
	public function getIterationListData();

    
    /**
	 * Returns the list header
	 * 
	 * @return Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 */
    public function getListHeader();
    
    
    
    /**
     * Returns th aggregate data
     * 
     * @return Tx_PtExtlist_Domain_Model_List_ListData
     */
    public function getAggregateListData();
    
    
    
    /**
     * Returns raw data for all filters excluding given filters. 
     * 
     * Result is given as associative array with fields given in query object.
     *
     * @param Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery Query that defines which group data to get
     * @param array $excludeFilters List of filters to be excluded from query (<filterboxIdentifier>.<filterIdentifier>)
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig
     * @return array Array of group data with given fields as array keys
     */
    public function getGroupData(Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery, $excludeFilters = array(),
								 Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig = NULL);
    
    
    
    /**
     * Returns field configuraiton collection
     *
     * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
     */
    public function getFieldConfigurationCollection(); 
    
    
    
    /**
     * Injector for field config collection
     *
     * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection $fieldConfigCollection
     */
    public function _injectFieldConfigurationCollection(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection  $fieldConfigCollection);
    
  
    
    /**
     * Injector for pager collection.
     *
     * @param Tx_PtExtlist_Domain_Model_Pager_PagerCollection $pagerCollection
     */
    public function _injectPagerCollection(Tx_PtExtlist_Domain_Model_Pager_PagerCollection $pagerCollection);
    
    
    
    /**
     * Returns the number of items for current settings without pager settings
     *
     * @return int Total number of items for current data set
     */
    public function getTotalItemsCount();
    
    
    
    /**
     * Return an aggregate for a field and with a method defined in the given config
     *  
     * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateDataConfig
     */
    public function getAggregatesByConfigCollection(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateDataConfigCollection);
    
    
    
    /**
     * Injector for data mapper
     *
     * @param Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $dataMapper
     */
    public function _injectDataMapper(Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $dataMapper);
    
    
    
    /**
     * Injector for data source
     *
     * @param mixed $dataSource
     */
    public function _injectDataSource($dataSource);
    
    
    
    /**
     * Injector for list header
     *
     * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader
     */
    #public function _injectListHeader(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader);



    /**
     * Injector for sorter
     * 
     * @param Tx_PtExtlist_Domain_Model_Sorting_Sorter $sorter
     * @return void
     */
    public function _injectSorter(Tx_PtExtlist_Domain_Model_Sorting_Sorter $sorter);

    
    
    /**
     * Injector for filterbox collection
     *
     * @param Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollection $filterboxCollection
     */
    public function _injectFilterboxCollection(Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection $filterboxCollection);
    
    
    
    /**
     * Injector for backend configuration
     *
     * @param Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration $backendConfiguration
     */
    public function _injectBackendConfiguration(Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration $backendConfiguration);
    
    
    
    /**
     * Injector for query interpreter
     *
     * @param mixed $queryInterpreter
     */
    public function _injectQueryInterpreter($queryInterpreter);
    
    
    
    /**
     * Injector for bookmark manager
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager $bookmarkManager
     */
    public function _injectBookmarkManager(Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager $bookmarkManager);
    
    
    
    /**
     * Returns associated filterbox collection
     * 
     * @return Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection Associated filterbox collection
     */
    public function getFilterboxCollection();

    
    
    /**
     * Returns associated pager collection
     * 
     * @return Tx_PtExtlist_Domain_Model_Pager_PagerCollection
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
     * @return Tx_PtExtlist_Domain_Model_Sorting_Sorter
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

}
?>