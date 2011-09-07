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
 * Class implements data backend for generic mysql connections
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Domain
 * @subpackage DataBackend\MySqlDataBackend
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
     * Table definitions from TSConfig
     * @var string
     */
    protected $tables;



    /**
     * The baseWhereClause from TSConfig
     * @var string
     */
    protected $baseWhereClause;


    /**
     * The baseFromClause from TSConfig
     * @var string
     */
    protected $baseFromClause;



    /**
     * The baseGroupByClause from TSConfig
     *
     * @var string
     */
    protected $baseGroupByClause;



    /**
     * Array of complete query list query parts with MYSQL keywords
     *
     * @var array
     */
    protected $listQueryParts = NULL;



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
        $dataSourceConfiguration = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($configurationBuilder->buildDataBackendConfiguration()->getDataSourceSettings());
        $dataSource = Tx_PtExtlist_Domain_DataBackend_DataSource_MysqlDataSourceFactory::createInstance($dataSourceConfiguration);

        return $dataSource;
    }



    /**
     * (non-PHPdoc)
     * @see Classes/Domain/DataBackend/Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend::initBackendByTsConfig()
     */
    protected function initBackendByTsConfig() {
    	$this->tables = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($this->backendConfiguration->getDataBackendSettings('tables'));
    	$this->baseWhereClause = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($this->backendConfiguration->getDataBackendSettings('baseWhereClause'));
    	$this->baseFromClause = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($this->backendConfiguration->getDataBackendSettings('baseFromClause'));
    	$this->baseGroupByClause = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($this->backendConfiguration->getDataBackendSettings('baseGroupByClause'));
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
	 * Build the list data
	 * @return array Array of raw list data
	 */
	public function buildListData() {
		$sqlQuery = $this->buildQuery();
		$rawData = $this->dataSource->executeQuery($sqlQuery);
		return $this->dataMapper->getMappedListData($rawData);
	}



	/**
	 * Builder for SQL query. Gathers information from
	 * all parts of plugin (ts-config, pager, filters etc.)
	 * and generates SQL query out of this information
	 *
	 * @return string An SQL query
	 */
	public function buildQuery() {

		if(!is_array($this->listQueryParts)) {
			$selectPart  = $this->buildSelectPart();
			$fromPart    = $this->buildFromPart();
			$wherePart   = $this->buildWherePart();
			$orderByPart = $this->buildOrderByPart();
			$limitPart   = $this->buildLimitPart();
			$groupByPart = $this->buildGroupByPart();

			$this->listQueryParts['SELECT'] =	$selectPart != '' ? 'SELECT ' 	. $selectPart   . " \n" : '';
			$this->listQueryParts['FROM'] = 	$fromPart != '' ? 'FROM '   	. $fromPart 	. " \n" : '';
			$this->listQueryParts['WHERE'] = 	$wherePart != '' ? 'WHERE '  	. $wherePart 	. " \n" : '';
			$this->listQueryParts['GROUPBY'] =  $groupByPart!= '' ? 'GROUP BY ' . $groupByPart 	. " \n" : '';
			$this->listQueryParts['ORDERBY'] =  $orderByPart!= '' ? 'ORDER BY ' . $orderByPart 	. " \n" : '';
			$this->listQueryParts['LIMIT'] = 	$limitPart != '' ? 'LIMIT ' 	. $limitPart 	. " \n" : '';
		}

		$query = implode('', $this->listQueryParts);
		if (TYPO3_DLOG) t3lib_div::devLog('MYSQL QUERY : '.$this->listIdentifier.' -> listSelect', 'pt_extlist', 1, array('query' => $query));
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
			if($fieldConfiguration->getExpandGroupRows() && $this->baseGroupByClause) {
        		$selectParts[] = 'group_concat('.Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($fieldConfiguration).') AS ' . $fieldConfiguration->getIdentifier();
        	} else {
        		$selectParts[] = Tx_PtExtlist_Utility_DbUtils::getAliasedSelectPartByFieldConfig($fieldConfiguration);
        	}
        }

        return implode(', ', $selectParts);
	}



	/**
	 * Builds from part from all parts of plugin
	 *
	 * @return string FROM part of query without 'FROM'
	 */
	public function buildFromPart() {
		if($this->baseFromClause) {
			$fromPart = $this->baseFromClause;
		} else {
			$fromPart = $this->tables;
		}

		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($fromPart, array('message' => 'Backend must have a tables setting or a baseFromClause in TS! None of both is given! 1280234420'));

		return $fromPart;
	}



	/**
	 * Builds where part of query from all parts of plugin
	 *
	 * @return string WHERE part of query without 'WHERE'
	 * @param array $excludeFilters Define filters from which no where clause should be returned (array('filterboxIdentifier' => array('filterIdentifier')))
	 */
	public function buildWherePart($excludeFilters = array()) {
		$wherePart = '';
		$baseWhereClause = $this->getBaseWhereClause();
		$whereClauseFromFilterBoxes = $this->getWhereClauseFromFilterboxes($excludeFilters);

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
		return $this->baseWhereClause;
	}



	/**
	 * Returns base group by clause from TS config
	 * @return $string
	 */
	public function getBaseGroupByClause() {
		return $this->baseGroupByClause;
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
		$sortingsQuery = $this->sorter->getSortingStateCollection()->getSortingsQuery();
        $translatedOrderBy = $this->queryInterpreter->getSorting($sortingsQuery);
        return $translatedOrderBy;
	}
    


	/**
	 * Builds limit part of query from all parts of plugin
	 *
	 * @return string LIMIT part of query without 'LIMIT'
	 */
	public function buildLimitPart() {
		$limitPart = '';
		if ($this->pagerCollection->isEnabled()) {
			$pagerOffset = intval($this->pagerCollection->getCurrentPage() - 1) * intval($this->pagerCollection->getItemsPerPage());
			$pagerLimit = intval($this->pagerCollection->getItemsPerPage());
			$limitPart .= $pagerOffset > 0 ? $pagerOffset . ',' : '';
			$limitPart .= $pagerLimit > 0 ? $pagerLimit : '';
		}
		return $limitPart;
	}


	/**
	 * Get the query parts
	 * @return array query parts
	 */
	public function getQueryParts() {
		$this->buildQuery();
		return $this->listQueryParts;
	}


	/**
	 * Returns number of records for current settings without pager
	 *
	 * @return int Total number of records for current settings
	 */
	public function getTotalItemsCount() {

		$this->buildQuery();

		$query = '';
		$query .= 'SELECT COUNT(*) AS totalItemCount ';
		$query .= $this->listQueryParts['FROM'];
		$query .= $this->listQueryParts['WHERE'];
		$query .= $this->listQueryParts['GROUPBY'];

		$countResult = $this->dataSource->executeQuery($query);

		if($this->listQueryParts['GROUPBY']) {
			$count = count($countResult);
		} else {
			$count = intval($countResult[0]['totalItemCount']);
		}

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
        $this->buildQuery();

    	$selectPart  = 'SELECT ' . $this->queryInterpreter->getSelectPart($groupDataQuery);
    	$fromPart = $this->listQueryParts['FROM'];
    	$groupPart   = count($groupDataQuery->getGroupBy()) > 0  ? ' GROUP BY ' . $this->queryInterpreter->getGroupBy($groupDataQuery) : '';
    	$sortingPart = count($groupDataQuery->getSortings()) > 0 ? ' ORDER BY ' . $this->queryInterpreter->getSorting($groupDataQuery) : '';

    	if(count($groupDataQuery->getFrom()) > 0) {
    		// special from part from filter TODO think about this and implement it!
    		//$fromPart = ' FROM ' . $this->queryInterpreter->getFromPart($groupDataQuery);

    	} elseif($this->listQueryParts['GROUPBY']) {
    		$selectPart = $this->convertTableFieldToAlias($selectPart);
    		$groupPart = $this->convertTableFieldToAlias($groupPart);
    		$sortingPart = $this->convertTableFieldToAlias($sortingPart);

    		$filterWherePart = $this->buildWherePart($excludeFilters);
    		$filterWherePart = $filterWherePart ? ' WHERE ' . $filterWherePart . " \n" : '';

    		// if the list has a group by clause itself, we have to use the listquery as subquery
    		$fromPart = ' FROM (' . $this->listQueryParts['SELECT'] . $this->listQueryParts['FROM'] . $filterWherePart . $this->listQueryParts['GROUPBY'] . ') AS SUBQUERY ';

    		unset($filterWherePart);	// we confined the subquery, so we dont need this in the group query

    	} else {
    		$filterWherePart =  $this->buildWherePart($excludeFilters);
    		if($filterWherePart != '') $filterWherePart = ' WHERE ' . $filterWherePart . " \n";
    	}

    	$query =  implode(" \n", array($selectPart, $fromPart, $filterWherePart, $groupPart, $sortingPart));

    	if (TYPO3_DLOG) t3lib_div::devLog('MYSQL QUERY : '.$this->listIdentifier.' -> groupDataSelect', 'pt_extlist', 1, array('query' => $query));

        $groupDataArray = $this->dataSource->executeQuery($query);

        return $groupDataArray;
    }



    /**
     * Aggreagte the list by field and method or special sql
     *
     * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateConfig
     */
    public function getAggregatesByConfigCollection(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateConfigCollection) {
    	$aggregateSQLQuery = $this->buildAggregateSQLByConfigCollection($aggregateConfigCollection);

    	$aggregates = $this->dataSource->executeQuery($aggregateSQLQuery);

    	return $aggregates[0];
    }



    /**
     * Build the whole SQL Query for all aggregate fields
     *
     * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateConfigCollection
     */
    protected function buildAggregateSQLByConfigCollection(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateConfigCollection) {
    	$this->buildQuery();

    	$selectPart  = 'SELECT ' . $this->buildAggregateFieldsSQLByConfigCollection($aggregateConfigCollection);
    	$fromPart = ' FROM (' . $this->listQueryParts['SELECT'] . $this->listQueryParts['FROM'] . $this->listQueryParts['WHERE'] . $this->listQueryParts['GROUPBY'] . ')  AS AGGREGATEQUERY';

    	$query =  implode(" \n", array($selectPart, $fromPart));

    	return $query;
    }



    /**
     * Build the fields Sql for all fields
     *
     * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateConfigCollection
     */
    protected function buildAggregateFieldsSQLByConfigCollection(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateConfigCollection) {
    	$fieldsSQL = array();

    	foreach($aggregateConfigCollection as $aggregateConfig) {
    		$fieldsSQL[] = $this->buildAggregateFieldSQLByConfig($aggregateConfig);
    	}

    	return implode(', ', $fieldsSQL);
    }



    /**
     * Build the SQL Query for an aggregate
     *
     * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateConfig
     */
    protected function buildAggregateFieldSQLByConfig(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateConfig) {
    	$supportedMethods = array('sum', 'avg', 'min', 'max');

    	if($aggregateConfig->getSpecial()) {
    		$aggregateFieldSQL = $aggregateConfig->getSpecial();
    	} else {
    		Tx_PtExtbase_Assertions_Assert::isInArray($aggregateConfig->getMethod(), $supportedMethods, array('info' => 'The given aggregate method "'.$aggregateConfig->getMethod().'" is not supported by this DataBackend'));
    		$aggregateFieldSQL = strtoupper($aggregateConfig->getMethod()) . '('.$aggregateConfig->getFieldIdentifier().')';
    	}

    	$aggregateFieldSQL .= ' AS ' . $aggregateConfig->getIdentifier();

    	return $aggregateFieldSQL;
    }



    /**
     * Replaces all occurrences of "table.field AS fieldIdentifier" and "table.field"
     *
     * @param string $query
     */
    protected function convertTableFieldToAlias($query) {

    	$convertTableFieldAsAliasArray = array();
    	$convertTableFieldArray = array();

    	foreach($this->getFieldConfigurationCollection() as $fieldConfiguration) {

    		if($fieldConfiguration->getSpecial() == '') {
    			$convertTableFieldAsAliasArray[$fieldConfiguration->getIdentifier()] = $fieldConfiguration->getTable() . '.' . $fieldConfiguration->getField() . ' AS ' . $fieldConfiguration->getIdentifier();
	    		$convertTableFieldArray[$fieldConfiguration->getIdentifier()] =  $fieldConfiguration->getTable() . '.' . $fieldConfiguration->getField();
    		}
    	}

    	$query = str_replace(array_values($convertTableFieldAsAliasArray), array_keys($convertTableFieldAsAliasArray), $query);
    	$query = str_replace(array_values($convertTableFieldArray), array_keys($convertTableFieldArray), $query);

    	return $query;
    }
}

?>