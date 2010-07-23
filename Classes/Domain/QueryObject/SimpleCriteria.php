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
 * Implements a simple criteria
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_QueryObject_SimpleCriteria extends Tx_PtExtlist_Domain_QueryObject_Criteria {
	 
	protected $field;
	
	protected $value;
	
	protected $operator;
	
	public function __construct($field = '', $value, $operator) {
		tx_pttools_assert::isNotEmptyString($field, array('message' => 'Field must not be empty! TODO insert timestamp'));
		tx_pttools_assert::isNotEmptyString($operator, array('message' => 'Operator must not be empty! TODO insert timestamp'));
		$this->field = $field;
		$this->value = $value;
		$this->operator = $operator;
	}
	
	
	
	public function getField() {
		return $this->field;
	}
	
	
	
	public function getValue() {
		return $this->value;
	}
	
	
	
	public function getOperator() {
		return $this->operator;
	}
	
}
?>