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
 * Abstract filter base class for all filters implementing single value filter
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
abstract class Tx_PtExtlist_Domain_Model_Filter_AbstractSingleValueFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractFilter {

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
     * Returns field description identifier on which this filter operates
     *
     * @return string Field description Identifier
     */
    public function getFieldIdentifier() {
        return $this->fieldIdentifier;
    }
    
    
    
    /**
     * Persists filter state to session
     *
     * @return array Array of filter data to persist to session
     */
    public function persistToSession() {
        return array('filterValue' => $this->filterValue, 'invert' => $this->invert);
    }
    
    
    
    /**
     * Resets filter to its default values
     * 
     * @return void
     */
    public function reset() {
        $this->filterValue = '';
        $this->resetSessionDataForFilter();
        $this->resetGpVarDataForFilter();
        $this->init();
    }
    
    
    
    /**
     * Resets session data for this filter
     */
    protected function resetSessionDataForFilter() {
        $this->sessionFilterData = array();
    }
    
    
    
    /**
     * Resets get/post var data for this filter
     */
    protected function resetGpVarDataForFilter() {
        $this->gpVarFilterData = array();
    }
    
    
    
    /**
     * Returns an error message for this filter
     *
     * @return string
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }
    
    

    /**
     * Template method for initializing filter by get / post vars
     */
    protected function initFilterByGpVars() {
        if(array_key_exists('filterValue', $this->gpVarFilterData)) {
            $this->filterValue = $this->gpVarFilterData['filterValue']; 
        }
    }
    
    

    /**
     * Template method for initializing filter by session data
     */
    protected function initFilterBySession() {
        $this->filterValue = array_key_exists('filterValue', $this->sessionFilterData) ? $this->sessionFilterData['filterValue'] : $this->filterValue;
    }
    
    
    
    /**
     * Template method for initializing filter by TS configuration
     */
    protected function initFilterByTsConfig() {
        $this->filterValue = $this->filterConfig->getDefaultValue() ? $this->filterConfig->getDefaultValue() : $this->filterValue;
    }
   
    
    
    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::setActiveState()
     */
    protected function setActiveState() {
    	$this->isActive = $this->filterValue != $this->filterConfig->getInactiveValue() ? true : false; 
    }
	
}