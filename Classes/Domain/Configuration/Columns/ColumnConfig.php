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
class Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig implements Tx_PtExtlist_Domain_Configuration_RenderConfigInterface {
	
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
	 * @var array
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
	 * 
	 * @var string
	 */
	protected $specialCell = NULL;

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
	 * @param $columnSettings array of coumn settings
	 * @return void
	 * @author Daniel Lienert <lienert@punkt.de>
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, array $columnSettings) {
		tx_pttools_assert::isNotEmptyString($columnSettings['columnIdentifier'], array(message => 'Column identifier not given 1277889446'));
		tx_pttools_assert::isNotEmptyString($columnSettings['fieldIdentifier'], array(message => 'Field identifier for Column "'.$columnSettings['columnIdentifier'].'" not given 1277889447'));
		
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
		$this->columnIdentifier = $columnSettings['columnIdentifier'];
		$this->fieldIdentifier =  t3lib_div::trimExplode(',', $columnSettings['fieldIdentifier']) ;
		
		$this->label = $this->columnIdentifier;

		
		$this->setOptionalSettings($columnSettings);
	}	
	
	
	
	/**
	 * Set optional definable columnsettings
	 * 
	 * @param $columnSettings
	 * @return void
	 * @author Daniel Lienert <lienert@punkt.de>
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
				
		if(array_key_exists('renderObj', $columnSettings)) {
        	$this->renderObj = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray(array('renderObj' => $columnSettings['renderObj']));
        }
		
		if(array_key_exists('sorting', $columnSettings)) {
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
	 * @return string fieldIdentifier
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
    
}
?>