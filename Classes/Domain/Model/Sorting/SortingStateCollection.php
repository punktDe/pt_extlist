<?php


namespace PunktDe\PtExtlist\Domain\Model\Sorting;

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
class SortingStateCollection extends \PunktDe\PtExtbase\Collection\ObjectCollection
{
    /**
     * Factory method to create a sorting state from a given session array
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @param array $sessionArray
     * @return \PunktDe\PtExtlist\Domain\Model\Sorting\SortingStateCollection
     */
    public static function getInstanceBySessionArray(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder, array $sessionArray)
    {
        $sortingStateCollection = new \PunktDe\PtExtlist\Domain\Model\Sorting\SortingStateCollection();
        foreach ($sessionArray as $sortingStateSessionArray) {
            $sortingStateCollection->addSortingState(\PunktDe\PtExtlist\Domain\Model\Sorting\SortingState::getInstanceBySessionArray($configurationBuilder, $sortingStateSessionArray));
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
     * Adds a given field and direction to sorting state
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig $field
     * @param integer $direction
     */
    public function addSortingByFieldAndDirection(\PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig $field, $direction)
    {
        $sortingState = new \PunktDe\PtExtlist\Domain\Model\Sorting\SortingState($field, $direction);
        $this->addItem($sortingState, $field->getIdentifier());
    }


    /**
     * Adds a sorting state to this collection
     *
     * @param \PunktDe\PtExtlist\Domain\Model\Sorting\SortingState $sortingState
     */
    public function addSortingState(\PunktDe\PtExtlist\Domain\Model\Sorting\SortingState $sortingState)
    {
        $this->addItem($sortingState, $sortingState->getField()->getIdentifier());
    }


    /**
     * @param $sortingStateIdentifier
     * @return \PunktDe\PtExtlist\Domain\Model\Sorting\SortingState
     */
    public function getSortingState($sortingStateIdentifier)
    {
        if ($this->hasItem($sortingStateIdentifier)) {
            return $this->getItemById($sortingStateIdentifier);
        }
    }

    /**
     * Returns an array of field configs that are contained by this sorting state collection
     *
     * @return array<Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig>
     */
    public function getSortedFields()
    {
        $sortedFields = [];
        foreach ($this->itemsArr as $sortingState) {
            $sortedFields[] = $sortingState->getField();
        }
        return $sortedFields;
    }


    /**
     * Returns a query object with sortings for this sorting state collection
     *
     * @return \PunktDe\PtExtlist\Domain\QueryObject\Query
     */
    public function getSortingsQuery()
    {
        $sortingsQuery = new \PunktDe\PtExtlist\Domain\QueryObject\Query();
        foreach ($this->itemsArr as $sortingState) {
            /* @var $sortingState Tx_PtExtlist_Domain_Model_Sorting_SortingState */
            $sortingsQuery->addSorting($sortingState->getField()->getIdentifier(), $sortingState->getDirection());
        }
        return $sortingsQuery;
    }


    /**
     * Returns an array representing the sortings states of this collection
     * that can be stored in session.
     *
     * Array looks like array( array( 'fieldName' => fieldName, 'direction' => direction), ... )
     *
     * @return array
     */
    public function getSessionPersistableArray()
    {
        $sessionPersistableArray = [];
        foreach ($this->itemsArr as $sortingState) {
            /* @var $sortingState Tx_PtExtlist_Domain_Model_Sorting_SortingState */
            $sessionPersistableArray[] = $sortingState->getSessionPersistableArray();
        }
        return $sessionPersistableArray;
    }
}
