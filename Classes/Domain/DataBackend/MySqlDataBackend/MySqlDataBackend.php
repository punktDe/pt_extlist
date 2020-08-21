<?php
namespace PunktDe\PtExtlist\Domain\DataBackend\MySqlDataBackend;

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

use Doctrine\DBAL\DBALException;
use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtbase\Exception\Assertion;
use PunktDe\PtExtbase\Exception\InternalException;
use PunktDe\PtExtbase\Logger\Logger;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfig;
use PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\Configuration\DataBackend\DataSource\DatabaseDataSourceConfiguration;
use PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig;
use PunktDe\PtExtlist\Domain\DataBackend\AbstractDataBackend;
use PunktDe\PtExtlist\Domain\DataBackend\DataSource\MySqlDataSource;
use PunktDe\PtExtlist\Domain\DataBackend\DataSource\MysqlDataSourceFactory;
use PunktDe\PtExtlist\Domain\DataBackend\MySqlDataBackend\MySqlInterpreter\MySqlInterpreter;
use PunktDe\PtExtlist\Domain\Model\Filter\AbstractFilter;
use PunktDe\PtExtlist\Domain\Model\Filter\Filterbox;
use PunktDe\PtExtlist\Domain\Model\Filter\FilterInterface;
use PunktDe\PtExtlist\Domain\Model\Lists\IterationListData;
use PunktDe\PtExtlist\Domain\Model\Lists\IterationListDataInterface;
use PunktDe\PtExtlist\Domain\QueryObject\Query;
use PunktDe\PtExtlist\Domain\Renderer\RendererChainFactory;
use PunktDe\PtExtlist\Utility\DbUtils;
use PunktDe\PtExtlist\Utility\RenderValue;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class implements data backend for generic mysql connections
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Domain
 * @subpackage DataBackend\MySqlDataBackend
 */
class MySqlDataBackend extends AbstractDataBackend
{
    /**
     * @var Connection
     */
    protected $connection;


    /**
     * Holds an instance of a query interpreter to be used for
     * query objects
     *
     * @var MySqlInterpreter
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
     * The baseFromClause from TSConfig
     * @var array
     */
    protected $baseJoinClause;

    /**
     * The baseGroupByClause from TSConfig
     *
     * @var string
     */
    protected $baseGroupByClause;

    /**
     * Indicates if prepare Statements are already executed
     *
     * @var boolean
     */
    protected $prepareStatementsExecuted = false;

    /**
     * Array of complete query list query parts with MYSQL keywords
     *
     * @var array
     */
    protected $listQueryParts = null;


    /**
     * Holds total item count (cached)
     *
     * @var integer
     */
    protected $totalItemsCount = null;


    /**
     * Holds an instance of the renderer chain factory
     *
     * @var RendererChainFactory
     */
    protected $rendererChainFactory;

    /**
     * @var Logger
     */
    protected $logger;



    /**
     * @param RendererChainFactory $rendererChainFactory
     */
    public function injectRendererChainFactory(RendererChainFactory $rendererChainFactory)
    {
        $this->rendererChainFactory = $rendererChainFactory;
    }

