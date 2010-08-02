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
     * Identifier of field on which this filter is operating (database field to be filtered)
     *
     * @var string
     */
    protected $fieldDescriptionIdentifier;
	
		
	
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
    public function getFieldDescriptionIdentifier() {
        return $this->fieldDescriptionIdentifier;
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
    	// TODO think about what happens if filter is reseted!
    	$settings = $this->filterConfig->getSettings();
    	$this->filterValue = array_key_exists('filterDefaultValue', $settings) ? $settings['filterDefaultValue'] : $this->filterValue;
    	if (!array_key_exists('fieldDescriptionIdentifier', $settings) || $settings['fieldDescriptionIdentifier'] == '') {
    		throw new Exception('No fieldDescriptionIdentifier set in TS config for filter ' . $this->getFilterIdentifier() . ' 1280762513');
    	}
    	$this->fieldDescriptionIdentifier = $settings['fieldDescriptionIdentifier'];
    }
    
    

    /**
     * Template method for initializing filter by session data
     */
    protected function initFilterBySession() {
    	// TODO think about what happens if filter is reseted!
    	$this->filterValue = array_key_exists('filterValue', $this->sessionFilterData) ? $this->sessionFilterData['filterValue'] : $this->filterValue;
    }
    
    

    /**
     * Template method for initializing filter by get / post vars
     */
    protected function iniFilterByGpVars() {
    	// TODO think about what happens if filter is resetted!
    	$this->filterValue = array_key_exists('filterValue', $this->gpVarFilterData) ? $this->gpVarFilterData['filterValue'] : $this->filterValue;
    }
    
    
    
    /**
     * Creates filter query from filter value and settings
     */
    protected function createFilterQuery() {
    	$fieldDescriptionIdentifier = $this->dataBackend->getFieldConfigurationCollection()->getFieldConfigByIdentifier($this->fieldDescriptionIdentifier);
    	$columnName = $fieldDescriptionIdentifier->getTable() . '.' . $fieldDescriptionIdentifier->getField();
    	$filterQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$criteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria($columnName, $this->filterValue, 'LIKE');
    	$filterQuery->addCriteria($criteria);
    	$this->filterQuery = $filterQuery;
    }
    
	
}
 
 ?>