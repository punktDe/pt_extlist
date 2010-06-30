<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * Class implements a string filter
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Model_Filter_StringFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractFilter {

	/**
	 * Holds the current filter value
	 *
	 * @var string
	 */
	protected $filterValue = '';
	
	
	
	/**
	 * Initializes filter
	 * 
	 * @return void
	 */
	public function init() {
		// TODO ry21 gather data from GP vars
	}
	
	
	
	/**
	 * Persists filter state to session
	 *
	 * @return array Array of filter data to persist to session
	 */
	public function persistToSession() {
		return array('filterValue' => $this->filterValue);
	}
	
	
	
	/**
	 * Restores filter state from session
	 *
	 * @param array $sessionData Session data to restore filter from
	 */
	public function loadFromSession(array $sessionData) {
		if (array_key_exists('filterValue',$sessionData)) {
	       $this->filterValue = $sessionData['filterValue'];
		}	
	}
 	
}
 
 ?>