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
 * Class implements data backend for generic mysql connections
 * 
 * @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend extends Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend {
	    
    /**
     * @var Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource
     */
    protected $dataSource;
    
    
    
    /**
     * Holds an instance of a query interpreter to be used for
     * query objects
     *
     * @var Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter
     */
    protected $queryInterpreter;
	
    
    
	/**
     * Factory method for data source
     * 
     * Only DataBackend knows, which data source to use and how to instantiate it.
     * So there cannot be a generic factory for data sources and data backend factory cannot instantiate it either!
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource Data source object for this data backend
     */
    public static function createDataSource(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
        $dataSourceConfiguration = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($configurationBuilder->buildDataBackendConfiguration());
        $dataSource = Tx_PtExtlist_Domain_DataBackend_DataSource_MysqlDataSourceFactory::createInstance($dataSourceConfiguration);
        
        return $dataSource;
    }
    
    
       
    /**
     * Injector for data source
     *
     * @param mixed $dataSource
     */
    public function injectDataSource($dataSource) {
        $this->dataSource = $dataSource;
    }
	
		
	
    /**
     * Returns raw list data
     *
     * @return array Array of raw list data
     */
	public function getListData() {
		$sqlQuery = $this->buildQuery();
		$rawData = $this->dataSource->executeQuery($sqlQuery);
		$mappedListData = $this->dataMapper->getMappedListData($rawData);
		return $mappedListData;
	}
	
	
	
	/**
	 * Builder for SQL query. Gathers information from
	 * all parts of plugin (ts-config, pager, filters etc.)
	 * and generates SQL query out of this information
	 *
	 * @return string An SQL query
	 */
	public function buildQuery() {
		$selectPart  = $this->buildSelectPart();
		$fromPart    = $this->buildFromPart();
		$wherePart   = $this->buildWherePart();
		$orderByPart = $this->buildOrderByPart();
		$limitPart   = $this->buildLimitPart();
		$groupByPart = $this->buildGroupByPart();
		
		$query = '';
		$query .= $selectPart != ''  ? 'SELECT ' 	. $selectPart   . " \n" : '';
		$query .= $fromPart != ''    ? 'FROM '   	. $fromPart 	. " \n" : '';
		$query .= $wherePart != ''   ? 'WHERE '  	. $wherePart 	. " \n" : '';
		$query .= $groupByPart != '' ? 'GROUP BY ' 	. $groupByPart 	. " \n" : '';
		$query .= $limitPart != ''   ? 'LIMIT ' 	. $limitPart 	. " \n" : '';
		
		return $query;
	}
	
	
	
	/**
	 * Builds select part from all parts of plugin
	 *
	 * @return string SELECT part of query without 'SELECT'
	 */
	public function buildSelectPart() {
		$selectParts = array();
        foreach($this->fieldConfigurationCollection as $fieldConfiguration) { /* @var $fieldConfiguration Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig */
        	$selectParts[] = Tx_PtExtlist_Utility_DbUtils::getAliasedSelectPartByFieldConfig($fieldConfiguration);
        }
		return implode(', ', $selectParts);
	}
	

	
	/**
	 * Builds from part from all parts of plugin
	 *
	 * @return string FROM part of query without 'FROM'
	 */
	public function buildFromPart() {
		if ($this->backendConfiguration->getDataBackendSettings('tables')) {
		    $fromPart = $this->backendConfiguration->getDataBackendSettings('tables');
		}
		
		if(trim($this->backendConfiguration->getDataBackendSettings('baseFromClause'))) {
			$fromPart = trim($this->backendConfiguration->getDataBackendSettings('baseFromClause'));
		}
		
		tx_pttools_assert::isNotEmptyString($fromPart, array('message' => 'Backend must have a tables setting or a baseFromClause in TS! None of both is given! 1280234420'));
		
		return $fromPart;
	}
	
	
	
	/**
	 * Builds where part of query from all parts of plugin
	 *
	 * @return string WHERE part of query without 'WHERE'
	 */
	public function buildWherePart() {
		$wherePart = '';
		$baseWhereClause = $this->getBaseWhereClause();
		$whereClauseFromFilterBoxes = $this->getWhereClauseFromFilterboxes();
		
		if($baseWhereClause && $whereClauseFromFilterBoxes) {
			$wherePart = '(' . $baseWhereClause . ') AND ' . $whereClauseFromFilterBoxes;
		} else {
			$wherePart = $baseWhereClause.$whereClauseFromFilterBoxes;			
		}
	 
		return $wherePart;
	}
	
	
	
	/**
	 * Builds groupBy part of query from all parts of plugin
	 * @return string groupByPart
	 */
	public function buildGroupByPart() {
		return $this->getBaseGroupByClause();
	}
	
	
	
	/**
	 * Returns base where clause from TS config
	 *
	 * @return string
	 */
	public function getBaseWhereClause() {
		return trim($this->backendConfiguration->getDataBackendSettings('baseWhereClause'));
	}
	
	
	
	/**
	 * Returns base group by clause from TS config
	 * @return $string
	 */
	public function getBaseGroupByClause() {
		return trim($this->backendConfiguration->getDataBackendSettings('baseGroupByClause'));
	}
	
	
	
	/**
	 * Returns where clauses from all filterboxes of a given collection of filterboxes except filters defined in exclude filters
	 *
	 * @param array $excludeFilters Define filters from which no where clause should be returned (array('filterboxIdentifier' => array('filterIdentifier')))
	 * @return string WHERE clause from filterboxcollection without 'WHERE'
	 */
	public function getWhereClauseFromFilterboxes($excludeFilters = array()) {
		$whereClauses = array();
		foreach ($this->filterboxCollection as $filterBox) { /* @var $filterBox Tx_PtExtlist_Domain_Model_Filter_Filterbox */
			$excludeFilterbox = array_key_exists($filterBox->getfilterboxIdentifier(), $excludeFilters) ? $excludeFilters[$filterBox->getfilterboxIdentifier()] : array();
			$whereClauseFromFilterbox = $this->getWhereClauseFromFilterbox($filterBox, $excludeFilterbox);
			if($whereClauseFromFilterbox) {
				$whereClauses[] = $whereClauseFromFilterbox;
			}
		}
		$whereClauseString = sizeof($whereClauses) > 0 ? '(' . implode(') AND (', $whereClauses) . ')' : '';
		return $whereClauseString;
	}
	
	
	
	/**
	 * Returns where clauses from all filters of a given filterbox
	 *
	 * @param Tx_PtExtlist_Domain_Model_Filter_Filterbox $filterbox
	 * @param array $excludeFilters Filters from which no where clause should be returned
	 * @return string WHERE clause from filterbox without 'WHERE'
	 */
	public function getWhereClauseFromFilterbox(Tx_PtExtlist_Domain_Model_Filter_Filterbox $filterbox, array $excludeFilters = array()) {
		$whereClausesFromFilterbox = array();
		foreach($filterbox as $filter) { /* @var $filter Tx_PtExtlist_Domain_Model_Filter_FilterInterface */
			if (!in_array($filter->getFilterIdentifier(), $excludeFilters)) {
				$whereClauseFromFilter = $this->getWhereClauseFromFilter($filter);
				if($whereClauseFromFilter) {
					$whereClausesFromFilterbox[] = $whereClauseFromFilter;	
				}
			}
		}
		$whereClauseString = sizeof($whereClausesFromFilterbox) > 0 ? '(' . implode(') AND (', $whereClausesFromFilterbox) . ')' : '';
		return $whereClauseString;
		
	}
	
	
	
	/**
	 * Returns WHERE clause for a given filter
	 *
	 * @param Tx_PtExtlist_Domain_Model_Filter_AbstractFilter $filter
	 * @return string WHERE clause for given filter without 'WHERE'
	 */
	public function getWhereClauseFromFilter(Tx_PtExtlist_Domain_Model_Filter_AbstractFilter $filter) {
		$whereClauseFromFilter = '';
		$whereClauseFromFilter = $this->queryInterpreter->getCriterias($filter->getFilterQuery());
		return $whereClauseFromFilter;
	}
	
	
	
	/**
	 * Builds order by part of query from all parts of plugin
	 *
	 * @return string ORDER BY part of query without 'ORDER BY'
	 */
	public function buildOrderByPart() {
		return $this->getOrderByFromListHeader($this->listHeader);
	}
	
	
	
	/**
	 * Build the order by string from list header
	 * 
	 * @param $listHeader Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 * @return string
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 02.08.2010
	 */
	public function getOrderByFromListHeader(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader) {
		$orderByArray = array();
		
		foreach($listHeader as $headerColumn) {
			$headerColumnSorting = $this->getOrderByFromHeaderColumn($headerColumn);
			if ($headerColumnSorting != '' ) {
			   $orderByArray[] = $headerColumnSorting;
			}
		}
		
		return count($orderByArray) > 0 ? implode(', ', $orderByArray) : '';
	}
	
	
	
	/**
	 * Return the interpreted order by string from a single header column
	 * 
	 * @param $headerColumn Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn
	 * @return string
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 02.08.2010
	 */
	public function getOrderByFromHeaderColumn(Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $headerColumn) {
		return $this->queryInterpreter->getSorting($headerColumn->getSortingQuery());	
	}
	
	
	
	/**
	 * Builds limit part of query from all parts of plugin
	 *
	 * @return string LIMIT part of query without 'LIMIT'
	 */
	public function buildLimitPart() {
		$limitPart = '';
		if ($this->pager->isEnabled()) {
			$pagerOffset = intval($this->pager->getCurrentPage() - 1) * intval($this->pager->getItemsPerPage());
			$pagerLimit = intval($this->pager->getItemsPerPage());
			$limitPart .= $pagerOffset > 0 ? $pagerOffset . ',' : '';
			$limitPart .= $pagerLimit > 0 ? $pagerLimit : '';
		}
		return $limitPart;
	}
	
	
	
	/**
	 * Returns number of records for current settings without pager
	 * 
	 * @return int Total number of records for current settings
	 */
	public function getTotalItemsCount() {
		$query = '';
		$query .= 'SELECT COUNT(*) AS totalItemCount FROM ';
		$query .= $this->buildFromPart();
		$query .= $this->buildWherePart() != '' ? ' WHERE ' . $this->buildWherePart() : '';
		
		$countResult = $this->dataSource->executeQuery($query);
		$count = intval($countResult[0]['totalItemCount']);
		
		return $count;
	}
	
	
	
    /**
     * Returns raw data for all filters excluding given filters. 
     * 
     * Result is given as associative array with fields given in query object.
     *
     * @param Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery Query that defines which group data to get
     * @param array $excludeFilters List of filters to be excluded from query (<filterboxIdentifier>.<filterIdentifier>)
     * @return array Array of group data with given fields as array keys
     */
    public function getGroupData(Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery, $excludeFilters = array()) {
    	$filterWherePart = $this->getWhereClauseFromFilterboxes($excludeFilters);
    	
    	$sqlQueryString = 'SELECT ' . $this->queryInterpreter->getSelectPart($groupDataQuery);
    	$sqlQueryString .= ' FROM ' . $this->buildFromPart();
    	$sqlQueryString .= count($groupDataQuery->getFrom()) > 0 ? ', ' . $this->queryInterpreter->getFromPart($groupDataQuery) : '';
    	$sqlQueryString .= $filterWherePart != '' ? ' WHERE ' . $filterWherePart : '';  
    	$sqlQueryString .= count($groupDataQuery->getGroupBy()) > 0  ? ' GROUP BY ' . $this->queryInterpreter->getGroupBy($groupDataQuery) : '';
    	$sqlQueryString .= count($groupDataQuery->getSortings()) > 0 ? ' ORDER BY ' . $this->queryInterpreter->getSorting($groupDataQuery) : '';

        $groupDataArray = $this->dataSource->executeQuery($sqlQueryString);
        return $groupDataArray;
    }
	
}

?>