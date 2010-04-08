<?php

class Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig {
	
	
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
	protected $isActice = 1;
	protected $value;
	protected $settings;
	
	
	
	/**
	 * Constructor for filter config
	 *
	 * @param array $filterSettings    Settings for filter
	 */
	public function __construct(array $filterSettings) {
		// TODO check which values need to be set here and add assertions!
		tx_pttools_assert::isNotEmptyString($filterSettings['filterIdentifier']);
		
		$this->filterIdentifier = $filterSettings['filterIdentifier'];
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
    public function getIsActice() {
        return $this->isActice;
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