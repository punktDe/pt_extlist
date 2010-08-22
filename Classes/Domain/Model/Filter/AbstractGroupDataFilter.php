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
	 */
	protected function initFilter() {}

	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::createFilterCriteria()
	 */
	protected function buildFilterCriteria() {
		
		$criteria = NULL;
		$columnName = $this->fieldIdentifier->getTableFieldCombined();
		$filterValues = array_filter($this->filterValues);
		
		if (is_array($filterValues) && count($filterValues) == 1) {
			$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::equals($columnName, current($filterValues));
		} elseif (is_array($filterValues) && count($filterValues) > 1) {
			$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::in($columnName, $filterValues);
		}
		
		return $criteria;
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilterByGpVars()
	 *
	 */
	protected function initFilterByGpVars() {
		if (array_key_exists('filterValues', $this->gpVarFilterData)) {
			$filterValues= $this->gpVarFilterData['filterValues'];
			$this->filterValues = is_array($filterValues) ? array_filter($filterValues) : array($filterValues => $filterValues);
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
        
		if (array_key_exists('showRowCount', $filterSettings) && $filterSettings['showRowCount']) {
        	$this->showRowCount = $filterSettings['showRowCount'];
        }
       
        if($this->filterConfig->getDefaultValue()) {
        	$this->setDefaultValuesFromTSConfig($this->filterConfig->getDefaultValue());
        }
	}

	
	
	/**
	 * Set the groupfilters default value
	 * 
	 * @param mixed $defaultValue single value or array of preselected values
	 */
	protected function setDefaultValuesFromTSConfig($defaultValue) {	
		if(is_array($defaultValue)) {
			unset($defaultValue['_typoScriptNodeValue']);
			foreach($defaultValue as $value) {
				$this->filterValues[$value] = $value;
			}
		} else {
			$this->filterValues[$defaultValue] = $defaultValue;
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
        $renderedOptions = $this->getRenderedOptionsByFields($fields);
        $this->addInactiveOption($renderedOptions);
        return $renderedOptions;
	}
	
	
	/**
	 * Add inactiveFilterOpotion to rendered options
	 * TODO move to selectFilter?
	 * 
	 * @param array $renderedOptions
	 */
	protected function addInactiveOption(&$renderedOptions) {
        
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
	protected function getRenderedOptionsByFields($fields) {
		$options =& $this->getOptionsByFields($fields);
		
        foreach($options as $optionData) {
        	$optionKey = $optionData[$this->filterField->getIdentifier()];
        	$selected = in_array($optionKey, $this->filterValues)  ? true : false;
        	$renderedOptions[$optionKey] = array('value' => $this->renderOptionData($optionData),
        									'selected' => $selected);
        }
        
        return $renderedOptions;
	}
	
	
	/**
	 * Get the raw data from the database
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fields
	 */
	protected function getOptionsByFields($fields) {
		$groupDataQuery = $this->buildGroupDataQuery($fields);
        $excludeFiltersArray = $this->buildExcludeFiltersArray();
        
        return $this->dataBackend->getGroupData($groupDataQuery, $excludeFiltersArray);
	}
	
	
	/**
	 * Build the group data query to retrieve the group data
	 * 
	 * @param array Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fields
	 */
	protected function buildGroupDataQuery($fields) {
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
        	// TODO only works with SQL!
        	$groupDataQuery->addField(sprintf('count("%s") as rowCount', $this->filterField->getTableFieldCombined()));
        }
        
        $groupDataQuery->addGroupBy($this->filterField->getIdentifier()); 
        
        return $groupDataQuery;
	}
	
	
	/**
	 * Render a single option line by cObject or default
	 *
	 * @param array $optionData
	 */
	protected function renderOptionData($optionData) {
		
		$option = '';
		
		foreach($this->displayFields as $displayField) {
        	$values[] = $optionData[$displayField->getIdentifier()];
        }
		$optionData['allDisplayFields'] = implode(' ', $values);
		
		$option = Tx_PtExtlist_Utility_RenderValue::renderByConfigObjectUncached($optionData, $this->filterConfig);
		
		return $option;
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
		if(count($this->filterValues) > 1){
			return $this->filterValues;
		} else {
			return current($this->filterValues);
		}
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