<?php
namespace PunktDe\PtExtlist\Domain\Model\Lists\Header;

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


use PunktDe\PtExtbase\State\GpVars\GpVarsInjectableInterface;
use PunktDe\PtExtbase\State\Session\SessionPersistableInterface;
use PunktDe\PtExtlist\Domain\Configuration\Columns\ColumnConfig;
use PunktDe\PtExtlist\Domain\Configuration\Columns\SortingConfig;
use PunktDe\PtExtlist\Domain\Configuration\Columns\SortingConfigCollection;
use PunktDe\PtExtlist\Domain\Model\Sorting\Sorter;
use PunktDe\PtExtlist\Domain\Model\Sorting\SortingObserverInterface;
use PunktDe\PtExtlist\Domain\Model\Sorting\SortingState;
use PunktDe\PtExtlist\Domain\Model\Sorting\SortingStateCollection;
use PunktDe\PtExtlist\Domain\QueryObject\Query;


/**
 * Class implements a single column
 *
 * HeaderColumn is not the right name any more - this class represents the column and its properties
 * TODO: Rename this class to Columns_Column
 *
 * @author Daniel Lienert
 * @author Michael Knoll
 * @package Domain
 * @subpackage Model\Lists\Header
 * @see Tx_PtExtlist_Tests_Domain_Model_List_Header_HeaderColumnTest
 */
