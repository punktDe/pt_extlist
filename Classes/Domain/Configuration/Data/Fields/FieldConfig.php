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
 * Class Field Config
 *
 * @package Domain
 * @subpackage Configuration\Data\Fields 
 * @author Daniel Lienert 
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration {
	
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


	/**
	 * Join and expand the groupRows by the following delimiter
	 * @var string
	 */
	protected $expandGroupRowsSeparator = '<extListSeparator>';
	
	
	
	/**
	 * (non-PHPdoc)
	 * @see Tx_PtExtbase_Configuration_AbstractConfiguration::init()
	 */
	protected function init() {
		$this->setRequiredValue('identifier', 'No field identifier specified. 1277889450');
		
		$this->table = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($this->settings['table']);
		$this->field = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($this->settings['field']);
		$this->special = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($this->settings['special']);
		
		if(!trim($this->special) && (!trim($this->table) || !trim($this->field))) {
			throw new Exception('Configuration error for field: "'.$this->identifier.'" Either a table and a field or a special string has to be given! 1281353522');
		}
		
		$this->setBooleanIfExistsAndNotNothing('isSortable');
		$this->setBooleanIfExistsAndNotNothing('expandGroupRows');

		$this->setValueIfExists('expandGroupRowsSeparator');
		
		if(array_key_exists('accessGroups', $this->settings)) {
			$this->accessGroups = t3lib_div::trimExplode(',', $this->settings['accessGroups']);
		}
	}
	
	
	
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $identifier, $settings) {
		
		$settings['identifier'] = $identifier;
		parent::__construct($configurationBuilder, $settings);
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


	/**
	 * @return string
	 */
	public function getExpandGroupRowsSeparator() {
		return $this->expandGroupRowsSeparator;
	}
}
?>