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
	 * Returns raw value of filter (NOT FILTER QUERY!!!)
	 *
	 * @return string
	 */
	public function getFilterValue() {
		return $this->filterValue;
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
     * Template method for initializing filter by TS configuration
     */
    protected function initFilterByTsConfig() {
    	// TODO implement me!
    }
    
    

    /**
     * Template method for initializing filter by session data
     */
    protected function initFilterBySession() {
    	// TODO implement me!
    }
    
    

    /**
     * Template method for initializing filter by get / post vars
     */
    protected function iniFilterByGpVars() {
    	// TODO implement me!
    }
    
    
    
    protected function createFilterQuery() {
    	// TODO implement me!
    }
    
	
}
 
 ?>