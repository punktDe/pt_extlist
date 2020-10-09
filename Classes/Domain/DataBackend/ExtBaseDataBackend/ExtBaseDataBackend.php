<?php

namespace PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend;


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

use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig;
use PunktDe\PtExtlist\Domain\DataBackend\AbstractDataBackend;
use PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter\ExtBaseInterpreter;
use PunktDe\PtExtlist\Domain\Model\Filter\Filterbox;
use PunktDe\PtExtlist\Domain\Model\Filter\FilterInterface;
use PunktDe\PtExtlist\Domain\Model\Lists\IterationListDataInterface;
use PunktDe\PtExtlist\Domain\Model\Sorting\SortingState;
use PunktDe\PtExtlist\Domain\QueryObject\Query;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Backend for using pt_extlist with ExtBase Domain objects
 * 
 * TODO at the moment 2 queries are send to the database: 1) gather data 2) count rows in data --> cache data and count rows there
 * 
 * @author Michael Knoll 
 * @package Domain
 * @subpackage DataBackend\ExtBaseDataBackend
 */
class ExtBaseDataBackend extends AbstractDataBackend
{
    /**
     * Holds a repository for creating domain objects
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Repository
     */
    protected $repository;
    
    
    
    /**
     * Factory method for repository to be used with this data backend.
     * 
     * Although it's called data source, we create an extbase repository here which acts as a 
     * datasource for this backend.
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @return mixed
     */
    public static function createDataSource(ConfigurationBuilder $configurationBuilder)
    {
        $dataBackendSettings =  $configurationBuilder->getSettingsForConfigObject('dataBackend');
        Assert::isNotEmptyString($dataBackendSettings['repositoryClassName'], ['message' => 'No repository class name is given for extBase backend. 1281546327']);
        Assert::isTrue(class_exists($dataBackendSettings['repositoryClassName']), ['message' => 'Given class does not exist: ' . $dataBackendSettings['repositoryClassName'] . ' 1281546328']);
        $repository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class)->get($dataBackendSettings['repositoryClassName']);
        return $repository;
    }
    
    
    
    /**
     * (non-PHPdoc)
     * @see Classes/Domain/DataBackend/AbstractDataBackend::buildListData()
     */
    protected function buildListData()
    {
        $extbaseQuery = $this->buildExtBaseQuery();
        $data = $extbaseQuery->execute();

        return $this->dataMapper->getMappedListData($data);
    }



    /**
     * We implement template method for initializing backend
     */
    protected function initBackend()
    {
        parent::initBackend();
        // As pager->getCurrentPage is requested during $this->getTotalItemsCount(),
        // we have to set it to infinity first and later set correct item count!
        $this->pagerCollection->setItemCount(PHP_INT_MAX);
    }


    /**
     * @param Query $groupDataQuery
     * @param array $excludeFilters
     * @param FilterConfig $filterConfig
     * @return array
     */
    public function getGroupData(Query $groupDataQuery, $excludeFilters = [],
                                 FilterConfig $filterConfig = null)
    {
        /**
         * This is a proof of concept. To make this work, we use group filter TS configuration as follows:
         *
         * additionalTables = \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository
         * --> this is used to register a different repository than backend uses to create and execute query for group data
         *
         * displayFields = grouptitle
         * --> this is used to generate the options of the group filter by the domain objects returned by repository
         *
         * filterField = groupuid
         * --> this is used to generate value of selected objects (the value that is used for filtering)
         *
         * TODO no row count is possible at the moment (could only be realised by a 'non-Extbase SQL query'
         */

        $query = $this->buildGenericQueryWithoutPager($excludeFilters);
        $query = $this->mergeGenericQueries($query, $groupDataQuery);

        if (count($groupDataQuery->getFrom()) >= 1) { // use different repository for group data query
            $fromArray = $groupDataQuery->getFrom();

            $repositoryClassName = $fromArray[0];
            Assert::isTrue(class_exists($repositoryClassName), ['message' => 'Configuration for group filter expects ' . $repositoryClassName . ' to be a classname but it is not. 1282245744']);

            $repository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($repositoryClassName);
            Assert::isTrue($repository instanceof \TYPO3\CMS\Extbase\Persistence\Repository, ['message' => 'Class ' . $repositoryClassName . ' does not implement an extbase repository']);
        } else {
            $repository = $this->repository;
        }

        $extBaseQuery = ExtBaseInterpreter::interpretQueryByRepository($query, $repository);

        if ($this->backendConfiguration->getSettings('respectStoragePage') == 0) {
            $extBaseQuery->getQuerySettings()->setRespectStoragePage(false);
        }

        $domainObjectsForFilterOptions = $extBaseQuery->execute();

        $result = [];

        foreach ($domainObjectsForFilterOptions as $domainObjectForFilterOption) {
            $row = [];

            foreach ($groupDataQuery->getFields() as $field) {
                list($dottedField, $alias) = explode('AS', $field);
                list($object, $property) = explode('.', $dottedField);
                $getterMethodName = 'get' . ucfirst(trim($property));
                $row[trim($alias)] = $domainObjectForFilterOption->$getterMethodName();
            }
            
            $result[] = $row;
        }

        return $result;
    }
    
    
    
    /**
     * Merges criterias for two given queries
     * 
     * TODO put this into query class!
     *
     * @param Query $resultQuery Query to be returned after merge
     * @param Query $queryToBeMerged Query to be merged into other query
     * @return Query
     */
    protected function mergeGenericQueries(Query $resultQuery, Query $queryToBeMerged)
    {
        // TODO merge other things, except criterias
        foreach ($queryToBeMerged->getCriterias() as $criteria) {
            $resultQuery->addCriteria($criteria);
        }
        return $resultQuery;
    }
    
    
    
    /**
     * Injects a query interpreter
     * 
     * This method is overwritten to make sure that correct type for interpreter is injected
     *
     * TODO this method is not really required ATM, as all methods in query interpreter are static ATM
     *
     * @param ExtBaseInterpreter $queryInterpreter
     */
    public function _injectQueryInterpreter($queryInterpreter)
    {
        Assert::isTrue($queryInterpreter instanceof ExtBaseInterpreter);
        parent::_injectQueryInterpreter($queryInterpreter);
    }

    
    /**
     * Builds query for current pager, filter and sorting settings
     *
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Query
     */
    protected function buildExtBaseQuery()
    {
        // Create extlist query object for current request
        $query = $this->buildGenericQueryWithoutPager();
        $this->setLimitOnQuery($query);
        $this->setSortingOnQuery($query);

        // Create Extbase query for current request by translating extlist query
        $extbaseQuery = ExtBaseInterpreter::interpretQueryByRepository($query, $this->repository);

        /* @var $extbaseQuery \TYPO3\CMS\Extbase\Persistence\Generic\Query */
        if ($this->backendConfiguration->getSettings('respectStoragePage') == 0) {
            $extbaseQuery->getQuerySettings()->setRespectStoragePage(false);
        }

        return $extbaseQuery;
    }



    /**
     * Set limits from current pager on given query object.
     * 
     * @param Query $query
     * @return void
     */
    protected function setLimitOnQuery(Query $query)
    {
        if ($this->pagerCollection->isEnabled()) {
            $limitPart = '';
            $pagerOffset = $this->pagerCollection->getItemOffset();
            $pagerLimit = intval($this->pagerCollection->getItemsPerPage());
            $limitPart .= $pagerOffset > 0 ? $pagerOffset . ':' : '';
            $limitPart .= $pagerLimit > 0 ? $pagerLimit : '';
            $query->setLimit($limitPart);
        }
    }



    /**
     * Sets sorting either from backend config or from sorter on given query
     * 
     * @param Query $query
     * @return void
     */
    protected function setSortingOnQuery(Query $query)
    {
        if (count($this->sorter->getSortingStateCollection()) > 0) {
            $this->setSortingFromSorterOnQuery($query);
        } elseif ($this->backendConfiguration->getDataBackendSettings('sorting') != '') {
            $this->setSortingFromDefaultSortingOnQuery($query);
        }
    }



    /**
     * Sets sorting from sorter on given query
     * 
     * @param Query $query
     * @return void
     */
    protected function setSortingFromSorterOnQuery(Query $query)
    {
        $sorting = [];
        $sortingStateCollection = $this->sorter->getSortingStateCollection();
        foreach ($sortingStateCollection as $sortingState) { /* @var $sortingState SortingState */
            $fieldName = $sortingState->getField()->getIdentifier();
            $sorting[$fieldName] = $sortingState->getDirection();
        }
        $query->addSortingArray($sorting);
    }



    /**
     * Sets sortings state on given query from backend sorting configuration
     *
     * @param Query $query
     * @return void
     */
    protected function setSortingFromDefaultSortingOnQuery(Query $query)
    {
        list($field, $direction) = explode(' ', $this->backendConfiguration->getDataBackendSettings('sorting'));
        $sorting = [];
        $sorting[$field] = strtoupper($direction) == 'DESC' ?
            Query::SORTINGSTATE_DESC : Query::SORTINGSTATE_ASC;
        $query->addSortingArray($sorting);
    }
    
    
    
    /**
     * Builds extlist query object without regarding pager
     *
     * @param array $excludeFilters
     * @return Query
     */
    protected function buildGenericQueryWithoutPager(array $excludeFilters = [])
    {
        $query = $this->buildGenericQueryExcludingFilters($excludeFilters);
        return $query;
    }
    
    
    
    /**
     * Builds extlist query object excluding criterias from filters given by parameter
     *
     * @param array $excludeFilters Array of <filterbox>.<filter> identifiers to be excluded from query
     * @return Query
     */
    protected function buildGenericQueryExcludingFilters(array $excludeFilters = [])
    {
        $query = new Query();
        
        foreach ($this->filterboxCollection as $filterbox) { /* @var $filterbox Filterbox */

            foreach ($filterbox as $filter) { /* @var $filter FilterInterface */
                if (!is_array($excludeFilters[$filterbox->getfilterboxIdentifier()]) || !in_array($filter->getFilterIdentifier(), $excludeFilters[$filterbox->getfilterboxIdentifier()])) {
                    $criterias = $filter->getFilterQuery()->getCriterias();

                    foreach ($criterias as $criteria) {
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
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Query
     */
    protected function buildExtBaseQueryWithoutPager()
    {
        $extbaseQuery = ExtBaseInterpreter::interpretQueryByRepository(
            $this->buildGenericQueryWithoutPager(), $this->repository); /* @var $extbaseQuery \TYPO3\CMS\Extbase\Persistence\Generic\Query */

        if ($this->backendConfiguration->getSettings('respectStoragePage') == 0) {
            $extbaseQuery->getQuerySettings()->setRespectStoragePage(false);
        }

        return $extbaseQuery;
    }
    
    
    
    /**
     * @see DataBackendInterface::getTotalItemsCount()
     *
     * @return integer
     */
    public function getTotalItemsCount()
    {
        $count = $this->buildExtBaseQueryWithoutPager()->execute()->count();
        $this->pagerCollection->setItemCount($count);
        return $count;
    }
    
    
    /**
     * (non-PHPdoc)
     * @see Classes/Domain/DataBackend/DataBackendInterface::getAggregateByConfig()
     */
    public function getAggregatesByConfigCollection(AggregateConfigCollection $aggregateDataConfig)
    {
        // TODO: implement me!
        throw new \Exception('Aggregates are not yet available in extbase Backend');
    }
    
    
    /**
     * Injector for data source. Expects \TYPO3\CMS\Extbase\Persistence\Repository to be given as datasource
     *
     * @param mixed $dataSource
     */
    public function _injectDataSource($dataSource)
    {
        Assert::isInstanceOf($dataSource, '\TYPO3\CMS\Extbase\Persistence\Repository', ['message' => 'Given data source must implement \TYPO3\CMS\Extbase\Persistence\Repository but did not! 1281545172']);
        $this->repository = $dataSource;
    }


    /**
     * @return IterationListDataInterface|void
     * @throws \Exception
     */
    public function getIterationListData()
    {
        throw new Exception('The extbase databackend does not support iteration lists yet! Ask a friendly extlist hacker to implement it :)', 1349282023);
    }
}
