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
 * Interface for row structure
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\List
 */
interface Tx_PtExtlist_Domain_Model_List_RowInterface {

	/**
	 * @return array
	 */
	public function getColumnIdentifiers();


	/**
	 * Add a new cell to row identified by a given column name
	 *
	 * @param string $columnName
	 * @param Tx_PtExtlist_Domain_Model_List_Cell $cell
	 * @return void
	 */
	public function addCell(Tx_PtExtlist_Domain_Model_List_Cell $cell, $columnName);


	/**
	 * Create a new Cell with the Content and add it
	 *
	 * @param string $cellContent
	 * @param string $columnIdentifier
	 */
	public function createAndAddCell($cellContent, $columnIdentifier);



	/**
	 * @param string $columnIdentifier
	 * @return Tx_PtExtlist_Domain_Model_List_Cell
	 */
	public function getCell($columnIdentifier);



	/**
	 * Add a special value to the list
	 * @param string $key
	 * @param mixed $value
	 */
	public function addSpecialValue($key, $value);



	/**
	 * Get a special value
	 * @param string $key
	 */
	public function getSpecialValue($key);


	/**
	 * Return the complete value array
	 * @return array
	 */
	public function getSpecialValues();



	/**
	 * Setter for special values
	 * @param mixed $specialValues
	 */
	public function setSpecialValues($specialValues);



	/**
	 * Remove a special value from the list
	 * @param string $key
	 */
	public function removeSpecialValue($key);



	/**
	 * @return Tx_PtExtlist_Domain_Model_List_Cell $cell
	 */
	public function getFirstCell();



	/**
	 * Returns cell count of this row
	 *
	 * This is a helper method for fluid, as count is prefixed with 'get'
	 *
	 * @return int
	 */
	public function getCount();

}
