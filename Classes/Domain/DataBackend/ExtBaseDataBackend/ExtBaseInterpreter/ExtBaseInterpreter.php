<?php


namespace PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter;

use PunktDe\PtExtlist\Domain\QueryObject\AndCriteria;
use PunktDe\PtExtlist\Domain\QueryObject\Criteria;
use PunktDe\PtExtlist\Domain\QueryObject\NotCriteria;
use PunktDe\PtExtlist\Domain\QueryObject\OrCriteria;
use PunktDe\PtExtlist\Domain\QueryObject\Query;
use PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria;
use TYPO3\CMS\Extbase\Persistence\Generic\Query as GenericQuery;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

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
/**
 * Query interpreter for ExtBase data backend. Translates queries into extbase query objects
 *  
 * TODO by calling static methods in Translator class, we have no loose coupling here. Change this by using instance of translator class.
 *  
 * @package Domain
 * @subpackage DataBackend\ExtBaseDataBackend\ExtBaseInterpreter
 * @see Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreterTest
 */
class ExtBaseInterpreter extends \PunktDe\PtExtlist\Domain\DataBackend\AbstractQueryInterpreter
{
    protected static $translatorClasses = [SimpleCriteria::class => SimpleCriteriaTranslator::class,
                                                NotCriteria::class   => NotCriteriaTranslator::class,
                                                OrCriteria::class     => OrCriteriaTranslator::class,
                                                AndCriteria::class    => AndCriteriaTranslator::class,
    ];
    
    
    /**
     * @see AbstractQueryInterpreter::getCriterias()
     *
     * @param Query $query
     */
    public static function getCriterias(Query $query)
    {
    }
    
    
    
    /**
     * @see AbstractQueryInterpreter::getLimit()
     *
     * @param Query $query
     */
    public static function getLimit(Query $query)
    {
    }
    
    
    
    /**
     * @see AbstractQueryInterpreter::getSorting()
     *
     * @param Query $query
     */
    public static function getSorting(Query $query)
    {
    }
    
    
    
    /**
     * @see AbstractQueryInterpreter::interpretQuery()
     *
     * @param Query $query
     */
    public static function interpretQuery(Query $query)
    {
    }
    
    
    
    /**
     * Translates a query into an extbase query
     *
     * @param Query $query
     * @param Repository $repository
     * @return GenericQuery
     * @throws \Exception
     */
    public static function interpretQueryByRepository(Query $query, Repository $repository)
    {
        $emptyExtbaseQuery = $repository->createQuery(); /* @var $emptyExtbaseQuery GenericQuery */
        
        $constrainedExtbaseQuery = self::setAllCriteriasOnExtBaseQueryByQueryObject($query, $emptyExtbaseQuery, $repository);
        $limitedExtbaseQuery = self::setLimitOnExtBaseQueryByQueryObject($query, $constrainedExtbaseQuery);
        $orderedExtbaseQuery = self::setSortingOnExtBaseQueryByQueryObject($query, $limitedExtbaseQuery);
        
        return $orderedExtbaseQuery;
    }
    
    
    
    /**
     * Sets criterias from given query object on given extbase query object. Returns manipulated extbase query object
     *
     * @param Query $query
     * @param GenericQuery $extbaseQuery
     * @param Repository $repository
     * @return GenericQuery
     * @throws \Exception
     */
    public static function setAllCriteriasOnExtBaseQueryByQueryObject(Query $query, GenericQuery $extbaseQuery, Repository $repository)
    {
        foreach ($query->getCriterias() as $criteria) { /* @var $criteria SimpleCriteria */
            $extbaseQuery = self::setCriteriaOnExtBaseQueryByCriteria($criteria, $extbaseQuery, $repository);
        }

        return $extbaseQuery;
    }



    /**
     * Translates given criteria and adds it to extbase query criterias
     *
     * @param Criteria $criteria
     * @param GenericQuery $extbaseQuery
     * @param Repository $repository
     * @throws \Exception
     * @return GenericQuery
     */
    public static function setCriteriaOnExtBaseQueryByCriteria(Criteria $criteria, GenericQuery $extbaseQuery, Repository $repository)
    {
        $criteriaClass = get_class($criteria);
        switch ($criteriaClass) {
            case SimpleCriteria::class:
                $extbaseQuery = SimpleCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
                break;
            
            case AndCriteria::class:
                $extbaseQuery = AndCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
                break;
            
            case OrCriteria::class:
                $extbaseQuery = OrCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
                break;

            case NotCriteria::class:
                $extbaseQuery = NotCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
                break;

            default:
                throw new \Exception('Unkown criteria type ' . $criteriaClass . ' 1299224408');
                break;
        }
        
        return $extbaseQuery;
    }
    
    
    
    /**
     * Sets a limit on an extbase query by given query object. Returns manipulated extbase query.
     *
     * @param Query $query
     * @param GenericQuery $extbaseQuery
     * @return GenericQuery
     */
    public static function setLimitOnExtBaseQueryByQueryObject(Query $query, GenericQuery $extbaseQuery)
    {
        if ($query->getLimit() != '') {
            list($offset, $limit) = explode(':', $query->getLimit());
            if (!$limit > 0) {
                $extbaseQuery->setLimit(intval($offset)); // no offset set, so offset = limit
            } else {
                $extbaseQuery->setOffset(intval($offset));
                $extbaseQuery->setLimit(intval($limit));
            }
        }
        return $extbaseQuery;
    }
    
    
    
    /**
     * Sets sortings on an extbase query by given query object. Returns manipulated extbase query.
     *
     * @param Query $query Query object to get sortings from
     * @param GenericQuery $extbaseQuery Query object to set sortings on
     * @return GenericQuery Manipulated ExtBase query object
     */
    public static function setSortingOnExtBaseQueryByQueryObject(Query $query, GenericQuery $extbaseQuery)
    {
        $sortings = $query->getSortings();
        $extBaseSortings = [];

        foreach ($sortings as $field => $direction) { /* sorting is array('field' => 'Direction: 1 | -1') */
            $extBaseDirection = $direction == Query::SORTINGSTATE_ASC ? QueryInterface::ORDER_ASCENDING : QueryInterface::ORDER_DESCENDING;
            $extBaseSortings[$field] = $extBaseDirection;
        }
        
        $extbaseQuery->setOrderings($extBaseSortings);
        return $extbaseQuery;
    }
    
    
    
    public static function translateCriteria()
    {
    }
    
    
    
    /**
     * Translates group by part of query
     *
     * @param Query $query
     */
    public static function getGroupBy(Query $query)
    {
    }
}
