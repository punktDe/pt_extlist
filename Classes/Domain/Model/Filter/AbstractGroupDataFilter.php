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
 * Class implements an abstract filter for all filters using group data
 * 
 * @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
abstract class Tx_PtExtlist_Domain_Model_Filter_AbstractGroupDataFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractFilter {

	
	/**
	 * Array of filters to be excluded if options for this filter are determined
	 *
	 * @var string
	 */
	protected $excludeFilters;
	
	
	/**
     * Holds an array of filter values
     *
     * @var array
     */
	protected $filterValues = array();
	
	
	/**
	 * Show the group row count
	 * 
	 * @var integer
	 */
	protected $showRowCount;
	
	
	/**
	 * Holds identifier of field that should be filtered
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	protected $fieldIdentifier;
	
	   
	
	/**
	 * Holds the filter value to be used when filter is submitted
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	protected $filterField;
	
	
	
	/**
	 * Holds an array of fields to be used as displayed values for the filter (the options that can be selected)
	 *
	 * @var array Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	protected $displayFields = array();
		
	
	
	/**
	 * Holds a comma seperated list of additional tables that are required for this filter
	 *
	 * @var string
	 */
	protected $additionalTables;
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_FilterInterface::reset()
	 *
	 */
	public function reset() {
		$this->filterValues = array();
		$this->sessionFilterData = array();
		$this->init();
	}
	
	
	/**
	 * @see Tx_PtExtlist_Domain_StateAdapter_SessionPersistableInterface::persistToSession()
	 *
	 */
	public function persistToSession() {
		return array('filterValues' => $this->filterValues, 'invert' => $this->invert);
	}
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilter()
	 *
	 */
	protected function initFilter() {}


	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::createFilterQuery()
	 *
	 */
	protected function createFilterQuery() {

		$columnName = $this->fieldIdentifier->getTableFieldCombined();
		$criteria = NULL;
		
		if (!is_array($this->filterValues) && $this->filterValues != '') {
			$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::equals($columnName, $this->filterValues);
		} elseif (is_array($this->filterValues) && count($this->filterValues) > 1) {
			$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::in($columnName, $this->filterValues);
		}

		
		if($criteria) {
			if($this->invert) {
				$this->filterQuery->addCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria::notOp($criteria));
			} else {
				$this->filterQuery->addCriteria($criteria);
			}
		} else {
			$this->filterQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
		}
		
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilterByGpVars()
	 *
	 */
	protected function initFilterByGpVars() {
		if (array_key_exists('filterValues', $this->gpVarFilterData)) {
			$this->filterValues = $this->gpVarFilterData['filterValues'];
		}
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilterBySession()
	 *
	 */
	protected function initFilterBySession() {
		if (array_key_exists('filterValues', $this->sessionFilterData)) {
			$this->filterValues = $this->sessionFilterData['filterValues'];
		}
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilterByTsConfig()
	 *
	 */
	protected function initFilterByTsConfig() {
		$filterSettings = $this->filterConfig->getSettings();
        
        $this->filterField = trim($filterSettings['filterField']) ? $this->resolveFieldConfig(trim($filterSettings['filterField'])) : $this->fieldIdentifier;
        
        $this->setDisplayFieldsByTSConfig(trim($filterSettings['displayFields']));
               
        if (array_key_exists('excludeFilters', $filterSettings)) {
        	$this->excludeFilters = $filterSettings['excludeFilters'];
        }
        
        if (array_key_exists('additionalTables', $filterSettings)) {
        	$this->additionalTables = $filterSettings['additionalTables'];
        }
        
		if (array_key_exists('multiple', $filterSettings) && $filterSettings['multiple']) {
        	$this->multiple = $filterSettings['multiple'];
        }
        
		if (array_key_exists('showRowCount', $filterSettings) && $filterSettings['showRowCount']) {
        	$this->showRowCount = $filterSettings['showRowCount'];
        }
	}

	
	
	/**
	 * Set DisplayFields by TS-Settings
	 * 
	 * @param array $displayFieldSettings
	 */
	protected function setDisplayFieldsByTSConfig($displayFieldSettings) {
		
	 	if($displayFieldSettings) {
        	$displayFields = t3lib_div::trimExplode(',', $displayFieldSettings);
        	foreach($displayFields as $displayField) {
        		$fieldConfig = $this->resolveFieldConfig($displayField);
        		$this->displayFields[$fieldConfig->getIdentifier()] = $fieldConfig;
        	}
        } else {
        	$this->displayFields = array($this->filterField->getIdentifier() => $this->filterField);
        }	
        
	}
	
	
	
	/**
	 * Returns an associative array of options as possible filter values
	 *
	 * @return array
	 */
	public function getOptions() {
		$options = array();
		$renderedOptions = array();

        $fields = $this->getFieldsRequiredToBeSelected();
        $options = $this->getOptionsByFields($fields);
        
        $renderedOptions = $this->getRenderedOptionsByGroupData($options);
        $renderedOptions = $this->addInactiveOption($renderedOptions);
        
        return $renderedOptions;
	}

	
	
	/**
	 * Add inactiveFilterOpotion to rendered options
	 * TODO move to selectFilter?
	 * 
	 * @param array $renderedOptions
	 */
	protected function addInactiveOption($renderedOptions) {
        
		if($this->filterConfig->getInactiveOption()) {
        	$renderedOptions[''] = $this->filterConfig->getInactiveOption();
        }
 
        return $renderedOptions;
	}
	
	
	
	/**
	 * Returns an array of options to be displayed by filter
	 * for a given array of fields
	 *
	 * @param array Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 * @return array Options to be displayed by filter
	 */
	protected function getOptionsByFields($fields) {
		$groupDataQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
		
		foreach($fields as $selectField) {
			$groupDataQuery->addField(Tx_PtExtlist_Utility_DbUtils::getAliasedSelectPartByFieldConfig($selectField));
		}
				
        if ($this->additionalTables != '') {
           $groupDataQuery->addFrom($this->additionalTables);
        }
               
        foreach($this->displayFields as $displayField) {
        	$groupDataQuery->addSorting($displayField->getIdentifier(), Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
        }

        if($this->showRowCount) {
        	$groupDataQuery->addField(sprintf('count("%s") as rowCount', $this->filterField->getTableFieldCombined()));
			$groupDataQuery->addGroupBy($this->filterField->getIdentifier()); 
        }

        $excludeFiltersArray = $this->buildExcludeFiltersArray();
        
        $options = $this->dataBackend->getGroupData($groupDataQuery, $excludeFiltersArray);
        return $options;
	}

	
	/**
	 * Returns an array of <table>.<field> strings required by filter
	 *
	 * @return string
	 */
	protected function getFieldsRequiredToBeSelected() {

		$mergedFields = $this->displayFields;
		$mergedFields[$this->filterField->getIdentifier()] = $this->filterField;
        
        return $mergedFields;
	}
	
	
	
	/**
	 * Returns associative array of exclude filters for given TS configuration
	 *
	 * @return array Array with exclude filters. Encoded as (array('filterboxIdentifier' => array('excludeFilter1','excludeFilter2',...)))
	 */
	public function buildExcludeFiltersArray() {
		
		$excludeFiltersAssocArray = array($this->filterBoxIdentifier => array($this->filterIdentifier));
		
		if ($this->excludeFilters != '') {
			
			$excludeFiltersFlatArray = t3lib_div::trimExplode(',', $this->excludeFilters);
			
			foreach($excludeFiltersFlatArray as $excludeFilter) {
				list($filterboxIdentifier, $filterIdentifier) = explode('.', $excludeFilter);
				if ($filterIdentifier != '' && $filterboxIdentifier != '') {
				    $excludeFiltersAssocArray[$filterboxIdentifier][] = $filterIdentifier;
				} else {
					throw new Exception('Wrong configuration of exclude filters for filter '. $this->getFilterBoxIdentifier() . '.' . $this->getFilterIdentifier() . '. Should be comma seperated list of <filterboxIdentifier>.<filterIdentifier> but was ' . $excludeFilter . ' 1281102702'); 
				}
			}
		}

		return $excludeFiltersAssocArray;

	}
	
	

	/**
	 * Returns value of selected option
	 *
	 * @return mixed String for single value, array for multiple values
	 */
	public function getValue() {
		return $this->filterValues;
	}
	
	
	
	/**
	 * Return is rowcount is shown
	 * 
	 * @return integer
	 */
	public function getShowRowCount() {
		return $this->showRowCount;
	}
	
}