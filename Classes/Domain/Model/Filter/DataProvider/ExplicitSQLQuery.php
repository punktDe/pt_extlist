<?php

namespace PunktDe\PtExtlist\Domain\Model\Filter\DataProvider;



/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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

use Doctrine\DBAL\Connection;
use PunktDe\PtExtbase\Logger\Logger;
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtbase\Exception\Assertion;
use PunktDe\PtExtlist\Utility\RenderValue;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Implements data provider for explicit defined SQL query
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class ExplicitSQLQuery extends \PunktDe\PtExtlist\Domain\Model\Filter\DataProvider\AbstractDataProvider
{
    /**
     * @var string
     */
    protected $selectPart;



    /**
     * @var string
     */
    protected $fromPart;



    /**
     * @var string
     */
    protected $wherePart;



    /**
     * @var string
     */
    protected $groupByPart;



    /**
     * @var string
     */
    protected $orderByPart;



    /**
     * @var string
     */
    protected $limitPart;



    /**
     * @var array
     */
    protected $displayFields;



    /**
     * @var string
     */
    protected $filterField;



    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;


    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Init the data provider
     * @throws Assertion
     */
    public function init()
    {
        $this->logger = GeneralUtility::makeInstance(Logger::class);

        $sqlQuerySettings = $this->filterConfig->getSettings('optionsSqlQuery');

        foreach ($sqlQuerySettings as $type => $part) {
            if (!is_array($part)) {
                $sqlQuerySettings[$type] = trim($part);
            }
        }

        $fromPart = trim(RenderValue::stdWrapIfPlainArray($sqlQuerySettings['from']));
        $items = GeneralUtility::trimExplode(' ', $fromPart);

        Assert::isInRange(count($items), 1 ,2 , ['message' => 'baseFromClause of Backend in TS has not the correct values! This should be table name and optional alias added by space! 1280234420']);

        $fromTable= trim($items[0]);
        $fromAlias = $items[1] ?? null;

        /** @var Connection $connection */
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($fromTable);
        $this->queryBuilder = $connection->createQueryBuilder();

        if ($sqlQuerySettings['select']) {
            $this->queryBuilder->selectLiteral(RenderValue::stdWrapIfPlainArray($sqlQuerySettings['select']));
        }
        $this->queryBuilder->from($fromTable, $fromAlias);

        if ($sqlQuerySettings['join']) {
            foreach ($sqlQuerySettings['join'] as $joinPart) {
                $joinFromAlias = trim($joinPart['fromAlias']);
                $joinTable = trim($joinPart['table']);
                $joinAlias = trim($joinPart['alias']) ?: trim($joinPart['table']);
                $joinOn = trim($joinPart['on']);
                $joinMethod = (strtolower(trim($joinPart['type'])) ?: 'inner') . 'Join';
                $this->queryBuilder->$joinMethod($joinFromAlias, $joinTable, $joinAlias, $joinOn);
            }
        }


        if ($sqlQuerySettings['where']) {
            $this->queryBuilder->where(RenderValue::stdWrapIfPlainArray($sqlQuerySettings['where']));
        }

        if ($sqlQuerySettings['orderBy']) {
            $items = GeneralUtility::trimExplode(' ', $sqlQuerySettings['orderBy']);
            $this->queryBuilder->orderBy($items[0], $items[1] ?? '');

        }
        if ($sqlQuerySettings['groupBy']) {
            $this->queryBuilder->groupBy(RenderValue::stdWrapIfPlainArray($sqlQuerySettings['groupBy']));
        }
        if ($sqlQuerySettings['limit']) {
            $items = GeneralUtility::trimExplode(',', RenderValue::stdWrapIfPlainArray($sqlQuerySettings['limit']));
            if ($items > 1) {
                $this->queryBuilder->setFirstResult($items[0]);
                $this->queryBuilder->setMaxResults($items[1]);
            } else {
                $this->queryBuilder->setMaxResults($items[0]);
            }
        }

        $this->filterField = trim($this->filterConfig->getSettings('filterField'));
        $this->displayFields = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->filterConfig->getSettings('displayFields'));

        Assert::isNotEmptyString($this->filterField, ['info' => 'No filter field is given for filter ' . $this->filterConfig->getFilterIdentifier() . ' 1315221957']);
        Assert::isNotEmptyString($sqlQuerySettings['select'], ['info' => 'No Select part is given for filter ' . $this->filterConfig->getFilterIdentifier() . ' 1315221958']);
        Assert::isNotEmptyString($sqlQuerySettings['from'], ['info' => 'No from part is given for filter ' . $this->filterConfig->getFilterIdentifier() . ' 1315221959']);
    }


    /**
     * Return the rendered filterOptions
     *
     * @return array filter options
     * @throws \Exception
     */
    public function getRenderedOptions()
    {
        $renderedOptions = [];
        $options = $this->getDataFromSqlServer();
        foreach ($options as $optionData) {
            $optionKey = $optionData[$this->filterField];

            $renderedOptions[$optionKey] = $optionData;
            $renderedOptions[$optionKey]['value'] = $this->renderOptionData($optionData);
            $renderedOptions[$optionKey]['selected'] = false;
        }
        return $renderedOptions;
    }



    /**
     * Render a single option line by cObject or default
     *
     * @param array $optionData
     * @return string The rendered option
     */
    protected function renderOptionData($optionData)
    {
        $values = [];
        if (is_array($this->displayFields)) {
            foreach ($this->displayFields as $displayField) {
                $values[] = $optionData[$displayField];
            }
        }
        $optionData['allDisplayFields'] = implode(' ', $values);
        $option = RenderValue::renderByConfigObjectUncached($optionData, $this->filterConfig);
        return $option;
    }



    /**
     * @throws \Exception
     * @return array of options
     */
    protected function getDataFromSqlServer()
    {
        /** @var QueryBuilder $queryBuilder */

        //$query = $this->dbObj->SELECTquery($this->selectPart, $this->fromPart, $this->wherePart, $this->groupByPart, $this->orderByPart, $this->limitPart); // this method only combines the parts

        //$dataSource = DataBackendFactory::getInstanceByListIdentifier($this->filterConfig->getListIdentifier())->getDataSource();

//        if (!method_exists($dataSource, 'executeQuery')) {
//            throw new \Exception('The defined dataSource has no method executeQuery and is therefore not usable with this dataProvider!', 1315216209);
//        }

        ###TODO
        $query = $this->queryBuilder->getSql();
        $data =  $this->queryBuilder->execute()->fetchAll();

         $this->logger->debug('MYSQL QUERY : ' . $this->filterConfig->getListIdentifier() . ' -> Filter::ExplicitSQLQuery', 'pt_extlist',  ['query' => $query]);

        return $data;
    }
}
