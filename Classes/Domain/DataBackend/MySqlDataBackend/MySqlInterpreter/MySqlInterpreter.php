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
/**
 * Interpreter for MySql queries
 *  
 * @package Domain
 * @subpackage DataBackend\MySqlDataBackend\MySqlInterpreter
 * @author Michael Knoll 
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreterTest
 */
class MySqlInterpreter extends \PunktDe\PtExtlist\Domain\DataBackend\AbstractQueryInterpreter
{
    /**
     * Holds class names for translating different types of criterias
     *
     * @var array
     */
    protected static $translatorClasses = ['Tx_PtExtlist_Domain_QueryObject_SimpleCriteria' => 'MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator',
                                                'Tx_PtExtlist_Domain_QueryObject_NotCriteria'    => 'MySqlDataBackend_MySqlInterpreter_NotCriteriaTranslator',
                                                'Tx_PtExtlist_Domain_QueryObject_OrCriteria'     => 'MySqlDataBackend_MySqlInterpreter_OrCriteriaTranslator',
                                                'Tx_PtExtlist_Domain_QueryObject_AndCriteria'    => 'MySqlDataBackend_MySqlInterpreter_AndCriteriaTranslator',
                                                'Tx_PtExtlist_Domain_QueryObject_FullTextCriteria'    => 'MySqlDataBackend_MySqlInterpreter_FullTextCriteriaTranslator',
                                                'Tx_PtExtlist_Domain_QueryObject_RawSqlCriteria' => 'MySqlDataBackend_MySqlInterpreter_RawSqlCriteriaTranslator'
    ];
     
     
    
    /**
     * Translates query's criterias into SQL string (without "WHERE")
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     * @return string
     */
    public static function getCriterias(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
    {
        $criteriaArray = [];
        foreach ($query->getCriterias() as $criteria) {
            $criteriaArray[] = self::translateCriteria($criteria);
        }
        $criteriaString = implode(' AND ', $criteriaArray);
        
        return $criteriaString;
    }



    /**
     * Translates given criteria into  SQL WHERE string (without 'WHERE')
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria
     * @throws Exception
     * @return string
     */
    public static function translateCriteria(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria)
    {
        $criteriaString = '';
        $criteriaClass = get_class($criteria);
        if (array_key_exists($criteriaClass, self::$translatorClasses) && class_exists(self::$translatorClasses[$criteriaClass])) {
            $className = self::$translatorClasses[$criteriaClass];
            $criteriaString = call_user_func($className . '::translateCriteria', $criteria);
        } else {
            throw new Exception('Unknown type of criteria ' . get_class($criteria) . ' 1320484761');
        }
        return $criteriaString;
    }
    
    
    
    /**
     * Returns SQL limit string without "LIMIT"
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     * @return string
     */
    public static function getLimit(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
    {
        $limit = $query->getLimit();
        $limitString = preg_replace('/:/', ',', $limit);
        return $limitString;
    }
    
    
    
    /**
     * Returns SQL sortings string without "ORDER BY"
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     * @return string
     */
    public static function getSorting(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
    {
        $directionMap = [\PunktDe\PtExtlist\Domain\QueryObject\Query::SORTINGSTATE_ASC => 'ASC',
                              \PunktDe\PtExtlist\Domain\QueryObject\Query::SORTINGSTATE_DESC => 'DESC'];
        
        $sortingsArray = [];
        foreach ($query->getSortings() as $field => $direction) {
            $sortingsArray[] = $field . ' ' . $directionMap[$direction];
        }
        $sortingString = implode(', ', $sortingsArray);

        return $sortingString;
    }
    
    
    
    /**
     * Returns translated select part of query without 'SELECT'
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query Query to be translated
     * @return string Translated SELECT part of query without 'SELECT'
     */
    public static function getSelectPart(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
    {
        $columnsArray = $query->getFields();
        $selectString = '';
        $selectString = implode(', ', $columnsArray);
        return $selectString;
    }
    
    
    
    /**
     * Returns translated from part of query without 'FROM'
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query Query to be translated
     * @return string Translated FROM part of query without 'FROM'
     */
    public static function getFromPart(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
    {
        $fromArray = $query->getFrom();
        $fromString = '';
        $fromString = implode(', ', $fromArray);
        return $fromString;
    }
    

    /**
     * Returns translated group by fields with out the 'GROUP BY'
     *  
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     * @return string group by part
     */
    public static function getGroupBy(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
    {
        $groupByString = implode(', ', $query->getGroupBy());
        return $groupByString;
    }
    
    
    
    /**
     * Translates whole query with all keywords (SELECT, WHERE, FROM...)
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query Query to be translated
     * @return string Translated query with all keywords
     */
    public static function interpretQuery(\PunktDe\PtExtlist\Domain\QueryObject\Query $query)
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
