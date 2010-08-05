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
 * Class implementing configuration for filter
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig {
	
	/**
	 * Identifier of list to which this filter belongs to
	 *
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * Identifier of filterbox to which this filter belongs to
	 *
	 * @var string
	 */
	protected $filterboxIdentifier;
	
	
	
	/**
	 * Name of class implementing this filter
	 *
	 * @var string
	 */
	protected $filterClassName;
	
	
	
	/**
	 * Identifier of this filter
	 *
	 * @var string
	 */
	protected $filterIdentifier;
	
	
	
	/**
	 * Label to be shown for this filter
	 *
	 * @var string
	 */
	protected $label;
	
	
	
	/**
	 * Identifier of fieldDescription to which this filter belongs to
	 * // TODO ry21 could be the actual fieldDescription object instead of its name?
	 * @var string
	 */
	protected $fieldDescriptionIdentifier;
	
	
	
	/**
	 * Comma seperated list of gids that have access to this filter
	 *
	 * @var string
	 */
	protected $access;
	
	
	
	/**
	 * Array of columns to be hidden, if this filter is active
	 *
	 * @var array
	 */
	protected $hideColumns = array();
	
	
	
	/**
	 * If true, sorting state is reset if filter is submitted
	 *
	 * @var bool
	 */
	protected $resetListSortingStateOnSubmit = false;
	
	
	
	/**
	 * List of filters to be reset if this filter is submitted
	 *
	 * @var array
	 */
	protected $resetFilters = array();
	
	
	
	/**
	 * TODO ry21 what does this property do?
	 *
	 * @var unknown_type
	 */
	protected $dependsOn;
	
	
	
	/**
	 * TODO ry21 what does this property do?
	 *
	 * @var unknown_type
	 */
	protected $onValidated = array();
	
	
	
	/**
	 * TODO ry21 what does this property do?
	 *
	 * @var unknown_type
	 */
	protected $invert;
	
	
	
	/**
	 * If set to true, this filter is active
	 *
	 * @var bool
	 */
	protected $isActive = 1;
	
	
	
	/**
	 * Pre-defined value of filter
	 *
	 * @var string
	 */
	protected $value;
	
	
	
	/**
	 * Holds all settings passed by TS
	 *
	 * @var array
	 */
	protected $settings = array();
	
	
	
	/**
	 * Holds path to partial for filter template
	 *
	 * @var string
	 */
	protected $partialPath;
	
	
	
	/**
	 * Constructor for filter config
	 *
	 * @param array $filterSettings    Settings for filter
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $filterboxIdentifier, array $filterSettings) {
        $this->listIdentifier = $configurationBuilder->getListIdentifier();
        tx_pttools_assert::isNotEmptyString($filterboxIdentifier,array('message' => 'Filterbox identifier must not be empty. 1277889652'));
        $this->filterboxIdentifier = $filterboxIdentifier;
		$this->setPropertiesFromSettings($filterSettings);
	}
	
	
	
	/**
	 * Initializes properties from given settings array
	 *
	 * @param array $filterSettings
	 */
	protected function setPropertiesFromSettings(array $filterSettings) {
		tx_pttools_assert::isNotEmptyString($filterSettings['filterIdentifier'],array('message' => 'No filterIdentifier specified in config. 1277889452'));
        $this->filterIdentifier = $filterSettings['filterIdentifier'];
        tx_pttools_assert::isNotEmptyString($filterSettings['filterClassName'],array('message' => 'No filterClassName specified in config. 1277889552'));
        $this->filterClassName = $filterSettings['filterClassName'];
        $this->fieldDescriptionIdentifier = array_key_exists('fieldDescriptionIdentifier', $filterSettings) ? $filterSettings['fieldDescriptionIdentifier'] : '';
        tx_pttools_assert::isNotEmptyString($filterSettings['partialPath'], array('message' => 'No partial path is configured for this filter (TS key parialPath). 1281013746'));
        $this->partialPath = $filterSettings['partialPath'];
        // TODO ry21 add all properties here
		// TODO check which values need to be set here and add assertions!
        $this->settings = $filterSettings;
	}
	
	
	
	/**
	 * Returns identifier of list to which this filter belongs to
	 *
	 * @return string  Identifier of list to which this filter belongs to
	 */
    public function getListIdentifier() {
    	return $this->listIdentifier;	
    }
	
    
    
    /**
     * Returns partial path for filter template
     *
     * @return string
     */
    public function getPartialPath() {
    	return $this->partialPath;
    }
    
    
    
    /**
     * Returns identifier of filterbox to which this filter belongs to
     *
     * @return string Identifier of filterbox to which this filter belongs to
     */
    public function getFilterboxIdentifier() {
    	return $this->filterboxIdentifier;
    }
    
	
    /**
     * Returns class name of class implementing this filter
     *
     * @return string Class name of class implementing this filter
     */
	public function getFilterClassName() {
		return $this->filterClassName;
	}
	
	
	
    /**
     * @return unknown
     */
    public function getAccess() {
        return $this->access;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getDependsOn() {
        return $this->dependsOn;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getFieldDescriptionIdentifier() {
        return $this->fieldDescriptionIdentifier;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getFilterIdentifier() {
        return $this->filterIdentifier;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getHideColumns() {
        return $this->hideColumns;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getInvert() {
        return $this->invert;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getIsActive() {
        return $this->isActive;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getLabel() {
        return $this->label;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getOnValidated() {
        return $this->onValidated;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getResetFilters() {
        return $this->resetFilters;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getResetListSortingStateOnSubmit() {
        return $this->resetListSortingStateOnSubmit;
    }
    
    
    
    /**
     * @param string $key Key of settings array to be returned
     * @return mixed
     */
    public function getSettings($key = '') {
    	if ($key != '' ) {
    	   if (array_key_exists($key, $this->settings)) {
    		  return $this->settings[$key];
    	   }
    	} else {
            return $this->settings;
    	}
    }
    
    
    
    /**
     * @return unknown
     */
    public function getValue() {
        return $this->value;
    }
	
}


?>