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
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig {
	
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
	 * @var string
	 */
	protected $fieldIdentifier;
	
	/** 
	 * @var string
	 */
	protected $label;
	
	/**
	 * @var boolean
	 */
	protected $isSortable = true;
	
	/**
	 * @var array
	 */
	protected $stdWrap = NULL;
	
	protected $renderObj;

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
	 * @param $columnSettings array of coumn settings
	 * @return void
	 * @author Daniel Lienert <lienert@punkt.de>
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, array $columnSettings) {
		tx_pttools_assert::isNotEmptyString($columnSettings['columnIdentifier'], array(message => 'Column identifier not given 1277889446'));
		tx_pttools_assert::isNotEmptyString($columnSettings['fieldIdentifier'], array(message => 'Field identifier not given 1277889447'));
		
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
		$this->columnIdentifier = $columnSettings['columnIdentifier'];
		$this->fieldIdentifier = $columnSettings['fieldIdentifier'];
		
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
			$this->label = $columnSettings['label'];
		}
		
		if(array_key_exists('stdWrap', $columnSettings) && is_array($columnSettings['stdWrap'])) {
			$this->stdWrap = $columnSettings['stdWrap'];
		}
		
		if(array_key_exists('renderObj', $columnSettings) && is_array($columnSettings['renderObj'])) {
			$this->renderObj = $columnSettings['renderObj'];
		}
		
		if(array_key_exists('sorting', $columnSettings)) {
			$this->sortingConfigCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceBySortingSettings($columnSettings['sorting']);
		}else{
			$this->sortingConfigCollection = Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory::getInstanceByFieldConfiguration($this->fieldIdentifier);
		}
	}
	
	
	
	/**
	 * @return string listIdentifier
	 * @author Daniel Lienert <lienert@punkt.de>
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
	
	/**
	 * @return string columnIdentifier
	 * @author Daniel Lienert <lienert@punkt.de>
	 */
	public function getColumnIdentifier() {
		return $this->columnIdentifier;
	}
	
	
	/**
	 * @return string fieldIdentifier
	 * @author Daniel Lienert <lienert@punkt.de>
	 */
	public function getFieldIdentifier() {
		return $this->fieldIdentifier;
	} 
	
	/**
	 * @return string label
	 * @author Daniel Lienert <lienert@punkt.de>
	 */
	public function getLabel() {
		return $this->label;
	}	
	
	public function getIsSortable() {
		return (bool)$this->isSortable;
	}
	
	/**
	 * 
	 * @return array stdWrap
	 */
	public function getStdWrap() {
		return $this->stdWrap;	
	}
	
	public function getRenderObj() {
		return $this->renderObj;
	}
	
	/** 
	 * @return Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollection
	 * @author Daniel Lienert <lienert@punkt.de>
	 */
	public function getSortingConfig() {
		return $this->sortingConfigCollection;
	}
}
?>