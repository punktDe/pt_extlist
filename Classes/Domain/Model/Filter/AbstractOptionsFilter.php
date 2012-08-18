<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Class implements an abstract filter for all options filters
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter
 * @see Tx_PtExtlist_Tests_Domain_Model_Filter_AbstractOptionsFilterTest
 */
abstract class Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractFilter {

	/**
	 * Holds an array of filter values
	 *
	 * @var array
	 */
	protected $filterValues = array();


    /**
     * Array with selectable filter options
     *
     * @var array
     */
    protected $options = NULL;

	
	/**
	 * @see Tx_PtExtbase_State_Session_SessionPersistableInterface::persistToSession()
	 * @return array
	 */
	public function persistToSession() {
		return array('filterValues' => $this->filterValues, 'invert' => $this->invert);
	}

	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::reset()
	 */
	public function reset() {
		$this->filterValues = array();  // We need an empty array here, to overwrite a TS default value after reset
		parent::reset();
	}
	
	

	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilter()
	 */
	protected function initFilter() {}



	/**
	 * Build the criteria for a single field
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier
	 * @return Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
	 */
	protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {
		$fieldName = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($fieldIdentifier);
		$singleCriteria = NULL;

		if (is_array($this->filterValues) && count($this->filterValues) == 1) {
			$singleCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::equals($fieldName, current($this->filterValues));
		} elseif (is_array($this->filterValues) && count($this->filterValues) > 1) {
			$singleCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::in($fieldName, $this->filterValues);
		}

		return $singleCriteria;
	}



	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::setActiveState()
	 */
	protected function setActiveState() {
		if (is_array($this->filterValues)) {
			$this->isActive = (in_array($this->filterConfig->getInactiveValue(), $this->filterValues) || count($this->filterValues) == 0 ? false : true);
		} else {
			$this->isActive = false;
		}
	}
	


	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilterByGpVars()
	 *
	 */
	protected function initFilterByGpVars() {

		if (array_key_exists('filterValues', $this->gpVarFilterData)) {
			$filterValues = $this->gpVarFilterData['filterValues'];
			
			if (is_array($filterValues)) {
				$this->filterValues = array_filter($filterValues);
			} else {
				$this->filterValues = trim($filterValues) != '' ? array($filterValues => $filterValues) : array();
			}
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
		if ($this->filterConfig->getDefaultValue()) {
			$this->setDefaultValuesFromTSConfig($this->filterConfig->getDefaultValue());
		}
	}



	/**
	 * Set the groupfilters default value
	 *
	 * @param mixed $defaultValue single value or array of preselected values
	 */
	protected function setDefaultValuesFromTSConfig($defaultValue) {
		if (is_array($defaultValue)) {
			foreach($defaultValue as $value) {
				$this->filterValues[$value] = $value;
			}
		} else {
			$this->filterValues[$defaultValue] = $defaultValue;
		}
	}



    /**
     * @return Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface
     */
    protected function buildDataProvider() {
        return Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderFactory::createInstance($this->filterConfig);
    }


    /**
     * Get cached renderes options from data provider
     *
     * @return array|null
     */
    protected function getOptionsFromDataProvider() {
        if($this->options === NULL) {
            $this->options = $this->buildDataProvider()->getRenderedOptions();
        }

        return $this->options;
    }



	/**
	 * Returns an associative array of options as possible filter values
	 *
	 * @return array
	 */
	public function getOptions() {
		$renderedOptions = $this->getOptionsFromDataProvider();
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
			if (!is_array($filterValue) && array_key_exists($filterValue, $renderedOptions)) {
				$renderedOptions[$filterValue]['selected'] = true;
			}
		}
	}



	/**
	 * Add inactiveFilterOpotion to rendered options
	 *
	 * @param array $renderedOptions
	 * @return array
	 */
	protected function addInactiveOption(&$renderedOptions) {

		if ($renderedOptions == NULL) $renderedOptions = array();
		
		if ($this->filterConfig->getInactiveOption()) {

			unset($renderedOptions[$this->filterConfig->getInactiveValue()]);

			if (count($this->filterValues) == 0) {
				$selected = true;
			} else {
				$selected = in_array($this->filterConfig->getInactiveValue(), $this->filterValues) ? true : false;
			}

			$inactiveValue = $this->filterConfig->getInactiveValue();

			$renderedInactiveOption[$inactiveValue] = array('value' => $this->filterConfig->getInactiveOption(),
        													     'selected' => $selected);

			$renderedOptions = $renderedInactiveOption + $renderedOptions;
		}

		return $renderedOptions;
	}



	/**
	 * Returns value of selected option
	 *
	 * @return mixed String for single value, array for multiple values
	 */
	public function getValue() {
		if (count($this->filterValues) > 1){
			return $this->filterValues;
		} else {
			return current($this->filterValues);
		}
	}



	/**
	 * Return filter value string for breadcrumb
	 *
	 * @return string
	 */
	public function getDisplayValue() {
        $options = $this->getOptions();
        $displayValues = array();

        foreach($options as $key => $option) {
            if($option['selected'] === TRUE) {
                $displayValues[] = $option['value'];
            }
        }

		return implode(', ', $displayValues);
	}

}
?>