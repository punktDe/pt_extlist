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



class Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig {
	
	
	protected $filterClassName;
	protected $filterIdentifier;
	protected $label;
	protected $fieldDescriptionIdentifier;
	protected $access;
	protected $hideColumns = array();
	protected $resetListSortingStateOnSubmit = false;
	protected $resetFilters = array();
	protected $dependsOn;
	protected $onValidated = array();
	protected $invert;
	protected $isActive = 1;
	protected $value;
	protected $settings;
	
	
	
	/**
	 * Constructor for filter config
	 *
	 * @param array $filterSettings    Settings for filter
	 */
	public function __construct(array $filterSettings) {
		// TODO check which values need to be set here and add assertions!
		tx_pttools_assert::isNotEmptyString($filterSettings['filterIdentifier'],array('message' => 'No filterIdentifier specified in config. 1277889452'));
		
		$this->filterIdentifier = $filterSettings['filterIdentifier'];
	}
	
	
	
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
     * @return unknown
     */
    public function getSettings() {
        return $this->settings;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getValue() {
        return $this->value;
    }
	
}


?>