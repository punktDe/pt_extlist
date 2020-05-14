<?php


namespace PunktDe\PtExtlist\Domain\Model\Sorting;

use PunktDe\PtExtlist\Domain\Configuration\Sorting\SorterConfig;

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
 * Class implements sorter for handling all kinds of sorting.
 *  
 * Object that influence sorting can register here and get sorting
 * states and reset commands if necessary.
 *
 * @package pt_extlist
 * @subpackage Domain\Model\Sorting
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Model_Sorting_SorterTest
 */
class Sorter
{
    /**
     * Array that holds sorters to be observerd by sorter
     *
     * @var array<\PunktDe\PtExtlist\Domain\Model\Sorting\SortingObserverInterface>
     */
    protected $sortingObservers;
    
    
    
    /**
     * Holds sorter configuration
     *
     * @var SorterConfig
     */
    protected $sorterConfiguration;
    
    
    
    /**
     * Holds a collection of sorting states that are used for sorting
     *
     * @var SortingStateCollection
     */
    protected $sortingStateCollection;
    
    
    
    /**
     * Registers sorter that can influence sorting.
     *
     * @param SortingObserverInterface $sortingObserver
     */
    public function registerSortingObserver(SortingObserverInterface $sortingObserver)
    {
        $this->sortingObservers[] = $sortingObserver;
        $sortingObserver->registerSorter($this);
    }



    /**
     * Removes all registered sorting observers and resets sorting state collection
     */
    public function removeAllSortingObservers()
    {
        $this->sortingObservers = [];
        $this->buildSortingStateCollection();
    }



    /**
     * Injector for sorter configuration
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Sorting\SorterConfig $sorterConfiguration
     */
    public function _injectSorterConfig(\PunktDe\PtExtlist\Domain\Configuration\Sorting\SorterConfig $sorterConfiguration)
    {
        $this->sorterConfiguration = $sorterConfiguration;
    }


    /**
     * Returns current sorting state collection of this sorter
     *
     * @return \PunktDe\PtExtlist\Domain\Model\Sorting\SortingStateCollection
     */
    public function getSortingStateCollection()
    {
        if ($this->sortingStateCollection == null) {
            $this->buildSortingStateCollection();
        }
        return $this->sortingStateCollection;
    }



    /**
     * Resets sorter
     *
     * @return void
     */
    public function reset()
    {
        foreach ($this->sortingObservers as $sortingObserver) { /* @var $sortingObserver SortingObserverInterface */
            $sortingObserver->resetSorting();
        }
    }



    /**
     * Resets all sorting observers to default sorting
     */
    public function resetToDefault()
    {
        foreach ($this->sortingObservers as $sortingObserver) { /* @var $sortingObserver SortingObserverInterface */
            $sortingObserver->resetToDefaultSorting();
        }
    }



    /**
     * Builds sorting state collection by respecting the registered sorting observers
     * and getting their sorting informations.
     *
     * @return void
     */
    protected function buildSortingStateCollection()
    {
        $this->sortingStateCollection = new \PunktDe\PtExtlist\Domain\Model\Sorting\SortingStateCollection();

        // Gather sorting states from registered sorting observers
        if (is_array($this->sortingObservers)) {
            foreach ($this->sortingObservers as $sortingObserver) { /* @var $sortingObserver SortingObserverInterface */
                $sortingStateCollectionFromObserver = $sortingObserver->getSortingStateCollection();
                foreach ($sortingStateCollectionFromObserver as $sortingStateFromSortingObserver) {
                    $this->sortingStateCollection->addSortingState($sortingStateFromSortingObserver);
                }
            }
        }
    }
}
