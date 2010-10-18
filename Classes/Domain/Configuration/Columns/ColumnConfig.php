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
 * Column Config Object 
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package pt_extlist
 * @subpackage Domain\Configuration\Columns
 */
class Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig implements Tx_PtExtlist_Domain_Configuration_ColumnConfigInterface {
	
	/**
	 * Identifier of list to which this column belongs to
	 *
	 * @var string
	 */
	protected $listIdentifier;
	
	/** 
	 * @var string
	 */
	protected $columnIdentifier;

	/** 
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	protected $fieldIdentifier;
	
	/** 
	 * @var string
	 */
	protected $label;
	
	/** 
	 * @var array
	 */
	protected $accessGroups;	
	
	/**
	 * @var boolean
	 */
	protected $isSortable = true;
	
	/**
	 * @var array
	 */
	protected $renderUserFunctions = NULL;
	
	/**
	 * @var array
	 */
	protected $renderObj;
	
	
	/**
	 * Path to fluid template
	 * @var string
	 */
	protected $renderTemplate;
	
	
	/**
	 * @var string
	 */
	protected $specialCell = NULL;
	
	
	/**
	 * @var string
	 */
	protected $cellCSSClass = NULL;
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollection
	 */
	protected $sortingConfigCollection = NULL;
	
	
	/**
	 * Sortingstate of this column
	 * @var integer
	 */
	protected $sortingState = 0;
	
	
	/**
	 * Image to show as sorting link.
	 * @var string
	 */
	protected $sortingImageDefault = '';
	
	
	/**
	 * Image to show as sorting link.
	 * @var string
	 */
	protected $sortingImageAsc = '';
	
	
	/**
	 * Image to show as sorting link.
	 * @var string
	 */
	protected $sortingImageDesc = '';
	
	
	/**
	 * Says if this column is accessable by the current FE-User. Will be injected by the factory.
	 * 
	 * @var boolean
	 */
	protected $accessable = false;
	
	
	/**
	 * if one of this columns fields is a expanded GroupField, 
	 * this column has an array as dataStructure
	 * @var boolean
	 */
	protected $containsArrayData = false;
	
	
	/**
	 * @param $columnSettings array of coumn settings
	 * @return void
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, array $columnSettings) {
		tx_pttools_assert::isNotEmptyString($columnSettings['columnIdentifier'], array(message => 'Column identifier not given 1277889446'));
		tx_pttools_assert::isNotEmptyString($columnSettings['fieldIdentifier'], array(message => 'Field identifier for Column "'.$columnSettings['columnIdentifier'].'" not given 1277889447'));
		
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
		$this->columnIdentifier = $columnSettings['columnIdentifier'];
		
		$fieldIdentifierList = t3lib_div::trimExplode(',', $columnSettings['fieldIdentifier']);
		$this->fieldIdentifier = $configurationBuilder->buildFieldsConfiguration()->extractCollectionByIdentifierList($fieldIdentifierList);
		
		foreach($this->fieldIdentifier as $fieldConfig) {
			if($fieldConfig->getExpandGroupRows()) {
				$this->containsArrayData = true;
				break;
			}
		}
		
		$this->label = $this->columnIdentifier;

		
		$this->setOptionalSettings($columnSettings);
	}	
	
	
	/**
	 * @param boolean $accessable
	 */
	public function setAccessable($accessable) {
		$this->accessable = $accessable;
	}
	
	
	
	public function isAccessable() {
		return $this->accessable;
	}
	
	
	
