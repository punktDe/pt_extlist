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
 * Query interpreter for ExtBase data backend. Translates queries into extbase query objects
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter extends Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter  {

	protected static $translatorClasses = array('Tx_PtExtlist_Domain_QueryObject_SimpleCriteria' => 'Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator',
                                                'Tx_PtExtlist_Domain_QueryObject_NotCriteria'    => 'Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_NotCriteriaTranslator',
                                                'Tx_PtExtlist_Domain_QueryObject_OrCriteria'     => 'Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_OrCriteriaTranslator',
                                                'Tx_PtExtlist_Domain_QueryObject_AndCriteria'    => 'Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_AndCriteriaTranslator',
                                          ); 
	
	
    /**
	 * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::getCriterias()
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 */
	public static function getCriterias(Tx_PtExtlist_Domain_QueryObject_Query $query) {
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
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::interpretQuery()
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 */
	static function interpretQuery(Tx_PtExtlist_Domain_QueryObject_Query $query) {
		
	}
	
	
	
	/**
	 * Translates a query into an extbase query
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 * @param Tx_Extbase_Persistence_Repository $repository
	 * @return Tx_Extbase_Persistence_Query
	 */
	static function interpretQueryByRepository(
	       Tx_PtExtlist_Domain_QueryObject_Query $query, 
	       Tx_Extbase_Persistence_Repository $repository) {
		$extbaseQuery = $repository->createQuery();
		
		// translate criterias
		foreach($query->getCriterias() as $criteria) { /* @var $criteria Tx_PtExtlist_Domain_QueryObject_SimpleCriteria */
			if ($criteria->getOperator() == 'LIKE') {
				// TODO fix this!
				list ($foo, $field) = explode('.', $criteria->getField());
				$extbaseQuery->matching($extbaseQuery->like($field, $criteria->getValue()));
			}
		}
		
		
		// translate limit
		if ($query->getLimit() != '') {
			list($offset, $limit) = explode(':', $query->getLimit());
			if (!$limit > 0) {
				$extbaseQuery->setLimit(intval($offset)); // no offset set, so offset = limit
			} else {
			    $extbaseQuery->setOffset(intval($offset));
			    $extbaseQuery->setLimit(intval($limit));
			}
		}
		
		return $extbaseQuery;
	}
	
	
	
	public static function translateCriteria() {
		
	}
	
	
	
	/**
	 * Translates group by part of query
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 */
	static public function getGroupBy(Tx_PtExtlist_Domain_QueryObject_Query $query) {
	}

}