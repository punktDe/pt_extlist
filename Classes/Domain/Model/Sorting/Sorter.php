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
 * Class implements sorter for handling all kinds of sorting.
 * 
 * Object that influence sorting can register here and get sorting
 * states and reset commands if necessary.
 *
 * @package pt_extlist
 * @subpackage Domain\Model\Sorting
 * @author Michael Knoll
 */
class Tx_PtExtlist_Domain_Model_Sorting_Sorter {

	/**
	 * Array that holds sorters to be observerd by sorter
	 *
	 * @var array<Tx_PtExtlist_Domain_Model_Sorting_SortingObserverInterface>
	 */
	protected $sortingObservers;
	
	
	
	/**
	 * Holds sorter configuration
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Sorting_SorterConfig
	 */
	protected $sorterConfiguration;
	
	
	
	/**
	 * Holds a collection of sorting states that are used for sorting
	 *
	 * @var Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection
	 */
	protected $sortingStateCollection;
	
	
	
	/**
	 * Registers sorter that can influence sorting.
	 *
	 * @param Tx_PtExtlist_Domain_Model_Sorting_SortingObserverInterface $sortingObserver
	 */
    public function registerSortingObserver(Tx_PtExtlist_Domain_Model_Sorting_SortingObserverInterface $sortingObserver) {
    	$this->sortingObservers[] = $sortingObserver;
    	$sortingObserver->registerSorter($this);
    }
    
    
    
    /**
     * Injector for sorter configuration
     *
     * @param Tx_PtExtlist_Domain_Configuration_Sorting_SorterConfig $sortingConfiguration
     */
    public function injectSorterConfig(Tx_PtExtlist_Domain_Configuration_Sorting_SorterConfig $sorterConfiguration) {
    	$this->sorterConfiguration = $sorterConfiguration;
    }



    /**
     * Returns current sorting state collection of this sorter
     *
     * @return Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection
     */
    public function getSortingStateCollection() {
        $this->buildSortingStateCollection();
        return $this->sortingStateCollection;
    }



    /**
     * Resets sorter
     *
     * @return void
     */
    public function reset() {
        foreach($this->sortingObservers as $sortingObserver) { /* @var $sortingObserver Tx_PtExtlist_Domain_Model_Sorting_SortingObserverInterface */
            $sortingObserver->resetSorting();
        }
    }



	/**
	 * Resets all sorting observers to default sorting
	 */
	public function resetToDefault() {
		foreach($this->sortingObservers as $sortingObserver) { /* @var $sortingObserver Tx_PtExtlist_Domain_Model_Sorting_SortingObserverInterface */
			$sortingObserver->resetToDefaultSorting();
		}
	}



    /**
     * Builds sorting state collection by respecting the registered sorting observers
     * and getting their sorting informations.
     *
     * @return void
     */
    protected function buildSortingStateCollection() {
        $this->sortingStateCollection = new Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection();

        // Gather sorting states from registered sorting observers
		if (is_array($this->sortingObservers)) {
			foreach ($this->sortingObservers as $sortingObserver) { /* @var $sortingObserver Tx_PtExtlist_Domain_Model_Sorting_SortingObserverInterface */
				$sortingStateCollectionFromObserver = $sortingObserver->getSortingStateCollection();
				foreach($sortingStateCollectionFromObserver as $sortingStateFromSortingObserver) {
					$this->sortingStateCollection->addSortingState($sortingStateFromSortingObserver);
				}
			}
		}
    }

}
?>