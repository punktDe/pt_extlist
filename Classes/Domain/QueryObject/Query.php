<?php
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
 * Class implements a query
 *
 * @package Domain
 * @subpackage QueryObject
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_QueryObject_Query {
 	
	/**
	 * Holds array of field names to do query upon.
	 * Can be encoded as <table>.<field> or as <field>
	 *
	 * @var array
	 */
	protected $fields = array();
	
	
	
	/**
	 * Holds an array of table names to query from
	 *
	 * @var array
	 */
	protected $from = array();
	
	
	
	/**
	 * 
	 * Holds an array of fields to group by
	 * @var array
	 */
	protected $groupByFields = array();
	
	
	
	/**
	 * Holds limit of query, encoded as <offset>:<count>
	 *
	 * @var string
	 */
	protected $limit;
	
	
	
	/**
	 * Holds array of criterias to restrict selection
	 *
	 * @var array<Tx_PtExtlist_Domain_QueryObject_Criteria>
	 */
	protected $criterias = array();
	
	
	
	/**
	 * Holds array of sorting for query.
	 * Encoded as <field_name> [ASCENDING|DESCENDING]
	 *
	 * @var array
	 */
	protected $sortings = array();
	
	
	
	/**
     * @var integer
     */
	const SORTINGSTATE_NONE = 0;

	
	
	/**
     * @var integer
     */
	const SORTINGSTATE_ASC = 1;

	
	
	/**
     * @var integer
     */
	const SORTINGSTATE_DESC = -1;
	
	
	
	/**
	 * Adds a field name to list of fields
	 *
	 * @param string $field Field name to be added to list of fields
	 */
	public function addField($field) {
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($field, array('message' => 'Field must not be empty! 1279988488'));
		$this->fields[] = $field;
	}
	
	
	
	/**
	 * Returns list of field names query works upon
	 *
	 * @return array
	 */
	public function getFields() {
		return $this->fields;
	}
	
	
	
	/**
	 * Adds a new entry to from part of query
	 *
	 * @param string $from From part to be added to query
	 */
	public function addFrom($from) {
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($from, array('message' => 'From must not be empty! 1279988763'));
		$this->from[] = $from;
	}
	
	
	
	/**
	 * Returns all from parts from query
	 *
	 * @return array
	 */
	public function getFrom() {
		return $this->from;
	}
	
	
	
	/**
	 * Adds a criteria to query
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Criteria $criteria Criteria to be added to query
	 */
	public function addCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria) {
		$this->criterias[] = $criteria;
	}
	
	
	
	/**
	 * Add group by clause
	 * 
	 * @param string $groupBy
	 */
	public function addGroupBy($groupBy) {
		$this->groupByFields[] = $groupBy;
	}
	
	
	
	/**
	 * Returns all criterias from this query
	 *
	 * @return array<Tx_PtExtlist_Domain_QueryObject_Criteria>
	 */
	public function getCriterias() {
		return $this->criterias;
	}
	


	/**
	 * Unset the current filterCriterias
	 * @return void
	 */
	public function unsetCriterias() {
		unset($this->criterias);
	}


	
	/**
	 * Sets limit. Possible formats are 'd' or 'd:d'
	 *
	 * @param string $limit Limit for query
	 */
	public function setLimit($limit) {
		if ($limit == '') {
			$this->limit = '';
		} elseif (preg_match('/^[0-9]+$/', $limit)) {
			$this->limit = $limit;
		} elseif (preg_match('/^[0-9]+:[0-9]+$/', $limit)) {
			$this->limit = $limit;
		}
		else {
		    throw new Exception('Format of limit ' . $limit . ' does not fit (d) or (d:d)! 1281672837');
		}
	}
	
	
	
	/**
	 * Getter for group by fields
	 *
	 * @return array
	 */
	public function getGroupBy() {
		return $this->groupByFields;
	}
	
	
	
	/**
	 * Returns limit of query
	 *
	 * @return string
	 */
	public function getLimit() {
		return $this->limit;
	}
	
	
	
	/**
	 * Adds a sorting for a given field and a given direction to array of sortings.
	 * Direction may be either self::SORTINGSTATE_DESC oder self::SORTINGSTATE_ASC
	 *
	 * @param string $field Field to be sorted
	 * @param integer $direction Direction of sorting
	 */
	public function addSorting($field, $direction = self::SORTINGSTATE_ASC) {
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($field, array('message' => 'field must not be empty! 1280060692'));
		
		if ($direction == self::SORTINGSTATE_ASC || $direction == self::SORTINGSTATE_DESC) {
		    $this->sortings[$field] = $direction;	
		} else {
			throw new Exception('Given direction must be either SORTINGSTATE_ASC(1) or SORTINGSTATE_DESC(-1), but was ' . $direction);
		}
		
	}
	
	
	
	/**
	 * Add an array of fields and sorting direction to the array of sortings
	 * 
	 * @param $sortingArray array
	 */
	public function addSortingArray(array $sortingArray) {
		// TODO assert that content of array is correct! 
		$this->sortings =  t3lib_div::array_merge_recursive_overrule($this->sortings, $sortingArray);
	}
	
	
	
	/**
	 * Returns array of sortings
	 *
	 * @return array
	 */
	public function getSortings() {
		return $this->sortings;
	}



    /**
	 * Inverts given sorting state.
     *
     * ASC will become DESC
     * DESC will become ASC
     * everything else will become ASC
	 *
	 * @param int $sortingState
	 * @return int The inverted sorting state.
	 */
	public static function invertSortingState($sortingState) {
		switch($sortingState) {
			case self::SORTINGSTATE_ASC:
				return self::SORTINGSTATE_DESC;
			case self::SORTINGSTATE_DESC:
				return self::SORTINGSTATE_ASC;
			default:
                // TODO think about this behaviour!
				return self::SORTINGSTATE_ASC;
		}
	}
	
}
?>