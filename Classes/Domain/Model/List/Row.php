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
 * Class implements a row for a list data structure. Row contains
 * cells addressed by a identifier (column name).
 * 
 * @author Daniel Lienert 
 * @author Michael Knoll
 * @author Christoph Ehscheidt
 * @package Domain
 * @subpackage Model\List
 */
class Tx_PtExtlist_Domain_Model_List_Row extends Tx_PtExtbase_Collection_ObjectCollection {
	
	
	/**
	 * Special values for multiple purpose
	 * @var string
	 */
	protected $specialValues;
	
	
	
	/**
	 * Add a new cell to row identified by a given column name
	 * 
	 * @param string $columnName
	 * @param Tx_PtExtlist_Domain_Model_List_Cell $cell
	 * @return void
	 */
	public function addCell(Tx_PtExtlist_Domain_Model_List_Cell $cell, $columnName) {
		$this->addItem($cell, $columnName);
	}
	
	
	
	/**
	 * Create a new Cell with the Content and add it
	 * 
	 * @param string $cellContent
	 * @param string $columnName
	 */
	public function createAndAddCell($cellContent, $columnIdentifier) {
		$this->addItem(new Tx_PtExtlist_Domain_Model_List_Cell($cellContent), $columnIdentifier);
	}
	
	
	
	/**
	 * @param string $columnIdentifier
	 * @return Tx_PtExtlist_Domain_Model_List_Cell
	 */
	public function getCell($columnIdentifier) {
		if(!$this->hasItem($columnIdentifier)) {
			Throw new Exception('No Cell with Identifier "' . $columnIdentifier . '" found in Row. There are ('.implode(', ',array_keys($this->itemsArr)).')! 1282978972');
		}
		return $this->getItemById($columnIdentifier);
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
	 * Setter for special values
	 * @param mixed $specialValues
	 */
	public function setSpecialValues($specialValues) {
		$this->specialValues = $specialValues;
	}
	
	
	
	/**
	 * Remove a special value from the list
	 * @param string $key
	 */
	public function removeSpecialValue($key) {
		unset($this->specialValues[$key]);
	}
	
	
	
	/**
	 * @return Tx_PtExtlist_Domain_Model_List_Cell $cell
	 */
	public function getFirstCell() {
		reset($this->itemsArr);
		return current($this->itemsArr);
	}



	/**
	 * Returns cell count of this row
	 *
	 * This is a helper method for fluid, as count is prefixed with 'get'
	 *
	 * @return int
	 */
	public function getCount() {
		return $this->count();
	}

}
?>