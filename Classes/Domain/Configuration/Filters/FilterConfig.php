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
 * @package pt_extlist
 * @subpackage Domain\Configuration\Filters
 * @author Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig implements Tx_PtExtlist_Domain_Configuration_RenderConfigInterface {
	
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
	protected $inactiveOption;
	
	
	
	/**
	 * Identifier of field to which this filter belongs to
	 * // TODO ry21 could be the actual fieldDescription object instead of its name?
	 * @var string
	 */
	protected $fieldIdentifier;
	
	
	
	/**
	 * Comma seperated list of gids that have access to this filter
	 *
	 * @var string
	 */
	protected $accessGroups;
	
	
	
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
	 * This flag indicates if this filter is accessable for the current user.
	 * Will be injected from the factory.
	 * @var bool
	 */
	protected $accessable = FALSE;
	
	
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
        
        tx_pttools_assert::isNotEmptyString($filterSettings['fieldIdentifier'], array('message' => 'No fieldIdentifier set in TS config for filter ' . $this->filterIdentifier . ' 1280762513'));
		$this->fieldIdentifier =  $filterSettings['fieldIdentifier'];
               
        tx_pttools_assert::isNotEmptyString($filterSettings['partialPath'], array('message' => 'No partial path is configured for this filter (TS key parialPath). 1281013746'));
        $this->partialPath = $filterSettings['partialPath'];
        
        
        if(array_key_exists('invert', $filterSettings)) {
        	$this->invert = $filterSettings['invert'] ? true : false;
        }
        
		if(array_key_exists('invertable', $filterSettings)) {
        	$this->invertable = $filterSettings['invertable'] ? true : false;
        }
        
		if(array_key_exists('inactiveOption', $filterSettings)) {
        	$this->inactiveOption = trim($filterSettings['inactiveOption']);
        }
        
		if(array_key_exists('submitOnChange', $filterSettings)) {
        	$this->submitOnChange = $filterSettings['submitOnChange'] ? true : false;
        }
        
		if(array_key_exists('renderObj', $filterSettings)) {
        	$this->renderObj = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray(array('renderObj' => $filterSettings['renderObj']));
        	
        }
        
		if(array_key_exists('renderUserFunctions', $filterSettings)) {
			$this->renderUserFunctions = $filterSettings['renderUserFunctions'];
		}
        
        $this->defaultValue = array_key_exists('defaultValue', $filterSettings) ? $filterSettings['defaultValue'] : '';
        
		if(array_key_exists('accessGroups', $filterSettings)) {
			$this->accessGroups = t3lib_div::trimExplode(',', $filterSettings['accessGroups']);
		}
        
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
     * @return unknown
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
    
    
    public function getDefaultValue() {
    	return $this->defaultValue;
    }
    
    
    /**
     * @return unknown
     */
    public function getHideColumns() {
        return $this->hideColumns;
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
	 * cObj configuration
     * @return array
     */
    public function getRenderObj() {
        return $this->renderObj;
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
    	   } else {
    	   		return NULL;
    	   }
    	} else {
            return $this->settings;
    	}
    }
    
      
    
	/**
     * @return string
     */
    public function getInactiveOption() {
        return $this->inactiveOption;
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
	 * Sets the accessable flag.
	 * 
	 * @param bool $accessable
	 */
	public function injectAccessable($accessable) {
		$this->accessable = (bool)$accessable;
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