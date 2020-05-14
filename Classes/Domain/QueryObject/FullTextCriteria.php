<?php


namespace PunktDe\PtExtlist\Domain\QueryObject;

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
 * Implements a criteria for special fullTextSearch
 *
 * @package Domain
 * @subpackage QueryObject
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Domain_QueryObject_FullTextCriteriaTest
 */
class FullTextCriteria extends \PunktDe\PtExtlist\Domain\QueryObject\Criteria
{
    /**
     * Holds the fields for which the criteria holds
     *
     * @var \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection
     */
    protected $fields;
    
    
    
    /**
     * Holds the searchString to match against
     *
     * @var string
     */
    protected $searchString;


    /**
     * Holds additional, backend dependent search parameter
     * @var array
     */
    protected $searchParameter = [];
    
    
    /**
     * Constructor for criteria. Takes a collection of fields and a search string.
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection $fields
     * @param string $searchString
     * @param array $searchParameter
     */
    public function __construct(\PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection $fields, $searchString, array $searchParameter = [])
    {
        Assert::isPositiveInteger($fields->count(), ['message' => 'No field given to search in! 1313532571']);
        Assert::isNotEmptyString($searchString, ['message' => 'SearchString must not be empty! 1313532596']);
        $this->fields = $fields;
        $this->searchString = $searchString;
        $this->searchParameter = $searchParameter;
    }
    
    
    
   /**
     * Returns true, if criteria is equal to this object
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria Criteria to be compared with this object
     * @return bool
     */
    public function isEqualTo(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria)
    {
        if (!is_a($criteria, __CLASS__)) {
            return false;
        } else {
            /* @var $criteria Tx_PtExtlist_Domain_QueryObject_FullTextCriteria */
            if ($this->fields == $criteria->fields && $this->searchString == $criteria->searchString && $this->searchParameter == $criteria->searchParameter) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    
    
    /**
     * Returns field for which criteria holds
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection Field name of criteria
     */
    public function getFields()
    {
        return $this->fields;
    }
    
    
    
    /**
     * Returns value with which field is compared
     *
     * @return string Value to compare field with
     */
    public function getSearchString()
    {
        return $this->searchString;
    }



    /**
     * @param string $key
     * @return array of additional searchParameter
     */
    public function getSearchParameter($key = null)
    {
        if (!$key) {
            return $this->searchParameter;
        } else {
            if (array_key_exists($key, $this->searchParameter)) {
                return $this->searchParameter[$key];
            }
        }
        return null;
    }
}
