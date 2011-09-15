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
     * @return array Array of group data with given fields as array keys
     */
    public function getGroupData(Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery, $excludeFilters = array());
    
    
    
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
    public function injectFieldConfigurationCollection(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection  $fieldConfigCollection);
    
  
    
    /**
     * Injector for pager collection.
     *
     * @param Tx_PtExtlist_Domain_Model_Pager_PagerCollection $pagerCollection
     */
    public function injectPagerCollection(Tx_PtExtlist_Domain_Model_Pager_PagerCollection $pagerCollection);
    
    
    
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
    public function injectDataMapper(Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $dataMapper);
    
    
    
    /**
     * Injector for data source
     *
     * @param mixed $dataSource
     */
    public function injectDataSource($dataSource);
    
    
    
    /**
     * Injector for list header
     *
     * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader
     */
    #public function injectListHeader(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader);



    /**
     * Injector for sorter
     * 
     * @param Tx_PtExtlist_Domain_Model_Sorting_Sorter $sorter
     * @return void
     */
    public function injectSorter(Tx_PtExtlist_Domain_Model_Sorting_Sorter $sorter);

    
    
    /**
     * Injector for filterbox collection
     *
     * @param Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollection $filterboxCollection
     */
    public function injectFilterboxCollection(Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection $filterboxCollection);
    
    
    
    /**
     * Injector for backend configuration
     *
     * @param Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration $backendConfiguration
     */
    public function injectBackendConfiguration(Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration $backendConfiguration);
    
    
    
    /**
     * Injector for query interpreter
     *
     * @param Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter $queryInterpreter
     */
    public function injectQueryInterpreter(Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter $queryInterpreter);
    
    
    
    /**
     * Injector for bookmark manager
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager $bookmarkManager
     */
    public function injectBookmarkManager(Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager $bookmarkManager);
    
    
    
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
    
}
?>