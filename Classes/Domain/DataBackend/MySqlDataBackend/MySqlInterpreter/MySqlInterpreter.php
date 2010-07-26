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
 * Interpreter for MySql queries
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter extends Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter {

	/**
	 * Holds class names for translating different types of criterias
	 *
	 * @var array
	 */
	protected $translatorClasses = array('Tx_PtExtlist_Domain_QueryObject_SimpleCriteria' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator',
	                                     'Tx_PtExtlist_Domain_QueryObject_NotCriteria'    => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_NotCriteriaTranslator',
	                                     'Tx_PtExtlist_Domain_QueryObject_OrCriteria'     => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_OrCriteriaTranslator',
	                                     'Tx_PtExtlist_Domain_QueryObject_AndCriteria'    => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_AndCriteriaTranslator'
	 );
	 
	 
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::getCriterias()
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 */
	public static function getCriterias(Tx_PtExtlist_Domain_QueryObject_Query $query) {
		$criteriaArray = array();
		foreach ($query->getCriterias() as $criteria) {
			$criteriaArray[] = self::translateCriteria($criteria);
		}
		$criteriaString = implode(' AND ', $criteriaArray);
		return $criteriaString;
	}

	
	
	protected static function translateCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria) {
		$criteriaString = '';
		$criteriaClass = get_class($criteria);
		if (array_key_exists($criteriaClass, $this->translatorClasses) && class_exists($this->translatorClasses[$criteriaClass])) {
		  $criteriaString = $this->translatorClasses[$criteriaClass]->translateCriteria($criteria);	
		} else {
		  throw new Exception('Unknown type of criteria ' . get_class($criteria));
		}
		return $criteriaString;
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::getLimit()
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 */
	public static function getLimit(Tx_PtExtlist_Domain_QueryObject_Query $query) {
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::getSorting()
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 */
	public static function getSorting(Tx_PtExtlist_Domain_QueryObject_Query $query) {
	}
	
	
	
//	public function interpretQuery(Tx_PtExtlist_Domain_QueryObject_Query $query) {
//	    $sqlString = '';
//		
//	    $sqlString .= $this->getSelectPart($query);
//	    $sqlString .= $this->getFromPart($query);
//	    $sqlString .= $this->getWherePart($query);
//	    $sqlString .= $this->getGroupByPart($query);
//	    $sqlString .= $this->getOrderByPart($query);
//	    $sqlString .= $this->getLimitPart($query);
//	    
//		return $sqlString;
//	}

	
	

	
}

?>