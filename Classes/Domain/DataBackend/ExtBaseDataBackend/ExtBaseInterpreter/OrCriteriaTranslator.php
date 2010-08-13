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
 * Translator for or criteria for extbase data backend interpreter
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
 class Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_OrCriteriaTranslator 
    implements Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseCriteriaTranslatorInterface {
	
	/**
     * Translates a query an manipulates given query object
     *
     * @param Tx_PtExtlist_Domain_QueryObject_Criteria $criteria Criteria to be translated
     * @param Tx_Extbase_Persistence_Query $extbaseQuery Query to add criteria to
     * @param Tx_Extbase_Persistence_Repository $extbaseRepository Associated repository
     */
    public static function translateCriteria(
           Tx_PtExtlist_Domain_QueryObject_Criteria $criteria,
           Tx_Extbase_Persistence_Query $extbaseQuery,
           Tx_Extbase_Persistence_Repository $extbaseRepository) {
	}

 }
 
 
 ?>