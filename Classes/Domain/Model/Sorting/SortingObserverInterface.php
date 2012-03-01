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
 * Interface for objects that can be registered at sorter.
 * 
 * @author Michael Knoll
 * @package pt_extlist
 * @subpackage Domain\Model\Sorting
 */
interface Tx_PtExtlist_Domain_Model_Sorting_SortingObserverInterface {
	
	/**
	 * Registers a sorter which observes implementing object.
	 *
	 * @param Tx_PtExtlist_Domain_Model_Sorting_Sorter $sorter
	 */
	public function registerSorter(Tx_PtExtlist_Domain_Model_Sorting_Sorter $sorter);
	
	
	
	/**
	 * Returns sorting of implementing object.
	 * 
	 * @return Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection Collection of sorting states
	 */
	public function getSortingStateCollection();
	
	
	
	/**
	 * Resets sorting of implementing object.
	 *
	 * DOES NOT RESET TO DEFAULT SORTING!!!
	 */
	public function resetSorting();



	/**
	 * Resets sorting of implementing object to default sorting.
	 */
	public function resetToDefaultSorting();
	
}
?>