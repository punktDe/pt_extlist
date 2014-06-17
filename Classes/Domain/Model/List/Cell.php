<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Class implements a cell of a row of list data
 * 
 * @package Domain
 * @subpackage Model\List
 * @author Michael Knoll 
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Domain_Model_List_Cell {

	/**
	 * Holds value of cell
	 *
	 * @var mixed
	 */
	protected $value;
	
	
	
	/**
	 * Special values for multiple purpose
	 *
	 * @var array
	 */
	protected $specialValues;
	
	
	
	/**
	 * Holds index of row (number of row in list)
	 *
	 * @var int
	 */
	protected $rowIndex;
	
	
	
	/**
	 * Holds index of column (number of column in row)
	 *
	 * @var int
	 */
	protected $columnIndex;


	
	/**
	 * Individual cell class
	 * 
	 * @var string
	 */
	protected $cssClass;


	
	/**
	 * Constructor for cell object
	 *
	 * @param string $value
	 */
	public function __construct($value = '') {
		$this->value = $value;
	}
	
	
	
	/**
	 * Setter for cell value
	 *
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}
	
	
	
	/**
	 * Getter for cell value
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}
	
	
	
	/**
	 * Add a special value to the list
	 * @param string $key
	 * @param mixed $value
	 */
	public function addSpecialValue($key, $value) {
		$this->specialValues[$key] = $value;
	}
	
	
	
	/**
	 * Get a special value from the list
	 * @param string $key
	 */
	public function getSpecialValue($key) {
		return $this->specialValues[$key];
	}	
	
	
	
	/**
	 * Return the complete value array
	 */
	public function getSpecialValues() {
		return $this->specialValues;
	}
	
	
	
	/**
	 * Remove a special value from the list
	 * @param string $key
	 */
	public function removeSpecialValue($key) {
		unset($this->specialValues[$key]);
	}
	
	
	
	/**
	 * Setter for row index
	 *
	 * @param int $rowIndex
	 */
	public function setRowIndex($rowIndex) {
		$this->rowIndex = $rowIndex;
	}
	
	
	
	/**
	 * Getter for row index
	 *
	 * @return int
	 */
	public function getRowIndex() {
		return $this->rowIndex;
	}
	
	
	
	/**
	 * Setter for column index
	 *
	 * @param int $columnIndex
	 */
	public function setColumnIndex($columnIndex) {
		$this->columnIndex = $columnIndex;
	}
	
	
	
	/**
	 * Getter for column index
	 *
	 * @return int
	 */
	public function getColumnIndex() {
		return $this->columnIndex;
	}

	
	
	/**
	 * set the individual cell CSS class
	 * 
	 * @param string $cssClass
	 */
	public function setCSSClass($cssClass) {
		$this->cssClass = $cssClass;
	}

	
	
	/**
	 * get the individual cell CSS class
	 * @return string 	 
	 */
	public function getCSSClass() {
		return $this->cssClass;
	}


	
	/**
	 * Returns object value as string
	 *
	 * @return string
	 */
	public function __toString() {

		switch (true) {
			case is_object($this->value):
				return 'OBJECT::' . get_class($this->value);

			case is_array($this->value):
				return implode(', ', $this->value);

			case is_int($this->value):
				return (string)$this->value;

			case !$this->value:
				return '';

			default:
				return (string)$this->value;
		}
	}


	/**
	 * array(
	 * 	'value' =>
	 * 	'cssClass' =>
	 *  'rowIndex' =>
	 *  'columnIndex' =>
	 *  'specialValues' => array()
	 * )
	 *
	 * @param $dataArray
	 */
	public function setByArray($dataArray) {
		$internalVars = get_object_vars($this);

		foreach($internalVars as $key => $value) {
			if(array_key_exists($key, $dataArray)) {
				$this->$key = $dataArray[$key];
			} else {
				unset($this->$key);
			}
		}
	}



	/**
	 * @return array
	 */
	public function getAsArray() {
		$internalVars = get_object_vars($this);
		$returnArray = array();

		foreach($internalVars as $key => $value) {
			$returnArray[$key] = $value;
		}

		return $returnArray;
	}
}
?>