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
	 * @var array
	 */
	protected $stdWrap;
	
	
	protected $renderObj;
	
	/**
	 * @param $columnSettings array of coumn settings
	 * @return void
	 * @author Daniel Lienert <lienert@punkt.de>
	 */
	public function __construct(array $columnSettings) {
		tx_pttools_assert::isNotEmptyString($columnSettings['columnIdentifier'], array(message => 'Column identifier not given 1277889446'));
		tx_pttools_assert::isNotEmptyString($columnSettings['fieldIdentifier'], array(message => 'Field identifier not given 1277889447'));
		
		$this->columnIdentifier = $columnSettings['columnIdentifier'];
		$this->fieldIdentifier = $columnSettings['fieldIdentifier'];
		
		if(array_key_exists('label', $columnSettings)) {
			$this->label = $columnSettings['label'];
		} else {
			$this->label = $this->columnIdentifier;
		}
		
		if(array_key_exists('stdWrap', $columnSettings) && is_array($columnSettings['stdWrap'])) {
			$this->stdWrap = $columnSettings['stdWrap'];
		}
		if(array_key_exists('renderObj', $columnSettings) && is_array($columnSettings['renderObj'])) {
			$this->renderObj = $columnSettings['renderObj'];
		}
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
}
?>