class HeaderColumn
    implements GpVarsInjectableInterface,
    SessionPersistableInterface,
    SortingObserverInterface
{
    /**
     * @var ColumnConfig
     */
    protected $columnConfig;



    /**
     *
     * @var array GP-Var Data
     */
    protected $headerGPVarData = [];



    /**
     * Holds session data array
     *
     * @var array
     */
    protected $headerSessionData = [];



    /**
     * @var string
     */
    protected $listIdentifier;



    /**
     * @var string
     */
    protected $columnIdentifier;



    /**
     * @var boolean
     */
    protected $isVisible = true;



    /**
     * @var boolean
     */
    protected $isSortable;



    /**
     * @var SortingConfigCollection
     */
    protected $sortingFieldConfig;



    /**
     * Holds an array of fieldIdentifiers and sortingStates
     *
     * array('fieldIdentifier' => sortingState, ...)
     *
     * @var array
     */
    protected $sortedFields = [];



    /**
     * Array with the actual sorting state
     *
     * @var SortingStateCollection
     */
    protected $sortingStateCollection;



    /**
     * Holds instance of sorter
     *
     * @var Sorter
     */
    protected $sorter;



    /**
     * Det the Columnheader Configuration
     *
     * @param ColumnConfig $columnConfig
     */
    public function injectColumnConfig(ColumnConfig $columnConfig)
    {
        $this->columnConfig = $columnConfig;
        $this->listIdentifier = $this->columnConfig->getListIdentifier();
        $this->columnIdentifier = $this->columnConfig->getColumnIdentifier();
    }



    /**
     * Init the header column:
     *
     * 1. Set state from session
     * 2. Overwrite state from GPVars
     * 3. Build the sorting state collection for this header
     */
    public function init()
    {
        $this->initByTsConfig();
        $this->initBySession();
        $this->initByGpVars();
        $this->buildSortingStateCollection();
    }



    /**
     * Sets the sorting direction of this column.
     *
     * Which fields are sorted
     *
     * @param integer $sortingDirection One of  Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC | SORTINGSTATE_DESC | SORTINGSTATE_NONE
     */
    public function setSorting($sortingDirection = Query::SORTINGSTATE_ASC)
    {
        $this->sortedFields = [];
        $sortingConfig = $this->columnConfig->getSortingConfig();
        foreach ($sortingConfig as $fieldConfig) {
            /* @var SortingConfig $fieldConfig */
            if ($fieldConfig->getForceDirection()) {
                $this->sortedFields[$fieldConfig->getField()] = $fieldConfig->getDirection();
            } else {
                $this->sortedFields[$fieldConfig->getField()] = $sortingDirection;
            }
        }
    }



    /**
     * Initialize the Column by Configuration
     */
    protected function initByTsConfig()
    {
        $this->sortingFieldConfig = $this->columnConfig->getSortingConfig();
        $this->isVisible = $this->columnConfig->getIsVisible();
    }



    /**
     * Template method for initializing filter by session data
     */
    protected function initBySession()
    {
        if (array_key_exists('sortedFields', $this->headerSessionData)) {
            $this->sortedFields = $this->headerSessionData['sortedFields'];
        }
    }



    /**
     * Template method for initializing filter by get / post vars
     */
    protected function initByGpVars()
    {
        if (array_key_exists('sortingFields', $this->headerGPVarData)) {
            $this->initByGpVarsSortingFields($this->headerGPVarData['sortingFields']);
        }
    }



    /**
     * Build sorting state collection for this column
     *
     * @return void
     */
    protected function buildSortingStateCollection()
    {
        $this->sortingStateCollection = new SortingStateCollection();
        if (count($this->sortedFields) > 0) {
            foreach ($this->sortedFields as $fieldIdentifier => $sortingDirection) {
                if ($this->sortingFieldConfig->hasItem($fieldIdentifier)) {
                    $fieldConfig = $this->sortingFieldConfig->getItemById($fieldIdentifier);
                    if ($fieldConfig->getForceDirection()) {
                        $sortingState = SortingState::getInstanceByFieldIdentifierAndSortingDirection($this->columnConfig->getConfigurationBuilder(), $fieldConfig->getField(), $fieldConfig->getDirection());
                    } else {
                        $sortingState = SortingState::getInstanceByFieldIdentifierAndSortingDirection($this->columnConfig->getConfigurationBuilder(), $fieldConfig->getField(), $sortingDirection);
                    }
                    $this->sortingStateCollection->addSortingState($sortingState);
                }
            }
        }
    }



    /**
     * Sets sorting state of header by given sortingFields GP-var string.
     * String has following format: <fieldIdentifier1>:<sortingDirection1>;<fieldIdentifier2>:<sortingDirection2>
     *
     * @param string $sortingFields
     */
    protected function initByGpVarsSortingFields($sortingFields)
    {
        $this->sortedFields = [];
        $fieldsAndDirections = explode(';', $sortingFields);
        foreach ($fieldsAndDirections as $fieldAndSortingDirection) {
            list($fieldIdentifier, $sortingDirection) = explode(':', $fieldAndSortingDirection);
            if (in_array($sortingDirection, [Query::SORTINGSTATE_ASC, Query::SORTINGSTATE_DESC])
                && $this->sortingFieldConfig->hasItem($fieldIdentifier)
            ) {
                $this->sortedFields[$fieldIdentifier] = $sortingDirection;
            }
        }
    }



    /**
     * @return string column label
     */
    public function getLabel()
    {
        return $this->columnConfig->getLabel();
    }



    /**
     * @return string column identifier
     */
    public function getColumnIdentifier()
    {
        return $this->columnIdentifier;
    }



    /**
     * Return an array with sorting definitions for this column
     *
     * @return SortingStateCollection
     */
    public function getSortingStateCollection()
    {
        $this->buildSortingStateCollection();
        return $this->sortingStateCollection;
    }



    /**
     * Returns sorting direction for given field identifier
     *
     * @param string $fieldIdentifier Field identifier to get current sorting for
     * @return integer Sorting direction
     */
    public function getSortingDirectionForField($fieldIdentifier)
    {
        if (array_key_exists($fieldIdentifier, $this->sortedFields)) {
            return $this->sortedFields[$fieldIdentifier];
        }
        return 0;
    }



    /**
     * Return the column configuration.
     *
     * @return ColumnConfig
     */
    public function getColumnConfig()
    {
        return $this->columnConfig;
    }



    /**
     * Returns sorting config of attached column config
     *
     * @return SortingConfigCollection
     */
    public function getSortingConfig()
    {
        return $this->getColumnConfig()->getSortingConfig();
    }



    /**
     * Returns if the column is sortable.
     *
     * @return boolean True if sortable.
     */
    public function isSortable()
    {
        return $this->columnConfig->getIsSortable();
    }



    /**
     * Reset session state
     *
     * What happens here:
     *
     * Whenever we get a new sorting, we have to reset all previous sortings (empty session) except the column
     * that gets sorting parameters from GP vars. So here we reset sorting from old state to current state given
     * in GP vars.
     *
     * WE DO NOT RESET TO DEFAULT STATE HERE!!! @see resetToDefaultSorting() below!
     */
    public function reset()
    {
        $this->resetSorting();
        $this->headerSessionData = [];
        // we must not reset header GP data!
        $this->init();
    }



    /**
     * Resets column sorting to default sorting
     */
    public function resetToDefaultSorting()
    {
        $this->reset();
        $defaultSortingConfig = $this->columnConfig->getConfigurationBuilder()->buildListDefaultConfig();
        if ($defaultSortingConfig->getSortingColumn() === $this->getColumnIdentifier()) {
            $this->setDefaultSorting($defaultSortingConfig->getSortingDirection());
        }
    }



    /**
     * Returns namespace for this object
     *
     * @return string Namespace to identify this object
     */
    public function getObjectNamespace()
    {
        return $this->listIdentifier . '.headerColumns.' . $this->columnIdentifier;
    }



    /**
     * Returns list identifier of associated list
     *
     * @return string
     */
    public function getListIdentifier()
    {
        return $this->listIdentifier;
    }



    /**
     * @param $GPVars
     */
    public function _injectGPVars($GPVars)
    {
        $this->headerGPVarData = $GPVars;
    }



    /**
     * Registers a sorter which observes implementing object.
     *
     * @param Sorter $sorter
     */
    public function registerSorter(Sorter $sorter)
    {
        $this->sorter = $sorter;
    }



    /**
     * Resets sorting of implementing object.
     */
    public function resetSorting()
    {
        $this->sortedFields = [];
        unset($this->headerSessionData['sortedFields']);
        $this->init();
    }



    /**
     * Returns true, if sorting for this header is active
     *
     * TODO test me!
     *
     * @return bool
     */
    public function isSortingActive()
    {
        if (count($this->sortedFields) > 0) {
            return true;
        }
        return false;
    }



    /**
     * Sets sorting of this header according to default sorting.
     *
     * We therefore take sorting field configuration of this column
     * and set sorting of each field according to given direction, as
     * long as there is no forced direction for field.
     *
     * @param integer $sortingDirection
     * @return void
     */
    public function setDefaultSorting($sortingDirection)
    {
        foreach ($this->sortingFieldConfig as $sortingField) {
            /* @var $sortingField SortingConfig */
            $this->sortedFields[$sortingField->getField()] = $sortingField->getForceDirection() ? $sortingField->getDirection() : $sortingDirection;
        }
    }



    /**
     * Called by any mechanism to inject an object's state from session
     *
     * @param array $sessionData Object's state to be persisted to session
     */
    public function _injectSessionData(array $sessionData)
    {
        $this->headerSessionData = $sessionData;
    }



    /**
     * Called by any mechanism to persist an object's state to session
     *
     * @return array|null
     */
    public function _persistToSession()
    {
        $sessionArray = null;
        if (count($this->sortedFields) > 0) {
            $sessionArray['sortedFields'] = $this->sortedFields;
        }
        return $sessionArray;
    }



    /**
     * @param boolean $isVisible
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;
    }



    /**
     * @return boolean
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }
}
