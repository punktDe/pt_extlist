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

class Tx_PtExtlist_Domain_Model_Sorting_SortingState {
	
	/**
	 * Returns an instance of this object for a given session persisted array
	 *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param array $sessionArray
     * @return Tx_PtExtlist_Domain_Model_Sorting_SortingState
	 */
	public static function getInstanceBySessionArray(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, array $sessionArray) {
		$field = $configurationBuilder->buildFieldsConfiguration()->getFieldConfigByIdentifier($sessionArray['fieldName']);
		$direction = $sessionArray['direction'];
		return new Tx_PtExtlist_Domain_Model_Sorting_SortingState($field, $direction);
	}



    /**
     * Returns an instance of this object for given field identifier and sorting direction
     *
     * TODO test me!
     *
     * @static
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
     * @param $fieldIdentifier Field identifier for field to be sorted
     * @param $sortingDirection Sorting direction by which to sort given field
     * @return Tx_PtExtlist_Domain_Model_Sorting_SortingState
     */
    public static function getInstanceByFieldIdentifierAndSortingDirection($configurationBuilder, $fieldIdentifier, $sortingDirection) {
        $field = $configurationBuilder->buildFieldsConfiguration()->getFieldConfigByIdentifier($fieldIdentifier);
        return new Tx_PtExtlist_Domain_Model_Sorting_SortingState($field, $sortingDirection);
    }
	
	
	
	/**
	 * Holds field for which this sorting state is set
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	protected $field;
	
	
	
	/**
	 * Holds direction of sorting state
	 *
	 * @var int
	 */
	protected $direction;
	
	
	
	/**
	 * Constructor for sorting state.
	 * 
	 * Takes a field and a direction out of which a sorting state should be generated.
	 * Sorting state must either be one of these:
	 * Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE
	 * Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC
	 * Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $field
	 * @param int $direction
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $field, $direction) {
        if (!($direction != Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE 
           || $direction != Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC
           || $direction != Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC
           )) {
        throw new Exception('Given sorting direction is not known! 1313625871');
        }
        $this->field = $field; 
        $this->direction = $direction;
	}
	
	
	
	/**
	 * Getter for field for this sorting state
	 *
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	public function getField() {
		return $this->field;
	}
	
	
	
	/**
	 * Getter for direction of this sorting state
	 *
	 * @return int
	 */
	public function getDirection() {
		return $this->direction;
	}



    /**
     * Returns query object that reflects sorting of this sorting state
     * 
     * @return Tx_PtExtlist_Domain_QueryObject_Query
     */
    public function getSortingQuery() {
        $sortingQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
        $sortingQuery->addSorting($this->field->getIdentifier(), $this->direction);
        return $sortingQuery;
    }
	
	
	
	/**
	 * Returns an array of this sorting state that can be persisted to session.
	 *
	 * Array has format array('fieldName' => fieldName, 'direction' => direction)
	 * 
	 * @return array
	 */
	public function getSessionPersistableArray() {
		return array('fieldName' => $this->field->getIdentifier(), 'direction' => $this->direction);
	}
	
}
?>