	/**
	 * Set optional definable columnsettings
	 * 
	 * @param $columnSettings
	 * @return void
	 */
	protected function setOptionalSettings($columnSettings) {
		
		if(array_key_exists('isSortable', $columnSettings)) {
			$this->isSortable = $columnSettings['isSortable'];
		}
		
		if(array_key_exists('label', $columnSettings)) {
			$this->label = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($columnSettings['label']);
		}
		
		if(array_key_exists('renderUserFunctions', $columnSettings) && is_array($columnSettings['renderUserFunctions'])) {
			asort($columnSettings['renderUserFunctions']);
			$this->renderUserFunctions = $columnSettings['renderUserFunctions'];
		}
		
		if(array_key_exists('renderTemplate', $columnSettings)) {
			$this->renderTemplate = $columnSettings['renderTemplate'];
		}
				
		if(array_key_exists('renderObj', $columnSettings)) {
        	$this->renderObj = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray(array('renderObj' => $columnSettings['renderObj']));
        }

		if(array_key_exists('sorting', $columnSettings) && trim($columnSettings['sorting'])) {
			$this->sortingConfigCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceBySortingSettings($columnSettings['sorting']);
		}else{
			$this->sortingConfigCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceByFieldConfiguration($this->fieldIdentifier);
		}
		
		if(array_key_exists('sortingImageAsc', $columnSettings)) {
			$this->sortingImageAsc = $columnSettings['sortingImageAsc'];
		}
		
		if(array_key_exists('sortingImageDesc', $columnSettings)) {
			$this->sortingImageDesc = $columnSettings['sortingImageDesc'];
		}
		
		if(array_key_exists('sortingImageDefault', $columnSettings)) {
			$this->sortingImageDefault = $columnSettings['sortingImageDefault'];
		}
		
		if(array_key_exists('specialCell', $columnSettings)) {
			$this->specialCell = $columnSettings['specialCell'];
		}
		
		if(array_key_exists('accessGroups', $columnSettings)) {
			$this->accessGroups = t3lib_div::trimExplode(',',$columnSettings['accessGroups']);
		}
		
		if(array_key_exists('cellCSSClass', $columnSettings)) {
			$this->cellCSSClass = $columnSettings['cellCSSClass'];
		}
	}
	
	
	
	/**
	 * @return string listIdentifier
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
	
	
	
	/**
	 * @return string columnIdentifier
	 */
	public function getColumnIdentifier() {
		return $this->columnIdentifier;
	}
	
	
	
	/**
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection fieldIdentifier
	 */
	public function getFieldIdentifier() {
		return $this->fieldIdentifier;
	} 
	
	
	
	/**
	 * @return string label
	 */
	public function getLabel() {
		return $this->label;
	}	
	
	
	
	public function getIsSortable() {
		return (bool)$this->isSortable;
	}
	
	
	
	/**
	 * @return array
	 */
	public function getRenderObj() {
		return $this->renderObj;
	}
	
	/**
	 * @return array
	 */
	public function getRenderUserFunctions() {
		return $this->renderUserFunctions;
	}
	
	/** 
	 * @return Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollection
	 */
	public function getSortingConfig() {
		return $this->sortingConfigCollection;
	}
	
	/**
     * Return the default image path to show for sorting link.
     * @return string
     */
    public function getSortingImageDefault() {
    	return $this->sortingImageDefault;
    }
    
    /**
     * Return the ASC image path to show for sorting link.
     * @return string
     */
    public function getSortingImageAsc() {
    	return $this->sortingImageAsc;
    }
    
    /**
     * Return the DESC image path to show for sorting link.
     * @return string
     */
    public function getSortingImageDesc() {
    	return $this->sortingImageDesc;
    }
    
    /**
     * Returns the special cell user function path
     * @return string
     */
    public function getSpecialCell() {
    	return $this->specialCell;
    }
    
  	/**
     * Return array off groupIds
     * @return array
     */
    public function getAccessGroups() {
    	return $this->accessGroups;
    }
    
    
    /**
     * Indicates if the data for this columns cells are arrays
     * @return boolean 
     */
    public function getContainsArrayData() {
    	return $this->containsArrayData;
    }
    
    
    /**
     * @return string renderTemplate
     */
    public function getRenderTemplate() {
    	return $this->renderTemplate;
    }
    
    /**
     * @return string;
     */
    public function getCellCSSClass() {
    	return $this->cellCSSClass;
    }
    
}
?>