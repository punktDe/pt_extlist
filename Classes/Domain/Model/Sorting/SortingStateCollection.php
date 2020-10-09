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

use PunktDe\PtExtbase\Collection\ObjectCollection;
use PunktDe\PtExtbase\Exception\InternalException;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\QueryObject\Query;

/**
 * Class implements a collection of sorting states.
 *
 * @package pt_extlist
 * @subpackage Domain\Model\Sorting
 * @author Michael Knoll
 */
class SortingStateCollection extends ObjectCollection
{
    /**
     * Factory method to create a sorting state from a given session array
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @param array $sessionArray
     * @return SortingStateCollection
     * @throws InternalException
     * @throws \Exception
     */
    public static function getInstanceBySessionArray(ConfigurationBuilder $configurationBuilder, array $sessionArray)
    {
        $sortingStateCollection = new SortingStateCollection();
        foreach ($sessionArray as $sortingStateSessionArray) {
            $sortingStateCollection->addSortingState(SortingState::getInstanceBySessionArray($configurationBuilder, $sortingStateSessionArray));
        }
        return $sortingStateCollection;
    }
    
    

    /**
     * Holds class name of objects that this collection is restrictedd to
     *
     * @var string
     */
    protected $restrictedClassName = SortingState::class;


    /**
     * Adds a given field and direction to sorting state
     *
     * @param FieldConfig $field
     * @param integer $direction
     * @throws InternalException
     * @throws \Exception
     */
    public function addSortingByFieldAndDirection(FieldConfig $field, $direction)
    {
        $sortingState = new SortingState($field, $direction);
        $this->addItem($sortingState, $field->getIdentifier());
    }


    /**
     * Adds a sorting state to this collection
     *
     * @param SortingState $sortingState
     * @throws InternalException
     */
    public function addSortingState(SortingState $sortingState)
    {
        $this->addItem($sortingState, $sortingState->getField()->getIdentifier());
    }


    /**
     * @param $sortingStateIdentifier
     * @return SortingState
     * @throws InternalException
     */
    public function getSortingState($sortingStateIdentifier)
    {
        if ($this->hasItem($sortingStateIdentifier)) {
            return $this->getItemById($sortingStateIdentifier);
        }
        return null;
    }

    /**
     * Returns an array of field configs that are contained by this sorting state collection
     *
     * @return array<FieldConfig>
     */
    public function getSortedFields()
    {
        $sortedFields = [];
        foreach ($this->itemsArr as $sortingState) { /** @var $sortingState SortingState */
            $sortedFields[] = $sortingState->getField();
        }
        return $sortedFields;
    }


    /**
     * Returns a query object with sortings for this sorting state collection
     *
     * @return Query
     */
    public function getSortingsQuery()
    {
        $sortingsQuery = new Query();
        foreach ($this->itemsArr as $sortingState) {
            /* @var $sortingState SortingState */
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
            /* @var $sortingState SortingState */
            $sessionPersistableArray[] = $sortingState->getSessionPersistableArray();
        }
        return $sessionPersistableArray;
    }
}
