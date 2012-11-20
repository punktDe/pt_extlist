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
 * Implements data provider for grouped list data
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class Tx_PtExtlist_Domain_Model_Filter_DataProvider_GroupData extends Tx_PtExtlist_Domain_Model_Filter_DataProvider_AbstractDataProvider {

    /**
	 * Show the group row count
	 * 
	 * @var integer
	 */
	protected $showRowCount;
	
	
	
	/**
	 * Array of filters to be excluded if options for this filter are determined
	 *
	 * @var array
	 */
	protected $excludeFilters = NULL;
	
	
	/**
	 * Holds the filter value to be used when filter is submitted
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	protected $filterField;
	
	
	
	/**
	 * Holds an collection of fieldconfigs to be used as displayed values for the filter (the options that can be selected)
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	protected $displayFields = NULL;
		
	
	
	/**
	 * Holds a comma seperated list of additional tables that are required for this filter
	 *
	 * @var string
	 */
	protected $additionalTables;
	

	
	/**
	 * Set DisplayFields by TS-Settings
	 * 
	 * @param array $displayFieldSettings
	 */
	protected function setDisplayFieldsByTSConfig($displayFieldSettings) {
		
	 	if($displayFieldSettings) {
        	$displayFields = t3lib_div::trimExplode(',', $displayFieldSettings);
        	$this->displayFields = $this->dataBackend->getFieldConfigurationCollection()->extractCollectionByIdentifierList($displayFields);
        } else {
        	$fieldIdentifierList = t3lib_div::trimExplode(',', $this->filterConfig->getSettings('fieldIdentifier'));
        	$this->displayFields = $this->dataBackend->getFieldConfigurationCollection()->extractCollectionByIdentifierList($fieldIdentifierList);
        }	
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
        	
        	$renderedOptions[$optionKey] = $optionData;
        	$renderedOptions[$optionKey]['value'] = $this->renderOptionData($optionData);
        	$renderedOptions[$optionKey]['selected'] = false;
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
		return $this->dataBackend->getGroupData($groupDataQuery, $excludeFiltersArray, $this->filterConfig);
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
        
        $groupFields = array();
        foreach ($this->getFieldsRequiredToBeSelected() as $field) {
        	$groupFields[] = $field->getIdentifier();
        }

    	$groupDataQuery->addGroupBy(implode(',', $groupFields));	

        return $groupDataQuery;
	}
	
	
	
	/**
	 *
	 * @return array
	 */
	protected function getFieldsRequiredToBeSelected() {

		if($this->filterField) {
			$mergedFields = clone $this->displayFields;
			$mergedFields->addFieldConfig($this->filterField);
	        
	        return $mergedFields;	
		} else {
			return $this->displayFields;
		}
	}
	
	
	/**
	 * Returns associative array of exclude filters for given TS configuration
	 *
	 * @return array Array with exclude filters. Encoded as (array('filterboxIdentifier' => array('excludeFilter1','excludeFilter2',...)))
	 */
	protected function buildExcludeFiltersArray() {
		
		$excludeFiltersAssocArray = array($this->filterConfig->getFilterboxIdentifier() => array($this->filterConfig->getFilterIdentifier()));
		
		if($this->excludeFilters) {	
			foreach($this->excludeFilters as $excludeFilter) {
				
				list($filterboxIdentifier, $filterIdentifier) = explode('.', $excludeFilter);
				
				if ($filterIdentifier != '' && $filterboxIdentifier != '') {
				    $excludeFiltersAssocArray[$filterboxIdentifier][] = $filterIdentifier;
				} else {
					throw new Exception('Wrong configuration of exclude filters for filter '. $this->filterConfig->getFilterboxIdentifier() . '.' . $this->filterConfig->getFilterIdentifier() . '. Should be comma seperated list of "filterboxIdentifier.filterIdentifier" but was ' . $excludeFilter . ' 1281102702'); 
				}
				
			}
		}

		return $excludeFiltersAssocArray;

	}
	
	
	
	/**
	 * Init the dataProvider by TS-conifg
	 * 
	 * @param array $filterSettings
	 */
	protected function initDataProviderByTsConfig($filterSettings) {
		
		$filterField = trim($filterSettings['filterField']);
		if($filterField) {
			$this->filterField = $this->resolveFieldConfig($filterField);
		} else {
			$this->filterField = $this->filterConfig->getFieldIdentifier()->getItemByIndex(0);
		}
        
        $this->setDisplayFieldsByTSConfig(trim($filterSettings['displayFields']));
               
        if (array_key_exists('excludeFilters', $filterSettings) && trim($filterSettings['excludeFilters'])) {
        	$this->excludeFilters = t3lib_div::trimExplode(',', $filterSettings['excludeFilters']);
        }
        
        if (array_key_exists('additionalTables', $filterSettings)) {
        	$this->additionalTables = $filterSettings['additionalTables'];
        }
        
		if (array_key_exists('showRowCount', $filterSettings) && $filterSettings['showRowCount']) {
        	$this->showRowCount = $filterSettings['showRowCount'];
        }
	}
	
	
	
	/**
	 * Get the field config object by fieldIdentifier string
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier
	 */
	protected function resolveFieldConfig($fieldIdentifier) {	
		return $this->dataBackend->getFieldConfigurationCollection()->getFieldConfigByIdentifier($fieldIdentifier);
	}

	/****************************************************************************************************************
	 * Methods implementing "Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface"
	 *****************************************************************************************************************/
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::init()
	 */
	public function init() {
		$this->initDataProviderByTsConfig($this->filterConfig->getSettings());
	}
	
	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::getRenderedOptions()
	 */
	public function getRenderedOptions() {
		$fields = $this->getFieldsRequiredToBeSelected();
		return $this->getRenderedOptionsByFields($fields);
	}
	
	
}
?>