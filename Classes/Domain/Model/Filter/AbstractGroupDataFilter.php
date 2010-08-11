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
	 * @var string
	 */
	protected $fieldDescriptionIdentifier;
	
	   
	
	/**
	 * Holds the filter value to be used when filter is submitted
	 * encoded as <table>.<field>
	 *
	 * @var string
	 */
	protected $filterField;
	
	
	
	/**
	 * Holds an array of fields to be used as displayed values for the filter (the options that can be selected)
	 * encoded as array(<table>.<field>,...)
	 *
	 * @var array
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
		return array('filterValues' => $this->filterValues);
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
		if (count($this->filterValues) == 1 && $this->filterValues[0] != '') {
			$this->filterQuery->addCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria::equals($this->fieldDescriptionIdentifier, $this->filterValues[0]));
		} elseif (count($this->filterValues) > 1) {
			$this->filterQuery->addCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria::in($this->fieldDescriptionIdentifier, $this->filterValues));
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
	    
		if ($this->filterConfig->getFieldDescriptionIdentifier() == '') {
    	    throw new Exception('No fieldDescriptionIdentifier set in TS config for filter ' . $this->filterBoxIdentifier . '.' . $this->filterIdentifier . ' 1281019496');
    	}
		
        $this->fieldDescriptionIdentifier = $this->filterConfig->getFieldDescriptionIdentifier();
        
        tx_pttools_assert::isNotEmptyString($filterSettings['filterField'], array('message' => 'No filter field for filter '.$this->filterIdentifier .' specified! 1281365131'));
        $this->filterField = trim($filterSettings['filterField']);
        
        if(trim($filterSettings['displayFields'])) {
        	$this->displayFields = t3lib_div::trimExplode(',', $filterSettings['displayFields']);	
        } else {
        	$this->displayFields = array($this->filterField);
        }
               
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
		
        return $renderedOptions;
	}
	
	
	
	/**
	 * Returns an array of options to be displayed by filter
	 * for a given array of fields
	 *
	 * @param array $fields
	 * @return array Options to be displayed by filter
	 */
	protected function getOptionsByFields($fields) {
		$groupDataQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
		$selectString = implode(', ', $fields);
        if ($this->additionalTables != '') {
           $groupDataQuery->addFrom($this->additionalTables);
        }
        
        $groupDataQuery->addField($selectString);

        if($this->showRowCount) {
        	$groupDataQuery->addField('count(*) as rowCount');
			$groupDataQuery->addGroupBy($this->filterField);
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
        $fields = array();
		$mergedFields = $this->displayFields;
		
		if(!in_array($this->filterField, $mergedFields)) {
			$mergedFields[] = $this->filterField;
		}
		
	    foreach ($mergedFields as $optionSourceField) {
            $this->checkFieldConfig($optionSourceField);
        }
        
        return $mergedFields;
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
		list($table,$field) = explode('.', $fieldConfigString);
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
            list($filterTable, $filterField) = explode('.', $this->filterField);
            $key .= $option[$filterField];

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
			
			$excludeFiltersFlatArray = t3lib_div::trimExplode(',', $this->excludeFilters);
			$excludeFiltersAssocArray = array();
			
			foreach($excludeFiltersFlatArray as $excludeFilter) {
				list($filterboxIdentifier, $filterIdentifier) = explode('.', $excludeFilter);
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
	
	
	
	/**
	 * Return is rowcount is shown
	 * 
	 * @return integer
	 */
	public function getShowRowCount() {
		return $this->showRowCount;
	}
	
}