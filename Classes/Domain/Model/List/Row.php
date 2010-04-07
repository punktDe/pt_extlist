<?php

/**
 * Class implements a row for a list data structure. Row contains
 * cells addressed by a identifier (column name).
 * 
 * @author Daniel Lienert
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
	 * @param string $value
	 * @return void
	 */
	public function addCell($columnName, $value) {
		$this->addItem($value, $columnName);
	}
	
}

?>