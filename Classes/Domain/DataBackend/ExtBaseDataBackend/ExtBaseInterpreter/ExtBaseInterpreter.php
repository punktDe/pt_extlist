<?php


namespace PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter;

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
    protected static $translatorClasses = ['Tx_PtExtlist_Domain_QueryObject_SimpleCriteria' => 'Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator',
                                                'Tx_PtExtlist_Domain_QueryObject_NotCriteria'    => 'Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_NotCriteriaTranslator',
                                                'Tx_PtExtlist_Domain_QueryObject_OrCriteria'     => 'Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_OrCriteriaTranslator',
                                                'Tx_PtExtlist_Domain_QueryObject_AndCriteria'    => 'Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_AndCriteriaTranslator',
    ];
    
    
    /**
     * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::getCriterias()
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     */
    public static function getCriterias(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
    {
    }
    
    
    
    /**
     * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::getLimit()
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     */
    public static function getLimit(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
    {
    }
    
    
    
    /**
     * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::getSorting()
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     */
    public static function getSorting(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
    {
    }
    
    
    
    /**
     * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::interpretQuery()
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     */
    public static function interpretQuery(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
    {
    }
    
    
    
    /**
     * Translates a query into an extbase query
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $repository
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Query
     */
    public static function interpretQueryByRepository(\PunktDe\PtExtlist\Domain\QueryObject\Query $query, \TYPO3\CMS\Extbase\Persistence\Repository $repository)
    {
        $emptyExtbaseQuery = $repository->createQuery(); /* @var $emptyExtbaseQuery \TYPO3\CMS\Extbase\Persistence\Generic\Query */
        
        $constrainedExtbaseQuery = self::setAllCriteriasOnExtBaseQueryByQueryObject($query, $emptyExtbaseQuery, $repository);
        $limitedExtbaseQuery = self::setLimitOnExtBaseQueryByQueryObject($query, $constrainedExtbaseQuery);
        $orderedExtbaseQuery = self::setSortingOnExtBaseQueryByQueryObject($query, $limitedExtbaseQuery);
        
        return $orderedExtbaseQuery;
    }
    
    
    
    /**
     * Sets criterias from given query object on given extbase query object. Returns manipulated extbase query object
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $repository
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Query
     */
    public static function setAllCriteriasOnExtBaseQueryByQueryObject(\PunktDe\PtExtlist\Domain\QueryObject\Query $query, \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery, \TYPO3\CMS\Extbase\Persistence\Repository $repository)
    {
        foreach ($query->getCriterias() as $criteria) { /* @var $criteria Tx_PtExtlist_Domain_QueryObject_SimpleCriteria */
            $extbaseQuery = self::setCriteriaOnExtBaseQueryByCriteria($criteria, $extbaseQuery, $repository);
        }

        return $extbaseQuery;
    }



    /**
     * Translates given criteria and adds it to extbase query criterias
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $repository
     * @throws Exception
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Query
     */
    public static function setCriteriaOnExtBaseQueryByCriteria(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria, \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery, \TYPO3\CMS\Extbase\Persistence\Repository $repository)
    {
        $criteriaClass = get_class($criteria);
        switch ($criteriaClass) {
            case 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria':
                $extbaseQuery = \PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter\SimpleCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
                break;
            
            case 'Tx_PtExtlist_Domain_QueryObject_AndCriteria':
                $extbaseQuery = \PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter\AndCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
                break;
            
            case 'Tx_PtExtlist_Domain_QueryObject_OrCriteria':
                $extbaseQuery = \PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter\OrCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
                break;

            case 'Tx_PtExtlist_Domain_QueryObject_NotCriteria':
                $extbaseQuery = \PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter\NotCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
                break;

            default:
                throw new Exception('Unkown criteria type ' . $criteriaClass . ' 1299224408');
                break;
        }
        
        return $extbaseQuery;
    }
    
    
    
    /**
     * Sets a limit on an extbase query by given query object. Returns manipulated extbase query.
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Query
     */
    public static function setLimitOnExtBaseQueryByQueryObject(\PunktDe\PtExtlist\Domain\QueryObject\Query $query, \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery)
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
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query Query object to get sortings from
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery Query object to set sortings on
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Query Manipulated ExtBase query object
     */
    public static function setSortingOnExtBaseQueryByQueryObject(\PunktDe\PtExtlist\Domain\QueryObject\Query $query, \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery)
    {
        $sortings = $query->getSortings();
        $extBaseSortings = [];

        foreach ($sortings as $field => $direction) { /* sorting is array('field' => 'Direction: 1 | -1') */
            $extBaseDirection = $direction == \PunktDe\PtExtlist\Domain\QueryObject\Query::SORTINGSTATE_ASC ? \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING : \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
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
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     */
    public static function getGroupBy(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
    {
    }
}
