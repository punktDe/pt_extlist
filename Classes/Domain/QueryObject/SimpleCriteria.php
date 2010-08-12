<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * Implements a simple criteria which works like
 * 
 * <field><operator><value>
 * 
 * 'testfield' = 'testvalue'
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_QueryObject_SimpleCriteria extends Tx_PtExtlist_Domain_QueryObject_Criteria {
	 
	/**
	 * Holds the field name for which the criteria holds
	 *
	 * @var string
	 */
	protected $field;
	
	
	
	/**
	 * Holds the value against which the criteria is compared
	 *
	 * @var string
	 */
	protected $value;
	
	
	
	/**
	 * Holds the operator with which field and value is compared
	 *
	 * @var string
	 */
	protected $operator;
	
	
	/**
	 * Constructor for criteria. Takes a field name a value and a operator.
	 * Works like 'field' <operator> 'value'
	 *
	 * @param string $field
	 * @param string $value
	 * @param string $operator
	 */
	public function __construct($field = '', $value, $operator) {
		tx_pttools_assert::isNotEmptyString($field, array('message' => 'Field must not be empty! TODO insert timestamp'));
		tx_pttools_assert::isNotEmptyString($operator, array('message' => 'Operator must not be empty! TODO insert timestamp'));
		$this->field = $field;
		$this->value = $value;
		$this->operator = $operator;
	}
	
	
	
   /**
    * Returns true, if criteria is equal to this object
    *
    * @param Tx_PtExtlist_Domain_QueryObject_Criteria $criteria Criteria to be compared with this object
    * @return bool
    */
    public function isEqualTo(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria) {
    	if (!is_a($criteria, __CLASS__)) {
    		return false;
    	} else {
            if ($this->field == $criteria->field && $this->value == $criteria->value && $this->operator == $criteria->operator) {
            	return true;     
            } else {
                return false;
            }
    	}
    }
	
	
	
	/**
	 * Returns field for which criteria holds
	 *
	 * @return string Field name of criteria
	 */
	public function getField() {
		return $this->field;
	}
	
	
	
	/**
	 * Returns value with which field is compared
	 *
	 * @return string Value to compare field with
	 */
	public function getValue() {
		return $this->value;
	}
	
	
	
	/**
	 * Returns operator to be used as comperator
	 *
	 * @return strging operator
	 */
	public function getOperator() {
		return $this->operator;
	}
	
}
?>