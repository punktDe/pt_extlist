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
 * Class implements an abstract filter for all options filters
 * 
 * @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
 * @package Domain
 * @subpackage Model\Filter
 */
abstract class Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractFilter {
	
	/**
     * Holds an array of filter values
     *
     * @var array
     */
	protected $filterValues = array();
	
	
	
	/**
	 * Holds identifier of field that should be filtered
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	protected $fieldIdentifier;
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_FilterInterface::reset()
	 *
	 */
	public function reset() {
		$this->filterValues = array();
		$this->sessionFilterData = array();
		$this->filterQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
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
		$fieldName = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($this->fieldIdentifier);
	
		if (is_array($this->filterValues) && count($this->filterValues) == 1) {
			$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::equals($fieldName, current($this->filterValues));
		} elseif (is_array($this->filterValues) && count($this->filterValues) > 1) {
			$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::in($fieldName, $this->filterValues);
		}

		return $criteria;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::setActiveState()
	 */
	protected function setActiveState() {
		$this->isActive = in_array($this->filterConfig->getInactiveValue(), $this->filterValues) ? false : true;
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
	 * Returns an associative array of options as possible filter values
	 *
	 * @return array
	 */
	public function getOptions() {
		$dataProvider = Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderFactory::createInstance($this->filterConfig);
		
		$renderedOptions = $dataProvider->getRenderedOptions(); 
		$this->addInactiveOption($renderedOptions);
		$this->setSelectedOptions($renderedOptions);
		
        return $renderedOptions;
	}
	
	
	
	/**
	 * Set the the selected state in rendered Values
	 * 
	 * @param array $renderedOptions
	 */
	protected function setSelectedOptions(&$renderedOptions) {
		
		foreach($this->filterValues as $filterValue) {
			$renderedOptions[$filterValue]['selected'] = true;
		}
	}
	
	
	
	/**
	 * Add inactiveFilterOpotion to rendered options
	 * 
	 * @param array $renderedOptions
	 */
	protected function addInactiveOption(&$renderedOptions) {

		if($this->filterConfig->getInactiveOption()) {
			if(count($this->filterValues) == 0) {
				$selected = true;
			} else {
				$selected = in_array($this->filterConfig->getInactiveValue(), $this->filterValues) ? true : false;	
			}
             
   			$inactiveValue = $this->filterConfig->getInactiveValue();
            
			$renderedOptionsWithInactive[$inactiveValue] = array('value' => $this->filterConfig->getInactiveOption(),
        													     'selected' => $selected);
			
        	$renderedOptionsWithInactive=array_merge($renderedOptionsWithInactive, $renderedOptions);
        	$renderedOptions = $renderedOptionsWithInactive;
        }
 
        return $renderedOptions;
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
	 * Returns filter breadcrumb for this filter.
	 * Most likely to be overwritten in concrete filter class.
	 *
	 * @return Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb
	 */
	public function getFilterBreadCrumb() {
		$breadCrumb = new Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb($this);
		$breadCrumb->setMessage($this->filterIdentifier . ' = ' . implode(', ', $this->filterValues));
		return $breadCrumb;
	}
}