    /**
     * @param Logger $logger
     */
    public function injectLogger(Logger $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Factory method for data source
     *
     * Only DataBackend knows, which data source to use and how to instantiate it.
     * So there cannot be a generic factory for data sources and data backend factory cannot instantiate it either!
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @return MySqlDataSource Data source object for this data backend
     */
    public static function createDataSource(ConfigurationBuilder $configurationBuilder)
    {
        $dataSourceConfiguration = new DatabaseDataSourceConfiguration($configurationBuilder->buildDataBackendConfiguration()->getDataSourceSettings());
        $dataSource = MysqlDataSourceFactory::createInstance($configurationBuilder->buildDataBackendConfiguration()->getDataSourceClass(), $dataSourceConfiguration);

        return $dataSource;
    }


    /**
     * (non-PHPdoc)
     * @see Classes/Domain/DataBackend/AbstractDataBackend::initBackendByTsConfig()
     */
    protected function initBackendByTsConfig()
    {
        $this->tables = RenderValue::stdWrapIfPlainArray($this->backendConfiguration->getDataBackendSettings('tables'));
        $this->baseWhereClause = RenderValue::stdWrapIfPlainArray($this->backendConfiguration->getDataBackendSettings('baseWhereClause'));
        $this->baseFromClause = RenderValue::stdWrapIfPlainArray($this->backendConfiguration->getDataBackendSettings('baseFromClause'));
        $this->baseGroupByClause = RenderValue::stdWrapIfPlainArray($this->backendConfiguration->getDataBackendSettings('baseGroupByClause'));
        $this->baseJoinClause = $this->backendConfiguration->getDataBackendSettings('baseJoinClause');
    }


    /**
     * We implement template method for initializing backend
     */
    protected function initBackend()
    {
        parent::initBackend();
        // As pager->getCurrentPage is requested during $this->getTotalItemsCount(),
        // we have to set it to infty first and later set correct item count!
        $this->pagerCollection->setItemCount(PHP_INT_MAX);
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
     * Build the list data
     * @return array Array of raw list data
     */
    public function buildListData()
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->buildQuery(true);

        $rawData = $this->dataSource->executeQuery($queryBuilder)->fetchAll();

        $this->logger->debug($this->listIdentifier . '->listDataSelect / FetchAll', __CLASS__, ['executionTime' => $this->dataSource->getLastQueryExecutionTime(), 'query' => $queryBuilder->getSQL()]);

        $mappedListData = $this->dataMapper->getMappedListData($rawData);
        unset($rawData);

        return $mappedListData;
    }


    /**
     * @return IterationListDataInterface
     * @throws \Exception
     */
    public function getIterationListData()
    {
        $rendererChainConfiguration = $this->configurationBuilder->buildRendererChainConfiguration();

        $rendererChain = $this->rendererChainFactory->getRendererChain($rendererChainConfiguration);

        $queryBuilder = $this->buildQuery(true);

        $dataSource = clone $this->dataSource;
        $dataSource->executeQuery($queryBuilder);

        $this->logger->debug($this->listIdentifier . '->listDataSelect / IterationListData', __CLASS__, ['executionTime' => $dataSource->getLastQueryExecutionTime(), 'query' => $queryBuilder->getSQL()]);

        $iterationListData = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class)->get(IterationListData::class);
        /** @var $iterationListData IterationListData */
        $iterationListData->_injectDataSource($dataSource);
        $iterationListData->_injectDataMapper($this->dataMapper);
        $iterationListData->_injectRenderChain($rendererChain);

        return $iterationListData;
    }


    /**
     * Builder for SQL query. Gathers information from
     * all parts of plugin (ts-config, pager, filters etc.)
     * and generates SQL query out of this information
     *
     * @return QueryBuilder
     * @throws Assertion
     */
    public function buildQuery($execute= false): QueryBuilder
    {

        $this->executePrepareStatements();

        if (!is_array($this->listQueryParts)) {
            $this->buildSelectPart();
            $this->buildFromPart();
            $this->buildJoinPart();
            $this->buildWherePartForListData();
            $this->buildOrderByPart();
            $this->buildGroupByPart();
            $this->buildLimitPart();
        }

        /** @var Connection $connection */
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->listQueryParts['FROMTABLE']);
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $connection->createQueryBuilder();

