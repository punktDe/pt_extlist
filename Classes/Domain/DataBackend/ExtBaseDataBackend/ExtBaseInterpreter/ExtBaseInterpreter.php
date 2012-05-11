<?php
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
 * Query interpreter for ExtBase data backend. Translates queries into extbase query objects
 * 
 * TODO by calling static methods in Translator class, we have no loose coupling here. Change this by using instance of translator class.
 * 
 * @package Domain
 * @subpackage DataBackend\ExtBaseDataBackend\ExtBaseInterpreter
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
	public static function interpretQueryByRepository(Tx_PtExtlist_Domain_QueryObject_Query $query, Tx_Extbase_Persistence_Repository $repository) {
	       	
		$emptyExtbaseQuery = $repository->createQuery(); /* @var $emptyExtbaseQuery Tx_Extbase_Persistence_Query */
		
		$constrainedExtbaseQuery = self::setAllCriteriasOnExtBaseQueryByQueryObject($query, $emptyExtbaseQuery, $repository);
		$limitedExtbaseQuery = self::setLimitOnExtBaseQueryByQueryObject($query, $constrainedExtbaseQuery);
		$orderedExtbaseQuery = self::setSortingOnExtBaseQueryByQueryObject($query, $limitedExtbaseQuery);
		
		return $orderedExtbaseQuery;
	}
	
	
	
	/**
	 * Sets criterias from given query object on given extbase query object. Returns manipulated extbase query object
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 * @param Tx_Extbase_Persistence_Query $extbaseQuery
     * @param Tx_Extbase_Persistence_Repository $repository
	 * @return Tx_Extbase_Persistence_Query
	 */
	public static function setAllCriteriasOnExtBaseQueryByQueryObject(Tx_PtExtlist_Domain_QueryObject_Query $query, Tx_Extbase_Persistence_Query $extbaseQuery, Tx_Extbase_Persistence_Repository $repository) {

        foreach($query->getCriterias() as $criteria) { /* @var $criteria Tx_PtExtlist_Domain_QueryObject_SimpleCriteria */
            $extbaseQuery = self::setCriteriaOnExtBaseQueryByCriteria($criteria, $extbaseQuery, $repository);
        }

		return $extbaseQuery;
	}
	
	
	
	/**
	 * Translates given criteria and adds it to extbase query criterias 
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Criteria $criteria
	 * @param Tx_Extbase_Persistence_Query $extbaseQuery
	 * @param Tx_Extbase_Persistence_Repository $repository
	 * @return Tx_Extbase_Persistence_Query
	 */
	public static function setCriteriaOnExtBaseQueryByCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria, Tx_Extbase_Persistence_Query $extbaseQuery, Tx_Extbase_Persistence_Repository $repository) {
		$criteriaClass = get_class($criteria);
		switch ($criteriaClass) {
			case 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria':
			    $extbaseQuery = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
			    break;
			
			case 'Tx_PtExtlist_Domain_QueryObject_AndCriteria':
				$extbaseQuery = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_AndCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
			    break;
			
			case 'Tx_PtExtlist_Domain_QueryObject_OrCriteria':
			    $extbaseQuery = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_OrCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
                break;

            case 'Tx_PtExtlist_Domain_QueryObject_NotCriteria':
                $extbaseQuery = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_NotCriteriaTranslator::translateCriteria($criteria, $extbaseQuery, $repository);
                break;

			default:
				throw new Exception('Unkown criteria type ' . $criteriaClass . ' 1299224408');
			    break;
		}
		
        return $extbaseQuery;
	}
	
	
	
	/**
	 * Sets a limit on an extbase query by given query object. Returns manipulated extbase query.
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 * @param Tx_Extbase_Persistence_Query $extbaseQuery
	 * @return Tx_Extbase_Persistence_Query
	 */
	public static function setLimitOnExtBaseQueryByQueryObject(Tx_PtExtlist_Domain_QueryObject_Query $query, Tx_Extbase_Persistence_Query $extbaseQuery) {
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
	
	
	
	/**
	 * Sets sortings on an extbase query by given query object. Returns manipulated extbase query.
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query Query object to get sortings from
	 * @param Tx_Extbase_Persistence_Query $extbaseQuery Query object to set sortings on
	 * @return Tx_Extbase_Persistence_Query Manipulated ExtBase query object
	 */
	public static function setSortingOnExtBaseQueryByQueryObject(Tx_PtExtlist_Domain_QueryObject_Query $query, Tx_Extbase_Persistence_Query $extbaseQuery) {
        $sortings = $query->getSortings();
		$extBaseSortings = array();
		
		foreach ($sortings as $field => $direction) { /* sorting is array('field' => 'Direction: 1 | -1') */
			$extBaseDirection = $direction == Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC ? Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING : Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING;
		   $extBaseSortings[$field] = $extBaseDirection;
		}
		
		$extbaseQuery->setOrderings($extBaseSortings);
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
?>