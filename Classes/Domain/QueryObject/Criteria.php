<?php


namespace PunktDe\PtExtlist\Domain\QueryObject;

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
 * Class implements criteria for query objects. Besides an abstract base class for all other criteria,
 * this class acts as a factory for all criteria objects. 
 *
 * @package Domain
 * @subpackage QueryObject
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_QueryObject_CriteriaTest
 */
class Criteria
{
    /**
     * Returns true, if given criteria is equal to this object
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria Criteria to be compared with this object
     * @return bool
     */
    public function isEqualTo(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria)
    {
        // This class is something like abstract, so it can't be equal to anything else!
        return false;
    }
    
    
    
    /**
     * Returns a new equals criteria for given field and value
     *
     * @param string $field Field name to compare value with
     * @param string $value Value to be compared
     * @param boolean $treatValueAsString
     * @return \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria
     */
    public static function equals($field, $value, $treatValueAsString = false)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria($field, $value, '=', $treatValueAsString);
    }
    
    
    
    /**
     * Returns a new relation criteria for given field and value
     *
     * @param string $field Field name to compare value with
     * @param string $value Value to be compared
     * @return \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria
     */
    public static function relation($field, $value)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria($field, $value, '.');
    }
    
    
    
    /**
     * Returns a new greater than criteria for given field and value
     *
     * @param string $field Field to be compared
     * @param string $value Value to be compared
     * @return \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria
     */
    public static function greaterThan($field, $value)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria($field, $value, '>');
    }
    
    
    
    /**
     * Returns a new greater than equals criteria for given field and value
     *
     * @param string $field Field to be compared
     * @param string $value Value to be compared
     * @return \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria
     */
    public static function greaterThanEquals($field, $value)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria($field, $value, '>=');
    }
    
    
    
    /**
     * Returns a new less than criteria for given field and value
     *
     * @param string $field Field to be compared with
     * @param string $value Value to be compared with
     * @return \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria
     */
    public static function lessThan($field, $value)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria($field, $value, '<');
    }
    
    
    
    /**
     * Returns a new less than equals criteria for given field and value
     *
     * @param string $field Field to be compared with
     * @param string $value Value to be comperd with
     * @return \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria
     */
    public static function lessThanEquals($field, $value)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria($field, $value, '<=');
    }
    
    
    
    /**
     * Returns a new like criteria for given field and value
     *
     * @param string $field Field to be compared with
     * @param string $value Value to be compared with
     * @return \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria
     */
    public static function like($field, $value)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria($field, $value, 'LIKE');
    }
    
    
    
    /**
     * Returns a new 'in' criteria for given field and value
     *
     * @param string $needle Field to be compared with
     * @param array $haystack Values to compare content of field with
     * @return \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria
     */
    public static function in($needle, $haystack)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria($needle, $haystack, 'IN');
    }
    
    
    
    /**
     * Returns a new 'and' criteria for two given criterias
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria1 First criteria to be 'anded'
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria2 Second criteria to be 'anded'
     * @return \PunktDe\PtExtlist\Domain\QueryObject\AndCriteria
     */
    public static function andOp(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria1, \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria2)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\AndCriteria($criteria1, $criteria2);
    }
    
    
    
    /**
     * Returns a new 'or' criteria for two given criterias
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria1 First criteria to be 'ored'
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria2 Second criteria to be 'ored'
     * @return \PunktDe\PtExtlist\Domain\QueryObject\OrCriteria
     */
    public static function orOp(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria1, \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria2)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\OrCriteria($criteria1, $criteria2);
    }
    
    
    
    /**
     * Returns a new 'not' criteria for a given criteria
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria Criteria to be negated
     * @return \PunktDe\PtExtlist\Domain\QueryObject\NotCriteria
     */
    public static function notOp(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\NotCriteria($criteria);
    }



    /**
     * Returns a new 'fullText' criteria
     *
     * @static
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection $fields
     * @param string $searchString
     * @param array $searchParameter
     * @return \PunktDe\PtExtlist\Domain\QueryObject\FullTextCriteria
     */
    public static function fullText(\PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection $fields, $searchString, array $searchParameter = [])
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\FullTextCriteria($fields, $searchString, $searchParameter);
    }



    /**
     * Returns a new  raw sql criteria.
     *
     * WARNING: No quoting will be used to handle query string. Developer is
     * responsible for correct quoting of SQL string!
     *
     * @static
     * @param $rawSqlString
     * @return \PunktDe\PtExtlist\Domain\QueryObject\RawSqlCriteria
     */
    public static function rawSql($rawSqlString)
    {
        return new \PunktDe\PtExtlist\Domain\QueryObject\RawSqlCriteria($rawSqlString);
    }
}