        if ($execute) {
            $queryBuilder->selectLiteral($this->listQueryParts['SELECT'])
                ->from($this->listQueryParts['FROMTABLE'], $this->listQueryParts['FROMALIAS']);

            if (!empty($this->listQueryParts['WHERE'])) {
                $queryBuilder->where($this->listQueryParts['WHERE']);
            }

            if (!empty($this->listQueryParts['JOIN'])) {
                foreach ($this->listQueryParts['JOIN'] as $join) {
                    $method = $join['TYPE'] . 'Join';
                    $queryBuilder->$method($join['FROMALIAS'], $join['TABLE'], $join['ALIAS'],$join['ON']);
                }
            }

            if (!empty($this->listQueryParts['ORDERBY'])) {
                $firstItem = true;
                foreach($this->listQueryParts['ORDERBY'] as $field => $direction) {
                    if ($firstItem) {
                        $queryBuilder->orderBy($field, $direction);
                        $firstItem = false;
                    } else {
                        $queryBuilder->addOrderBy($field, $direction);
                    }
                }
            }

            if (!empty($this->listQueryParts['GROUPBY'])) {
                // @TODO: possibly refactor this. Previous code below
                // $queryBuilder->groupBy($this->listQueryParts['GROUPBY']);
                $queryBuilder->add('groupBy', $this->listQueryParts['GROUPBY'], true);
            }
            if (!empty($this->listQueryParts['FIRSTRESULT'])) {
                $queryBuilder->setFirstResult($this->listQueryParts['FIRSTRESULT']);
            }
            if (!empty($this->listQueryParts['MAXRESULT'])) {
                $queryBuilder->setMaxResults($this->listQueryParts['MAXRESULT']);
            }
        }
        return $queryBuilder;
    }


    /**
     * @param $query
     * @return string
     */
    public function processQueryWithFluid($query)
    {
        if (preg_match('/{.*}/', $query) !== 1) {
            return $query;
        }

        $query = '{namespace extlist=PunktDe\PtExtlist\ViewHelpers}{namespace ptx=PunktDe\PtExtbase\ViewHelpers}' . $query;

        $fluidView = $this->objectManager->get(StandaloneView::class); /** @var StandaloneView $fluidView */
        $fluidView->setTemplateSource($query);
        $fluidView->assignMultiple([
            'filters' => $this->filterboxCollection,
            'pager' => $this->pagerCollection,
            'sorter' => $this->sorter
        ]);

        return $fluidView->render();
    }


    /**
     *
     * Executes a list of prepare statements on database connect
     */
    protected function executePrepareStatements()
    {
        if ($this->prepareStatementsExecuted === true) {
            return;
        }

        $prepareStatements = $this->backendConfiguration->getSettings('prepareStatements');

        ###TODO
        if (is_array($prepareStatements)) {

            foreach ($prepareStatements as $prepareStatement) {
                if (trim($prepareStatement)) {
                    throw new \Exception('deactivated function executePrepareStatements', 1589466778);
                    $this->dataSource->executeQuery($this->processQueryWithFluid($prepareStatement));
                }
            }
        } else {
            if (trim($prepareStatements)) {
                throw new \Exception('deactivated function executePrepareStatements', 1589466878);
                $this->dataSource->executeQuery($this->processQueryWithFluid($prepareStatements));
            }
        }

        $this->prepareStatementsExecuted = true;
    }



    /**
     * Helper method that builds where part for list data
     *
     * Method respects exclude filters of submitted (active) filterbox
     * TODO test me!
     *
     * @return string
     */
    protected function buildWherePartForListData()
    {
        $this->listQueryParts['WHERE'] = $this->processQueryWithFluid($this->buildWherePart($this->filterboxCollection->getExcludeFilters()));
    }


    /**
     * Builds select part from all parts of plugin
     *
     * @return string SELECT part of query without 'SELECT'
     */
    public function buildSelectPart()
    {
        $selectParts = [];

        foreach ($this->fieldConfigurationCollection as $fieldConfiguration) {
            /* @var $fieldConfiguration FieldConfig */
            if ($fieldConfiguration->getExpandGroupRows() && $this->baseGroupByClause) {
                $selectParts[] = 'group_concat(' . DbUtils::getSelectPartByFieldConfig($fieldConfiguration) . ' SEPARATOR "' . $fieldConfiguration->getExpandGroupRowsSeparator() . '") AS ' . $fieldConfiguration->getIdentifier();
            } else {
                $selectParts[] = DbUtils::getAliasedSelectPartByFieldConfig($fieldConfiguration);
            }
        }
        $this->listQueryParts['SELECT'] = $this->processQueryWithFluid(implode(', ', $selectParts));
    }


    /**
     * Builds from part from all parts of plugin
     *
     * @return string FROM part of query without 'FROM'
     * @throws Assertion
     */
    public function buildFromPart()
    {
        if ($this->baseFromClause) {
            $fromPart = trim($this->baseFromClause);
        }

        Assert::isNotEmptyString($fromPart, ['message' => 'Backend must have a baseFromClause in TS! This is not given! 1280234420']);

        $fromPart = trim($this->processQueryWithFluid($fromPart));
        $items = GeneralUtility::trimExplode(' ', $fromPart);

        Assert::isInRange(count($items), 1 ,2 , ['message' => 'baseFromClause of Backend in TS has not the correct values! This should table name and optional added bey space alias! 1280234420']);

        $this->listQueryParts['FROMTABLE'] = trim($items[0]);
        $this->listQueryParts['FROMALIAS'] = trim($items[1]) ?: null;
    }

    /**
     * Builds from part from all parts of plugin
     *
     * @throws Assertion
     */
    public function buildJoinPart()
    {
        if (empty($this->baseJoinClause)) {
            return;
        }

        Assert::isArray($this->baseJoinClause, ['message' => 'BaseJoinClause has to be an array in TS! This is not given! 1594734761']);

        $this->listQueryParts['JOIN'] = [];
        foreach ($this->baseJoinClause as $joinPart) {
            $join = [];
            $join['FROMALIAS'] = trim($joinPart['fromAlias']);
            $join['TABLE'] = trim($joinPart['table']);
            $join['ALIAS'] = trim($joinPart['alias']) ?: trim($joinPart['table']);
            $join['ON'] = trim($joinPart['on']);
            $join['TYPE'] = strtolower(trim($joinPart['type'])) ?: 'inner';

            $this->listQueryParts['JOIN'][] = $join;
        }
    }

    /**
     * Builds where part of query from all parts of plugin
     *
     * @return string WHERE part of query without 'WHERE'
     * @param array $excludeFilters Define filters from which no where clause should be returned (array('filterboxIdentifier' => array('filterIdentifier')))
     */
    public function buildWherePart($excludeFilters = [])
    {
        $baseWhereClause = $this->getBaseWhereClause();
        $whereClauseFromFilterBoxes = $this->getWhereClauseFromFilterboxes($excludeFilters);

        if ($baseWhereClause && $whereClauseFromFilterBoxes) {
            $wherePart = '(' . $baseWhereClause . ') AND ' . $whereClauseFromFilterBoxes;
        } else {
            $wherePart = $baseWhereClause . $whereClauseFromFilterBoxes;
        }
        $this->listQueryParts['WHERE'] = $wherePart;
    }


    /**
     * Builds groupBy part of query from all parts of plugin
     * @return string groupByPart
     */
    public function buildGroupByPart()
    {
        $this->listQueryParts['GROUPBY'] = $this->getBaseGroupByClause();
    }


    /**
     * Returns base where clause from TS config
     *
     * @return string
     */
    public function getBaseWhereClause()
    {
        return $this->baseWhereClause;
    }


    /**
     * Returns base group by clause from TS config
     * @return string $string
     */
    public function getBaseGroupByClause()
    {
        return $this->baseGroupByClause;
    }


    /**
     * Returns where clauses from all filterboxes of a given collection of filterboxes except filters defined in exclude filters
     *
     * @param array $excludeFilters Define filters from which no where clause should be returned (array('filterboxIdentifier' => array('filterIdentifier')))
     * @return string WHERE clause from filterboxcollection without 'WHERE'
     * @throws \Exception
     */
    public function getWhereClauseFromFilterboxes($excludeFilters = [])
    {
        $whereClauses = [];
        foreach ($this->filterboxCollection as $filterBox) { /* @var $filterBox Filterbox */

            $excludeFilterbox = array_key_exists($filterBox->getfilterboxIdentifier(), $excludeFilters) ? $excludeFilters[$filterBox->getfilterboxIdentifier()] : [];
            $whereClauseFromFilterbox = $this->getWhereClauseFromFilterbox($filterBox, $excludeFilterbox);
            if ($whereClauseFromFilterbox) {
                $whereClauses[] = $whereClauseFromFilterbox;
            }
        }
        $whereClauseString = sizeof($whereClauses) > 0 ? '(' . implode(') AND (', $whereClauses) . ')' : '';
        return $whereClauseString;
    }


    /**
     * Returns where clauses from all filters of a given filterbox
     *
     * @param Filterbox $filterbox
     * @param array $excludeFilters Filters from which no where clause should be returned
     * @return string WHERE clause from filterbox without 'WHERE'
     * @throws \Exception
     */
    public function getWhereClauseFromFilterbox(Filterbox $filterbox, array $excludeFilters = [])
    {
        $whereClausesFromFilterbox = [];
        foreach ($filterbox as $filter) { /* @var $filter FilterInterface */

            if (!in_array($filter->getFilterIdentifier(), $excludeFilters)) {
                $whereClauseFromFilter = $this->getWhereClauseFromFilter($filter);
                if ($whereClauseFromFilter) {
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
     * @param AbstractFilter $filter
     * @return string WHERE clause for given filter without 'WHERE'
     * @throws \Exception
     */
    public function getWhereClauseFromFilter(AbstractFilter $filter)
    {
        $whereClauseFromFilter = $this->queryInterpreter->getCriterias($filter->getFilterQuery());
        return $whereClauseFromFilter;
    }


    /**
     * Builds order by part of query from all parts of plugin
     *
     * @return string ORDER BY part of query without 'ORDER BY'
     * @throws InternalException
     */
    public function buildOrderByPart()
    {
        $sortingsQuery = $this->sorter->getSortingStateCollection()->getSortingsQuery();
        $this->listQueryParts['ORDERBY'] = $this->queryInterpreter->getSorting($sortingsQuery);
    }


    /**
     * Builds limit part of query from all parts of plugin
     *
     * @return string LIMIT part of query without 'LIMIT'
     * @throws InternalException
     */
    public function buildLimitPart()
    {
        if ($this->pagerCollection->isEnabled()) {
            $pagerOffset = $this->pagerCollection->getItemOffset();
            $pagerLimit = (int)$this->pagerCollection->getItemsPerPage();
            $this->listQueryParts['FIRSTRESULT'] .= $pagerOffset > 0 ? $pagerOffset . ',' : '';
            $this->listQueryParts['MAXRESULT'] .= $pagerLimit > 0 ? $pagerLimit : '';
        }
    }

    /**
     * Get the query parts
     * @return array query parts
     */
    public function getQueryParts()
    {
        $this->buildQuery();
        return $this->listQueryParts;
    }


    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->buildQuery();
    }


    /**
     * Returns number of records for current settings without pager
     *
     * @return integer Total number of records for current settings
     * @throws Assertion
     */
    public function getTotalItemsCount()
    {
        if ($this->totalItemsCount == null) {
            $queryBuilder = $this->buildQuery();

            $queryBuilder->selectLiteral('COUNT(*) AS totalItemCount')
                ->from($this->listQueryParts['FROMTABLE'], $this->listQueryParts['FROMALIAS']);

            if (!empty($this->listQueryParts['WHERE'])) {
                $queryBuilder->where($this->listQueryParts['WHERE']);
            }

            if (!empty($this->listQueryParts['JOIN'])) {
                foreach ($this->listQueryParts['JOIN'] as $join) {
                    $method = $join['TYPE'] . 'Join';
                    $queryBuilder->$method($join['FROMALIAS'], $join['TABLE'], $join['ALIAS'],$join['ON']);
                }
            }

            if (!empty($this->listQueryParts['GROUPBY'])) {
                // @TODO: possibly refactor this. Previous code below
                // $queryBuilder->groupBy($this->listQueryParts['GROUPBY']);
                $queryBuilder->add('groupBy', $this->listQueryParts['GROUPBY'], true);
            }

            $countResult = $this->dataSource->executeQuery($queryBuilder)->fetchAll();

            $this->logger->debug($this->listIdentifier . '->getTotalItemsCount', __CLASS__,  ['executionTime' => $this->dataSource->getLastQueryExecutionTime(), 'query' => $queryBuilder->getSQL()]);

            if ($this->listQueryParts['GROUPBY']) {
                $this->totalItemsCount = count($countResult);
            } else {
                $this->totalItemsCount = (int)$countResult[0]['totalItemCount'];
            }

            $this->pagerCollection->setItemCount($this->totalItemsCount);

            // We have to build query again, as LIMIT is not set correctly above!
            // TODO fix this! Split build query in buildQueryWithoutPager() and buildQueryPartsFromPager()
            $this->listQueryParts = null;
            $this->buildQuery(false);
        }

        return $this->totalItemsCount;
    }


    /**
     * Returns raw data for all filters excluding given filters.
     *
     * Result is given as associative array with fields given in query object.
     *
     * @param Query $groupDataQuery Query that defines which group data to get
     * @param array $excludeFilters List of filters to be excluded from query (<filterboxIdentifier>.<filterIdentifier>)
     * @param FilterConfig $filterConfig
     * @return array Array of group data with given fields as array keys
     * @throws Assertion
     */
    public function getGroupData(Query $groupDataQuery, $excludeFilters = [],
                                 FilterConfig $filterConfig = null)
    {
        $queryBuilder = $this->buildGroupDataQuery($groupDataQuery, $excludeFilters, $filterConfig);

        $groupDataArray = $this->dataSource->executeQuery($queryBuilder)->fetchAll();

        $this->logger->debug($this->listIdentifier . '->groupDataSelect', __CLASS__, ['executionTime' => $this->dataSource->getLastQueryExecutionTime(), 'query' => $queryBuilder->getSQL()]);

        return $groupDataArray;
    }


    /**
     * @param Query $groupDataQuery
     * @param array $excludeFilters
     * @param FilterConfig $filterConfig
     * @return QueryBuilder
     * @throws Assertion
     */
    protected function buildGroupDataQuery(Query $groupDataQuery, $excludeFilters = [],
                                            FilterConfig $filterConfig = null): QueryBuilder
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->buildQuery();
        $selectPart = $this->queryInterpreter->getSelectPart($groupDataQuery);
        $groupPart = count($groupDataQuery->getGroupBy()) > 0 ?  $this->queryInterpreter->getGroupBy($groupDataQuery) : '';
        $sortingPart = count($groupDataQuery->getSortings()) > 0 ? $this->queryInterpreter->getSorting($groupDataQuery) : '';


        /**
         * If this list is grouped. There are two cases
         * 1. We want to show the rowCount. In this case we have to build the list first and then use this list as
         *        source to calculate the selectable rows and the count. This has the drawback, that options are not displayed, if grouped within the list.
         * 2. We do not need the rowCount. In this case, we exchange the original grouping fields with the fields needed by the filter.
         */

        if ($this->listQueryParts['GROUPBY'] && $filterConfig->getShowRowCount()) {

            $selectPart = $this->convertTableFieldToAlias($selectPart);
            $groupPart = $this->convertTableFieldToAlias($groupPart);
            $sortingPart = $this->convertTableFieldToAlias($sortingPart);

            $filterWherePart = $this->buildWherePart($excludeFilters);

            $queryBuilder->selectLiteral($this->listQueryParts['SELECT'])
                ->from($this->listQueryParts['FROMTABLE'], $this->listQueryParts['FROMALIAS'] ?? null);

            if (!empty($this->listQueryParts['JOIN'])) {
                foreach ($this->listQueryParts['JOIN'] as $join) {
                    $method = $join['TYPE'] . 'Join';
                    $queryBuilder->$method($join['FROMALIAS'], $join['TABLE'], $join['ALIAS'],$join['ON']);
                }
            }
            $queryBuilder->where($filterWherePart);

            $queryBuilder->add('groupBy', $this->listQueryParts['GROUPBY'], true);


            $sql = $queryBuilder->getSQL();

            // if the list has a group by clause itself, we have to use the listQuery as subQuery
            $fromPart = '(' . $sql  . ') AS SUBQUERY ';

            /**  we need a new query builder without restriction see SUBQUERY */
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->listQueryParts['FROMTABLE']); /** @var  Connection $connection */
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->selectLiteral($selectPart . ' FROM ' .  $fromPart);

        } else {
            $filterWherePart = $this->buildWherePart($excludeFilters);
            if ($filterWherePart != '') {
                $queryBuilder->where($filterWherePart);
            }

            $queryBuilder->selectLiteral($selectPart)
                ->from($this->listQueryParts['FROMTABLE'], $this->listQueryParts['FROMALIAS'] ?? null);

            if (!empty($this->listQueryParts['JOIN'])) {
                foreach ($this->listQueryParts['JOIN'] as $join) {
                    $method = $join['TYPE'] . 'Join';
                    $queryBuilder->$method($join['FROMALIAS'], $join['TABLE'], $join['ALIAS'],$join['ON']);
                }
            }
        }

        if (!empty($sortingPart)) {
            $firstItem = true;
            foreach($sortingPart as $field => $direction) {
                if ($firstItem) {
                    $queryBuilder->orderBy($field, $direction);
                    $firstItem = false;
                } else {
                    $queryBuilder->addOrderBy($field, $direction);
                }
            }
        }

        if (!empty($groupPart)) {
            $queryBuilder->add('groupBy', $groupPart, true);
        }

        ###TODO
        //$query = $this->processQueryWithFluid($query);


        return $queryBuilder;
    }


    /**
     * Aggreagte the list by field and method or special sql
     *
     * @param AggregateConfigCollection $aggregateConfigCollection
     */
    public function getAggregatesByConfigCollection(AggregateConfigCollection $aggregateConfigCollection)
    {
        $aggregateSQLQuery = $this->buildAggregateSQLByConfigCollection($aggregateConfigCollection);

        $aggregates = $this->dataSource->executeQuery($aggregateSQLQuery)->fetchAll();

        $this->logger->debug($this->listIdentifier . '->aggregateQuery', 'pt_extlist', ['executionTime' => $this->dataSource->getLastQueryExecutionTime(), 'query' => $aggregateSQLQuery->getSQL()]);

        return $aggregates[0];
    }


    /**
     * Build the whole SQL Query for all aggregate fields
     *
     * @param AggregateConfigCollection $aggregateConfigCollection
     * @return QueryBuilder
     * @throws DBALException
     * @throws Assertion
     */
    protected function buildAggregateSQLByConfigCollection(AggregateConfigCollection $aggregateConfigCollection): QueryBuilder
    {
        $this->buildQuery();

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionByName('Shared');

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->selectLiteral(
            $this->buildAggregateFieldsSQLByConfigCollection($aggregateConfigCollection) .
            ' FROM (SELECT ' . $this->listQueryParts['SELECT'] . ' FROM ' . $this->listQueryParts['FROMTABLE'] . ' ' . $this->listQueryParts['FROMALIAS'] . ' WHERE ' . $this->listQueryParts['WHERE'] . ' GROUP BY ' . $this->listQueryParts['GROUPBY'] . ')  AS AGGREGATEQUERY'
        );

        return $queryBuilder;
    }


    /**
     * Build the fields Sql for all fields
     *
     * @param AggregateConfigCollection $aggregateConfigCollection
     * @return string
     * @throws Assertion
     */
    protected function buildAggregateFieldsSQLByConfigCollection(AggregateConfigCollection $aggregateConfigCollection)
    {
        $fieldsSQL = [];

        foreach ($aggregateConfigCollection as $aggregateConfig) {
            $fieldsSQL[] = $this->buildAggregateFieldSQLByConfig($aggregateConfig);
        }

        return implode(', ', $fieldsSQL);
    }


    /**
     * Build the SQL Query for an aggregate
     *
     * @param AggregateConfig $aggregateConfig
     * @return string
     * @throws Assertion
     */
    protected function buildAggregateFieldSQLByConfig(AggregateConfig $aggregateConfig)
    {
        $supportedMethods = ['sum', 'avg', 'min', 'max', 'count'];

        if ($aggregateConfig->getSpecial()) {
            $aggregateFieldSQL = $aggregateConfig->getSpecial();
        } else {
            Assert::isInArray($aggregateConfig->getMethod(), $supportedMethods, ['info' => 'The given aggregate method "' . $aggregateConfig->getMethod() . '" is not supported by this DataBackend']);
            $aggregateFieldSQL = strtoupper($aggregateConfig->getMethod()) . '(' . $aggregateConfig->getFieldIdentifier() . ')';
        }

        $aggregateFieldSQL .= ' AS ' . $aggregateConfig->getIdentifier();

        return $aggregateFieldSQL;
    }


    /**
     * Replaces all occurrences of "table.field AS fieldIdentifier" and "table.field"
     *
     * @param string $query
     * @return string
     */
    protected function convertTableFieldToAlias($query)
    {
        $convertTableFieldAsAliasArray = [];
        $convertTableFieldArray = [];

        foreach ($this->getFieldConfigurationCollection() as $fieldConfiguration) {
            if ($fieldConfiguration->getSpecial() == '') {
                $convertTableFieldAsAliasArray[$fieldConfiguration->getIdentifier()] = $fieldConfiguration->getTable() . '.' . $fieldConfiguration->getField() . ' AS ' . $fieldConfiguration->getIdentifier();
                $convertTableFieldArray[$fieldConfiguration->getIdentifier()] = $fieldConfiguration->getTable() . '.' . $fieldConfiguration->getField();
            } else {
                $convertTableFieldAsAliasArray[$fieldConfiguration->getIdentifier()] = '(' . $fieldConfiguration->getSpecial() . ') AS ' . $fieldConfiguration->getIdentifier();
                $convertTableFieldArray[$fieldConfiguration->getIdentifier()] = $fieldConfiguration->getSpecial();
            }
        }


        $query = str_replace(array_values($convertTableFieldAsAliasArray), array_keys($convertTableFieldAsAliasArray), $query);
        $query = str_replace(array_values($convertTableFieldArray), array_keys($convertTableFieldArray), $query);

        return $query;
    }
}
