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
 * Class Field Config
 *
 * @package pt_extlist
 * @subpackage Domain\Configuration\Data\Fields 
 * @author Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig {
	
	/**
	 * Field Identifier
	 * @var string
	 */
	protected $identifier;
	
	
	/**
	 * Table name
	 * @var string
	 */
	protected $table;
	
	
	/**
	 * Field name
	 * @var string
	 */
	protected $field;
	
	
	/**
	 * Determines if this column is sortable or not
	 * @var bool
	 */
	protected $isSortable = true;
	
	
	/**
	 * Defines access on this column on usergroup level
	 * 
	 * @var array
	 */
	protected $accessGroups = array();
	
	
	/**
	 * 
	 * Special string to be interpreted by the queryinterpreter
	 * @var string
	 */
	protected $special;
	
	
	/**
	 * Indicates if the field is expanded to an array of values 
	 * if the row is grouped (e.g. by group by clause in SQL) 
	 * @var boolean
	 */
	protected $expandGroupRows = false;
	
	
	
	public function __construct($identifier, $fieldSettings) {
		tx_pttools_assert::isNotEmptyString($identifier, array('message' => 'No identifier specified. 1277889450'));
		
		if(!trim($fieldSettings['special']) && (!trim($fieldSettings['table']) || !trim($fieldSettings['field']))) {
			throw new Exception('Either a table and a field or a special string has to be given! 1281353522');
		}
		
		$this->identifier = $identifier;
		$this->table = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($fieldSettings['table']);
		$this->field = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($fieldSettings['field']);
		$this->special = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($fieldSettings['special']);
		
		if (array_key_exists('isSortable', $fieldSettings)) {
			$this->isSortable = $fieldSettings['isSortable'] == 1 ? true : false;
		}
		
		if (array_key_exists('accessGroups', $fieldSettings)) {
			$this->accessGroups = t3lib_div::trimExplode(',', $fieldSettings['accessGroups']);
		}
		
		if (array_key_exists('expandGroupRows', $fieldSettings)) {
			$this->expandGroupRows = $fieldSettings['expandGroupRows'] == 1 ? true : false;
		}
	}

	
	/* 
	 * method to be comatible with structures using fieldIdentifier as array of strings
	 * TODO - all objects should use fieldConfigCollections 
	 */ 
	public function __toString() {
		return $this->identifier;
	}
	
	
	public function getIdentifier() {
		return $this->identifier;
	}
	
	
	
	public function getTable() {
		return $this->table;
	}
	
	
	
	public function getField() {
		return $this->field;
	}
	
	
	
	public function getTableFieldCombined() {
		return $this->table . '.' . $this->field;
	}
	
	
	
	public function getIsSortable() {
		return $this->isSortable;
	}
	
	
	
	public function getAccessGroups() {
		return $this->accessGroups;
	}
	
	
	
	public function getSpecial() {
		return $this->special;
	}
	
	
	
	public function getExpandGroupRows() {
		return $this->expandGroupRows;
	}
	
}


?>