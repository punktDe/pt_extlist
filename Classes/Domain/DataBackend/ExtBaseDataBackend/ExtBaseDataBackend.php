<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * Backend for using pt_extlist with ExtBase Domain objects
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend implements Tx_PtExtlist_Domain_DataBackend_DataBackendInterface {
	
	/**
	 * Holds a repository for creating domain objects
	 *
	 * @var Tx_Extbase_Persistence_Repository
	 */
	protected $repository;
	
	
	
	/**
	 * Holds an instance of field config collection for field configuration
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	protected $fieldConfigCollection;
	
	
	
	/**
	 * Holds list header for this list
	 *
	 * @var Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 */
	protected $listHeader;
	
	
	
	/**
	 * Factory method for repository to be used with this data backend.
	 * 
	 * Although it's called data source, we create a extbase repository here which acts as a 
	 * datasource for this backend.
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public static function createDataSource(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$dataBackendSettings =  $configurationBuilder->getDatabackendSettings();
		tx_pttools_assert::isNotEmptyString($dataBackendSettings['repositoryClassName'], array('message' => 'No repository class name is given for extBase backend. 1281546327'));
		tx_pttools_assert::isTrue(class_exists($dataBackendSettings['repositoryClassName']), array('message' => 'Given class does not exist: ' . $dataBackendSettings['repositoryClassName'] . ' 1281546328'));
		$repository = t3lib_div::makeInstance($dataBackendSettings['repositoryClassName']);
		return $repository;
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::getFieldConfigurationCollection()
	 *
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	public function getFieldConfigurationCollection() {
		
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::getFilterboxCollection()
	 *
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection
	 */
	public function getFilterboxCollection() {
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::getGroupData()
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery
	 * @param array $excludeFilters
	 * @return array
	 */
	public function getGroupData(Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery, $excludeFilters=array()) {
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::getListData()
	 *
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getListData() {
		$data = $this->repository->findAll();
		#print_r($data);
		return $data;
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::getTotalItemsCount()
	 *
	 * @return int
	 */
	public function getTotalItemsCount() {
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::injectBackendConfiguration()
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration $backendConfiguration
	 */
	public function injectBackendConfiguration(Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration $backendConfiguration) {
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::injectDataMapper()
	 *
	 * @param Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $dataMapper
	 */
	public function injectDataMapper(Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $dataMapper) {
	}
	
	
	
	/**
	 * Injector for data source. Expects Tx_Extbase_Persistence_Repository to be given as datasource
	 *
	 * @param mixed $dataSource
	 */
	public function injectDataSource($dataSource) {
		tx_pttools_assert::isInstanceOf($dataSource, 'Tx_Extbase_Persistence_Repository', array('message' => 'Given data source must implement Tx_Extbase_Persistence_Repository but did not! 1281545172'));
		$this->repository = $dataSource;
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::injectFilterboxCollection()
	 *
	 * @param Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollection $filterboxCollection
	 */
	public function injectFilterboxCollection(Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection $filterboxCollection) {
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::injectPager()
	 *
	 * @param Tx_PtExtlist_Domain_Model_Pager_PagerInterface $pager
	 */
	public function injectPager(Tx_PtExtlist_Domain_Model_Pager_PagerInterface $pager) {
	}
	
	
	
	/**
	 * Injector for field config collection
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection $fieldConfigCollection
	 */
	public function injectFieldConfigurationCollection(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection  $fieldConfigCollection) {
		$this->fieldConfigCollection = $fieldConfigCollection;
	}
	
	
	
	/**
	 * Injector for list header
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader
	 */
	public function injectListHeader(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader) {
		$this->listHeader = $listHeader;
	}

}