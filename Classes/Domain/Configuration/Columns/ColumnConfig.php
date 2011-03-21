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
 * Column Config Object 
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Configuration\Columns
 */
class Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration
															 implements Tx_PtExtlist_Domain_Configuration_ColumnConfigInterface {

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
	 * (non-PHPdoc)
	 * @see Tx_PtExtbase_Configuration_AbstractConfiguration::init()
	 */
	protected function init() {
		$this->setRequiredValue('columnIdentifier', 'Column identifier not given 1277889446');
		$this->setRequiredValue('fieldIdentifier', 'Field identifier for Column "'.$this->columnIdentifier.'" not given 1277889447');
		
		$fieldIdentifierList = t3lib_div::trimExplode(',', $this->settings['fieldIdentifier']);
		$this->fieldIdentifier = $this->configurationBuilder->buildFieldsConfiguration()->extractCollectionByIdentifierList($fieldIdentifierList);
		
		foreach($this->fieldIdentifier as $fieldConfig) {
			if($fieldConfig->getExpandGroupRows()) {
				$this->containsArrayData = true;
				break;
			}
		}
		
		$this->label = $this->columnIdentifier;

		$this->setBooleanIfExistsAndNotNothing('isSortable');
		$this->setValueIfExistsAndNotNothing('renderTemplate');
		$this->setValueIfExistsAndNotNothing('sortingImageAsc');
		$this->setValueIfExistsAndNotNothing('sortingImageDesc');
		$this->setValueIfExistsAndNotNothing('sortingImageDefault');
		$this->setValueIfExistsAndNotNothing('specialCell');
		$this->setValueIfExistsAndNotNothing('cellCSSClass');
		$this->setValueIfExistsAndNotNothing('label');
		
		if(array_key_exists('renderUserFunctions', $this->settings) && is_array($this->settings['renderUserFunctions'])) {
			asort($this->settings['renderUserFunctions']);
			$this->renderUserFunctions = $this->settings['renderUserFunctions'];
		}
	
		if(array_key_exists('renderObj', $this->settings)) {
        	$this->renderObj = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray(array('renderObj' => $this->settings['renderObj']));
        }

		if(array_key_exists('sorting', $this->settings) && trim($this->settings['sorting'])) {
			$this->sortingConfigCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceBySortingSettings($this->settings['sorting']);
		}else{
			$this->sortingConfigCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceByFieldConfiguration($this->fieldIdentifier);
		}

		if(array_key_exists('accessGroups', $this->settings)) {
			$this->accessGroups = t3lib_div::trimExplode(',',$this->settings['accessGroups']);
		}
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