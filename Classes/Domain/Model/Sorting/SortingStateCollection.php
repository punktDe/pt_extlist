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
 * Class implements a collection of sorting states.
 *
 * @package pt_extlist
 * @subpackage Domain\Model\Sorting
 * @author Michael Knoll
 */
class Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection extends Tx_PtExtbase_Collection_ObjectCollection {
	
	/**
	 * Factory method to create a sorting state from a given session array
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param array $sessionArray
	 * @return Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection
	 */
	public static function getIstanceBySessionArray(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, array $sessionArray) {
		$sortingStateCollection = new Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection();
		foreach($sessionArray as $sortingStateSessionArray) {
			$sortingStateCollection->addSortingState(Tx_PtExtlist_Domain_Model_Sorting_SortingState::getInstanceBySessionArray($configurationBuilder, $sortingStateSessionArray));
		}
		return $sortingStateCollection;
	}
	
	

	/**
	 * Holds class name of objects that this collection is restrictedd to
	 *
	 * @var string
	 */
    protected $restrictedClassName = 'Tx_PtExtlist_Domain_Model_Sorting_SortingState';
	
    
	
	/**
	 * Holds an array of array(field => $fieldObject, 'sortingDirection' => $direction) mappings
	 *
	 * @var array
	 */
    protected $sortings = array();
    
    
    
    /**
     * Adds a given field and direction to sorting state
     *
     * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $field
     * @param int $direction
     */
    public function addSortingByFieldAndDirection(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $field, $direction) {
    	$sortingState = new Tx_PtExtlist_Domain_Model_Sorting_SortingState($field, $direction);
    	parent::addItem($sortingState, $sortingState->getField()->getIdentifier());
    }
    
    
    
    /**
     * Adds a sorting state to this collection
     *
     * @param Tx_PtExtlist_Domain_Model_Sorting_SortingState $sortingState
     */
    public function addSortingState(Tx_PtExtlist_Domain_Model_Sorting_SortingState $sortingState) {
    	parent::addItem($sortingState, $sortingState->getField()->getIdentifier());
    }
    
    
    
    /**
     * Returns an array of fields that are contained by this sorting state
     *
     * @return array<Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig>
     */
    public function getSortedFields() {
    	$sortedFields = array();
    	foreach ($this->sortings as $sorting) {
    		$sortedFields[] = $sorting;
    	}
    	return $sortedFields;
    }
    
    
    
    /**
     * Returns an array representing the sortings states of this collection
     * that can be stored in session.
     * 
     * Array looks like array( array( 'fieldName' => fieldName, 'direction' => direction), ... )
     * 
     * @return array
     */
    public function getSessionPersistableArray() {
    	$sessionPersistableArray = array();
    	foreach ($this->itemsArr as $sortingState) { /* @var $sortingState Tx_PtExtlist_Domain_Model_Sorting_SortingState */
    		$sessionPersistableArray[] = $sortingState->getSessionPersistableArray();
    	}
    	return $sessionPersistableArray;
    }

}
?>