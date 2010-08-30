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
 * Class implements a row for a list data structure. Row contains
 * cells addressed by a identifier (column name).
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @author Michael Knoll
 * @author Christoph Ehscheidt
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Model_List_Row extends tx_pttools_collection {
	
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
			Throw new Exception('No Cell with ColumnIdentifier ' . $columnIdentifier . ' found in Row! 1282978972');
		}
		return $this->getItemById($columnIdentifier);
	}
}

?>