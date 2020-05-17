<?php

namespace PunktDe\PtExtlist\Domain\DataBackend\MySqlDataBackend\MySqlInterpreter;

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

use PunktDe\PtExtlist\Domain\DataBackend\AbstractQueryInterpreter;
use PunktDe\PtExtlist\Domain\QueryObject\AndCriteria;
use PunktDe\PtExtlist\Domain\QueryObject\Criteria;
use PunktDe\PtExtlist\Domain\QueryObject\FullTextCriteria;
use PunktDe\PtExtlist\Domain\QueryObject\NotCriteria;
use PunktDe\PtExtlist\Domain\QueryObject\OrCriteria;
use PunktDe\PtExtlist\Domain\QueryObject\Query;
use PunktDe\PtExtlist\Domain\QueryObject\RawSqlCriteria;
use PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria;

/**
 * Interpreter for MySql queries
 *  
 * @package Domain
 * @subpackage DataBackend\MySqlDataBackend\MySqlInterpreter
 * @author Michael Knoll 
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreterTest
 */
class MySqlInterpreter extends AbstractQueryInterpreter
{
    /**
     * Holds class names for translating different types of criterias
     *
     * @var array
     */
    protected static $translatorClasses = [SimpleCriteria::class   => SimpleCriteriaTranslator::class,
                                           NotCriteria::class      => NotCriteriaTranslator::class,
                                           OrCriteria::class       => OrCriteriaTranslator::class,
                                           AndCriteria::class      => AndCriteriaTranslator::class,
                                           FullTextCriteria::class => FullTextCriteriaTranslator::class,
                                           RawSqlCriteria::class   => RawSqlCriteriaTranslator::class
    ];
     
     
    
    /**
     * Translates query's criterias into SQL string (without "WHERE")
     *
     * @param Query $query
     * @return string
     * @throws \Exception
     */
    public static function getCriterias(Query $query)
    {
        $criteriaArray = [];
        foreach ($query->getCriterias() as $criteria) {
            $criteriaArray[] = self::translateCriteria($criteria);
        }
        return implode(' AND ', $criteriaArray);
    }



    /**
     * Translates given criteria into  SQL WHERE string (without 'WHERE')
     *
     * @param Criteria $criteria
     * @throws \Exception
     * @return string
     */
    public static function translateCriteria($criteria)
    {
        $criteriaClass = get_class($criteria);
        if (array_key_exists($criteriaClass, self::$translatorClasses) && class_exists(self::$translatorClasses[$criteriaClass])) {
            $className = self::$translatorClasses[$criteriaClass];
            $criteriaString = call_user_func($className . '::translateCriteria', $criteria);
        } else {
            throw new \Exception('Unknown type of criteria ' . get_class($criteria) . ' 1320484761');
        }
        return $criteriaString;
    }
    
    
    
    /**
     * Returns SQL limit string without "LIMIT"
     *
     * @param Query $query
     * @return string
     */
    public static function getLimit(Query $query)
    {
        $limit = $query->getLimit();
        return  preg_replace('/:/', ',', $limit);
    }
    
    
    
    /**
     * Returns SQL sortings string without "ORDER BY"
     *
     * @param Query $query
     * @return string
     */
    public static function getSorting(Query $query): string
    {
        $directionMap = [Query::SORTINGSTATE_ASC => 'ASC',
                              Query::SORTINGSTATE_DESC => 'DESC'];
        
        $sortingsArray = [];
        foreach ($query->getSortings() as $field => $direction) {
            ###TODO
            //$sortingsArray[] = $field . ' ' . $directionMap[$direction];
            $sortingsArray[] = $field;
        }
        return implode(', ', $sortingsArray);
    }
    
    
    
    /**
     * Returns translated select part of query without 'SELECT'
     *
     * @param Query $query Query to be translated
     * @return string Translated SELECT part of query without 'SELECT'
     */
    public static function getSelectPart(Query $query): string
    {
        $columnsArray = $query->getFields();
        return implode(', ', $columnsArray);
    }
    
    
    
    /**
     * Returns translated from part of query without 'FROM'
     *
     * @param Query $query Query to be translated
     * @return string Translated FROM part of query without 'FROM'
     */
    public static function getFromPart(Query $query)
    {
        $fromArray = $query->getFrom();
        return implode(', ', $fromArray);
    }
    

    /**
     * Returns translated group by fields with out the 'GROUP BY'
     *  
     * @param Query $query
     * @return string group by part
     */
    public static function getGroupBy(Query $query): string
    {
        return implode(', ', $query->getGroupBy());
    }
    
    
    
    /**
     * Translates whole query with all keywords (SELECT, WHERE, FROM...)
     *
     * @param Query $query Query to be translated
     * @return string Translated query with all keywords
     * @throws \Exception
     */
    public static function interpretQuery(Query $query): string
    {
        $sqlString = '';
        
        $sqlString .= self::getSelectPart($query) != '' ? 'SELECT ' . self::getSelectPart($query) . "\n" : '';
        $sqlString .= self::getFromPart($query) != '' ? ' FROM ' . self::getFromPart($query) . "\n" : '';
        $sqlString .= self::getCriterias($query) != '' ? ' WHERE ' . self::getCriterias($query) . "\n" : '';
        // TODO implement group by!
        //$sqlString .= $this->getGroupByPart($query);
        $sqlString .= self::getSorting($query) != '' ? ' ORDER BY ' . self::getSorting($query) . "\n" : '';
        $sqlString .= self::getLimit($query) != '' ? ' LIMIT ' . self::getLimit($query) . "\n" : '';
        
        return $sqlString;
    }
}
