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
 * Translator for AND criteria
 * 
 * @package Domain
 * @subpackage DataBackend\MySqlDataBackend\MySqlInterpreter
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator implements Tx_PtExtlist_Domain_DataBackend_CriteriaTranslatorInterface {
	
	/**
	 * translate simple criteria 
	 * 
	 * @param $criteria Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
	 * @return string
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 26.07.2010
	 */
	public static function translateCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria) {
        return '' . $criteria->getField() . ' ' . $criteria->getOperator() . ' ' . self::wrapArrayInBrackets($criteria->getValue());    
	}
	
	
	
	/**
	 * Wraps an array in ("<array[0]>",...,"<array[n]>") and escapes values.
	 * Returns string as escaped string if no array is given
	 *
	 * @param mixed $value
	 */
	public static function wrapArrayInBrackets($value) {
		if (is_array($value)) {
			$escapedValues = self::escapeArray($value);
			$returnString = '("' . implode('", "', $escapedValues) . '")';
        } else {
            $returnString = '"' . mysql_real_escape_string($value) . '"';
		}
		return $returnString;
	}
	
	
	
	/**
	 * Escapes all values of a given array
	 *
	 * @param array $values Array with values that should be escaped
	 * @return array Array with escaped values
	 */
	public static function escapeArray(array $values) {
		foreach($values as $value) {
			$escapedValues[] = mysql_real_escape_string($value);
		}
		return $escapedValues;
	}
	
}

?>