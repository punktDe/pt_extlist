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
 * TODO at the moment 2 queries are send to the database: 1) gather data 2) count rows in data --> cache data and count rows there
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend extends Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend {
	
	/**
	 * Holds a repository for creating domain objects
	 *
	 * @var Tx_Extbase_Persistence_Repository
	 */
	protected $repository;
	
	
	
	/**
	 * Factory method for repository to be used with this data backend.
	 * 
	 * Although it's called data source, we create an extbase repository here which acts as a 
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
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::getGroupData()
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery
	 * @param array $excludeFilters
	 * @return array
	 */
	public function getGroupData(Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery, $excludeFilters=array()) {
		return array();
//		$query = $this->buildGenericQueryWithoutPager();
//		$query = $this->mergeGenericQueries($query, $groupDataQuery);
//		$extBaseQuery = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter::interpretQueryByRepository(
//		        $query, $this->repository);
//		return $extBaseQuery->execute();
	}
	
	
	
	/**
	 * Merges criterias for two given queries
	 * 
	 * TODO put this into query class!
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $resultQuery Query to be returned after merge
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $queryToBeMerged Query to be merged into other query
	 * @return Tx_PtExtlist_Domain_QueryObject_Query
	 */
	protected function mergeGenericQueries(Tx_PtExtlist_Domain_QueryObject_Query $resultQuery, Tx_PtExtlist_Domain_QueryObject_Query $queryToBeMerged) {
		// TODO merge other things, except criterias
		foreach($queryToBeMerged->getCriterias() as $criteria) {
			$resultQuery->addCriteria($criteria);
		}
		return $resultQuery;
	}
	
	
	
	/**
	 * Injects a query interpreter
	 * 
	 * This method is overwritten to make sure that correct type for interpreter is injected
	 *
	 * @param Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter $queryInterpreter
	 */
	public function injectQueryInterpreter(Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter $queryInterpreter) {
		tx_pttools_assert::isTrue(get_class($queryInterpreter) == 'Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter');
		parent::injectQueryInterpreter($queryInterpreter); 
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::getListData()
	 *
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getListData() {
		$extbaseQuery = $this->buildExtBaseQuery();
		$data = $extbaseQuery->execute();
		#print_r($data);
		$mappedListData = $this->dataMapper->getMappedListData($data);
		return $mappedListData;
	}
	
	
	
	/**
	 * Builds query for current pager, filter and sorting settings
	 *
	 * @return Tx_Extbase_Persistence_Query
	 */
	protected function buildExtBaseQuery() {
		$query = $this->buildGenericQueryWithoutPager();
        
        // Collect pager limit
        if ($this->pagerCollection->isEnabled()) {
            $pagerOffset = intval($this->pagerCollection->getCurrentPage() - 1) * intval($this->pagerCollection->getItemsPerPage());
            $pagerLimit = intval($this->pagerCollection->getItemsPerPage());
            $limitPart .= $pagerOffset > 0 ? $pagerOffset . ':' : '';
            $limitPart .= $pagerLimit > 0 ? $pagerLimit : '';
        }
        $query->setLimit($limitPart);
        
        $extbaseQuery = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter::interpretQueryByRepository($query, $this->repository); /* @var $extbaseQuery Tx_Extbase_Persistence_Query */
        return $extbaseQuery;
	}
	
	
	
	/**
	 * Builds extlist query object without regarding pager
	 *
	 * @return Tx_PtExtlist_Domain_QueryObject_Query
	 */
	protected function buildGenericQueryWithoutPager() {
        $query = $this->buildGenericQueryExcludingFilters();
        return $query;
	}
	
	
	
	/**
	 * Builds extlist query object excluding criterias from filters given by parameter
	 *
	 * @param array $excludeFilters Array of <filterbox>.<filter> identifiers to be excluded from query
	 */
	protected function buildGenericQueryExcludingFilters(array $excludeFilters = array()) {
	    $query = new Tx_PtExtlist_Domain_QueryObject_Query();
	    foreach($this->filterboxCollection as $filterbox) { /* @var $filterbox Tx_PtExtlist_Domain_Model_Filter_Filterbox */
            foreach($filterbox as $filter) { /* @var $filter Tx_PtExtlist_Domain_Model_Filter_FilterInterface */
		    	if (!in_array($filterbox->getfilterboxIdentifier() . '.' . $filter->getFilterIdentifier(), $excludeFilters)) {
                    $criterias = $filter->getFilterQuery()->getCriterias();
                    foreach($criterias as $criteria) {
                        $query->addCriteria($criteria);
                    }
                }
            }
        }
        return $query;
	}
	
	
	
	/**
	 * Builds ExtBase query object without regarding pager
	 *
	 * @return Tx_Extbase_Persistence_Query
	 */
	protected function buildExtBaseQueryWithoutPager() {
		$extbaseQuery = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter::interpretQueryByRepository(
		    $this->buildGenericQueryWithoutPager(), $this->repository); /* @var $extbaseQuery Tx_Extbase_Persistence_Query */
		return $extbaseQuery;
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::getTotalItemsCount()
	 *
	 * @return int
	 */
	public function getTotalItemsCount() {
		return $extbaseQuery = $this->buildExtBaseQueryWithoutPager()->count();
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

}