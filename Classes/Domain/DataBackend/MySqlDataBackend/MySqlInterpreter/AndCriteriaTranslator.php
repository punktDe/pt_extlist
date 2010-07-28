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
 * @package Typo3
 * @subpackage pt_extlist
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_AndCriteriaTranslator implements Tx_PtExtlist_Domain_DataBackend_CriteriaTranslatorInterface {
	
	/**
	 * return translated criteria string 
	 * @param $criteria Tx_PtExtlist_Domain_QueryObject_AndCriteria
	 * @return string
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 26.07.2010
	 */
	public static function translateCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria) {
	    $andCriteriaString = '(' . Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter::translateCriteria($criteria->getFirstCriteria()); 
	   	$andCriteriaString .= ') AND ('; 
	    $andCriteriaString .= Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter::translateCriteria($criteria->getSecondCriteria()) . ')';
	    
	    return $andCriteriaString;
	}
}

?>