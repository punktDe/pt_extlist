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
 * @package Domain
 * @subpackage Configuration\Filters
 * @author Daniel Lienert <lienert@punkt.de>
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration
															 implements Tx_PtExtlist_Domain_Configuration_RenderConfigInterface {

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
	 * default value for this filter
	 * 
	 * @var string
	 */
	protected $defaultValue;
	
	
	
	/**
	 * Label to be shown for this filter
	 *
	 * @var string
	 */
	protected $label;
	
	
	
	/**
	 * If set, it is the label for an inactive option added to the filter
	 * @var string
	 */
	protected $inactiveOption = '';
	
	
	
	/**
	 * If set, the filter sets itself inactive when this value is submitted
	 * @var string
	 */
	protected $inactiveValue = '';
	
	
	
	/** 
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	protected $fieldIdentifier;
	
	
	
	/**
	 * Comma seperated list of gids that have access to this filter
	 *
	 * @var array
	 */
	protected $accessGroups = array();
	
	
	
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
	 * Submit filter by javascript on change
	 * 
	 * @var boolean
	 */
	protected $submitOnChange = false;
	
	
	
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
	 * If this is set to true, the query will be inverted
	 *
	 * @var boolean
	 */
	protected $invert = false;
	
	
	
	/**
	 * If this is set to true, the filter has the ability to be inverted
	 *
	 * @var truefalse
	 */
	protected $invertable = false;
	
	
	
	/**
	 * If set to true, this filter is active
	 *
	 * @var bool
	 */
	protected $isActive = 1;
	
	
	
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
	 * cObj config array to render every filteroption
	 * in typoscript object array notation
	 * @var array
	 */
	protected $renderObj = NULL;
	
	
	
	/**
	 * renderuserFunction configuration to render everey filteroption
	 * @var array
	 */
	protected $renderUserFunctions = NULL;
	
	
	
	/**
	 * Path to fluid template
	 * @var string
	 */
	protected $renderTemplate;
	
	
	
	/**
	 * This flag indicates if this filter is accessable for the current user.
	 * Will be injected from the factory.
	 * @var bool
	 */
	protected $accessable = FALSE;
	
	
	
	/**
	 * Build the filterconfig object
	 * 
	 * @param array $settings
	 * @param string $filterBoxIdentifier
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_AbstractConfigurationBuilder $configurationBuilder, $settings, $filterboxIdentifier) {
		$settings['filterboxIdentifier'] = $filterboxIdentifier;
		parent::__construct($configurationBuilder, $settings);
	}
	
	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Configuration/Tx_PtExtlist_Domain_Configuration_AbstractConfiguration::init()
	 */
	public function init() {
		// required
		$this->setRequiredValue('filterboxIdentifier', 'Filterbox identifier must not be empty. 1277889652');
		$this->setRequiredValue('filterIdentifier', 'No filterIdentifier specified in config. 1277889452');
		$this->setRequiredValue('filterClassName', 'No filterClassName specified for filter ' . $this->filterIdentifier . '. 1277889552');
		$this->setRequiredValue('partialPath', 'No partial path is configured for ' . $this->filterIdentifier . ' (TS key parialPath). 1281013746');
		
		$this->setRequiredValue('fieldIdentifier', 'No fieldIdentifier set in TS config for filter ' . $this->filterIdentifier . ' 1280762513');
		$fieldIdentifierList = t3lib_div::trimExplode(',', $this->settings['fieldIdentifier']);
		$this->fieldIdentifier = $this->configurationBuilder->buildFieldsConfiguration()->extractCollectionByIdentifierList($fieldIdentifierList);
		
		// optional
		$this->setBooleanIfExistsAndNotNothing('invert');
		$this->setBooleanIfExistsAndNotNothing('invertable');
		$this->setBooleanIfExistsAndNotNothing('submitOnChange');
		$this->setValueIfExists('defaultValue');
		$this->setValueIfExists('inactiveOption');
		$this->setValueIfExists('inactiveValue');
		$this->setValueIfExistsAndNotNothing('renderUserFunctions');
		$this->setValueIfExistsAndNotNothing('renderTemplate');
		
		if($this->configValueExiststAndNotNothing('label')) {
			$this->label = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($this->settings['label']);
			if(t3lib_div::isFirstPartOfStr($this->label, 'LLL:')) {
				$this->label = Tx_Extbase_Utility_Localization::translate($this->label);	
			}
		}
		
		if(array_key_exists('renderObj', $this->settings)) {
        	$this->renderObj = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray(array('renderObj' => $this->settings['renderObj']));
        }
        
		if(array_key_exists('accessGroups', $this->settings)) {
			$this->accessGroups = t3lib_div::trimExplode(',', $this->settings['accessGroups']);
		}
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
     * @return array of group IDs
     */
    public function getAccessGroups() {
        return $this->accessGroups;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getDependsOn() {
        return $this->dependsOn;
    }
    
    
    
    /**
     * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
     */
    public function getFieldIdentifier() {
        return $this->fieldIdentifier;
    }
    
    
    
    /**
     * @return unknown
     */
    public function getFilterIdentifier() {
        return $this->filterIdentifier;
    }
    
    
    
    /**
     * Get the default value
     * @return string
     */
    public function getDefaultValue() {
    	return $this->defaultValue;
    }  
    
    
    
    /**
     * @return boolean
     */
    public function getInvert() {
        return $this->invert;
    }
    
    
    
    /**
     * @return boolean
     */
    public function getInvertable() {
    	return $this->invertable;
    }
    
    
    
    /**
     * @return boolean
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
     * @return boolean
     */
    public function getResetFilters() {
        return $this->resetFilters;
    }
    
    
    
	/**
	 * cObj configuration
     * @return array
     */
    public function getRenderObj() {
        return $this->renderObj;
    }
    
    
    
    /**
     * @return string renderTemplate
     */
    public function getRenderTemplate() {
    	return $this->renderTemplate;
    }
    
    
    /**
     * @return boolean
     */
    public function getResetListSortingStateOnSubmit() {
        return $this->resetListSortingStateOnSubmit;
    }
    

    
	/**
     * @return string
     */
    public function getInactiveOption() {
        return $this->inactiveOption;
    }
    
    
    
	/**
     * @return string
     */
    public function getInactiveValue() {
        return $this->inactiveValue;
    }
    
    
    
    /**
     * @return boolean
     */
    public function getSubmitOnChange() {
    	return $this->submitOnChange;
    }
    
    
    
	/**
	 * @return array
	 */
	public function getRenderUserFunctions() {
		return $this->renderUserFunctions;
	}
	
	
	
	/**
	 * @param bool $accessable
	 */
	public function setAccessable($accessable) {
		$this->accessable = (bool) $accessable;
	}

	
	
	/**
	 * Returns the accessable flag.
	 * 
	 * @return bool
	 */
	public function isAccessable() {
		return $this->accessable;
	}
	
}
?>