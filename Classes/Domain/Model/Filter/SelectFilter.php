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
 * Class implements a select filter
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Model_Filter_SelectFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractGroupDataFilter {
	
    /**
     * Holds an array of filter values
     *
     * @var array
     */
	protected $filterValues = array();
	
	
	
	/**
	 * Holds identifier of field that should be filtered
	 *
	 * @var string
	 */
	protected $fieldDescriptionIdentifier;
	
	   
	
	/**
	 * Holds an array of fields to be used as filter value when filter is submitted
	 * encoded as array(<table>.<field>, <table>.<field>, ...)
	 *
	 * @var array
	 */
	protected $filterFields = array();
	
	
	
	/**
	 * Holds an array of fields to be used as displayed values for the filter (the options that can be selected)
	 * encoded as array(<table>.<field>,...)
	 *
	 * @var array
	 */
	protected $displayFields = array();
	
	
	
	/**
	 * Array of filters to be excluded if options for this filter are determined
	 *
	 * @var string
	 */
	protected $excludeFilters;
	
	
	
	/**
	 * Holds a comma seperated list of additional tables that are required for this filter
	 *
	 * @var string
	 */
	protected $additionalTables;
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::createFilterQuery()
	 *
	 */
	protected function createFilterQuery() {
		if (count($this->filterValues) == 1) {
			$this->filterQuery->addCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria::equals($this->fieldDescriptionIdentifier, $this->filterValues[0]));
		} elseif (count($this->filterValues) > 1) {
			$this->filterQuery->addCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria::in($this->fieldDescriptionIdentifier, $this->filterValues));
		} else {
			$this->filterQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
		}
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilter()
	 *
	 */
	protected function initFilter() {
		
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
	    
		if ($this->filterConfig->getFieldDescriptionIdentifier() == '') {
    	    throw new Exception('No fieldDescriptionIdentifier set in TS config for filter ' . $this->filterBoxIdentifier . '.' . $this->filterIdentifier . ' 1281019496');
    	}
		
        $this->fieldDescriptionIdentifier = $this->filterConfig->getFieldDescriptionIdentifier();
        
        tx_pttools_assert::isNotEmptyString($filterSettings['filterFields']);
        $this->filterFields = explode(',', $filterSettings['filterFields']);
        
        tx_pttools_assert::isNotEmptyString($filterSettings['displayFields']);
        $this->displayFields = explode(',',$filterSettings['displayFields']);

        if (array_key_exists('excludeFilters', $filterSettings)) {
        	$this->excludeFilters = $filterSettings['excludeFilters'];
        }
        
        if (array_key_exists('additionalTables', $filterSettings)) {
        	$this->additionalTables = $filterSettings['additionalTables'];
        }
        
	}
	
	
	
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
		return array('filterValues' => $this->filterValues);
	}
	
	
	
	/**
	 * Returns an associative array of options as possible filter values
	 *
	 * @return array
	 */
	public function getOptions() {
		$options = array();
		$renderedOptions = array();

		$tables = $this->getTablesRequiredToBeSelected();
        $fields = $this->getFieldsRequiredToBeSelected();
		
		if (count($tables) > 0) {  // TODO why must table > 0 here?
            $options = $this->getOptionsByTablesAndFields($tables, $fields);
            $renderedOptions = $this->getRenderedOptionsByGroupData($options);
		}
        return $renderedOptions;
	}
	
	
	
	/**
	 * Returns an array of options to be displayed by filter
	 * for a given array of tables and a given array of fields
	 *
	 * @param array $tables
	 * @param array $fields
	 * @return array Options to be displayed by filter
	 */
	protected function getOptionsByTablesAndFields($tables, $fields) {
		$groupDataQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
		$selectString = implode(', ', $fields);
        if ($this->additionalTables != '') {
           $groupDataQuery->addFrom($this->additionalTables);
        }
        $groupDataQuery->addField($selectString);

        $excludeFiltersArray = $this->buildExcludeFiltersArray();
        // Add this filter to excluded filters
        $excludeFiltersArray[$this->filterBoxIdentifier][] = $this->filterIdentifier;
        $options = $this->dataBackend->getGroupData($groupDataQuery, $excludeFiltersArray);
        
        return $options;
	}
	
	
	
	/**
	 * Returns an array of <table>.<field> strings required by filter
	 *
	 * @return string
	 */
	protected function getFieldsRequiredToBeSelected() {
        $fields = array();
		$mergedFields = array_merge($this->displayFields, $this->filterFields);
	    foreach ($mergedFields as $optionSourceField) {
            $this->checkFieldConfig($optionSourceField);
            $fields[] = trim($optionSourceField);
        }
        return $fields;
	}
	
	
	
	/**
	 * Returns an array of tables that are required by the filter
	 *
	 * @return array
	 */
	protected function getTablesRequiredToBeSelected() {
		$tables = array();
	    $mergedFields = array_merge($this->displayFields, $this->filterFields);
        foreach ($mergedFields as $optionSourceField) {
            $this->checkFieldConfig($optionSourceField);
        	if (!in_array($table, $tables)) {
                $tables[] = $table;
            }
        }
        return $tables;
	}
	
	
	
	/**
	 * Checks for a field config whether it has right syntax:
	 * 
	 * <tablename>.<fieldname>
	 *
	 * @param string $fieldConfigString
	 * @throws Exception on wrong syntax
	 */
	protected function checkFieldConfig($fieldConfigString) {
		list($table,$field) = explode('.', trim($fieldConfigString));
        if (!($table != '' && $field != '')) {
        	throw new Exception('wrong configuration of option source field for filter ' . $this->getFilterBoxIdentifier() . '.' . $this->getFilterIdentifier() . ' 1281090352');
        }
	}
	
	
	
	/**
	 * Returns an array expected by f:form.select viewhelper:
	 * 
	 * array(<keyForReturnAsSelectedValue> => <valueToBeShownAsSelectValue>)
	 *
	 * @param array $groupData 
	 * @return array
	 */
	protected function getRenderedOptionsByGroupData(array $groupData = array()) {
	   $renderedOptions = array();
	   foreach($groupData as $option) {
            // TODO Add render-option to TS config for this stuff here
            $key = '';
            foreach($this->filterFields as $filterField) {
                list($filterTable, $filterField) = explode('.', trim($filterField));
                $key .= $option[$filterField];
            }
            $value = '';
            foreach($this->displayFields as $displayField) {
                list($displayTable, $displayField) = explode('.', trim($displayField));
                $value .= $option[$displayField];
            }
            $renderedOptions[$key] = $value;
        }
        return $renderedOptions;
	}
	
	
	
	
	/**
	 * Returns associative array of exclude filters for given TS configuration
	 *
	 * @return array Array with exclude filters. Encoded as (array('filterboxIdentifier' => array('excludeFilter1','excludeFilter2',...)))
	 */
	public function buildExcludeFiltersArray() {
		if ($this->excludeFilters != '') {
			$excludeFiltersFlatArray = explode(',', $this->excludeFilters);
			$excludeFiltersAssocArray = array();
			foreach($excludeFiltersFlatArray as $excludeFilter) {
				list($filterboxIdentifier, $filterIdentifier) = explode('.', trim($excludeFilter));
				if ($filterIdentifier != '' && $filterboxIdentifier != '') {
				    $excludeFiltersAssocArray[$filterboxIdentifier][] = $filterIdentifier;
				} else {
					throw new Exception('Wrong configuration of exclude filters for filter '. $this->getFilterBoxIdentifier() . '.' . $this->getFilterIdentifier() . '. Should be comma seperated list of <filterboxIdentifier>.<filterIdentifier> but was ' . $excludeFilter . ' 1281102702'); 
				}
			}
			return $excludeFiltersAssocArray;
		} else {
			return array();
		}
	}
	
	
	
	/**
	 * Returns value of selected option
	 *
	 * @return mixed String for single value, array for multiple values
	 */
	public function getValue() {
		return $this->filterValues;
	}

}