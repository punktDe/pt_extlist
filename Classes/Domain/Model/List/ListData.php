<?php

// TODO --> ext_autoload.php !!!
#require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_objectCollection.php';


/**
 * Class implements list data object containing rows for a list.
 * 
 * @author Michael Knoll
 * @author Daniel Lienert
 * @author Christoph Ehscheidt
 */
class Tx_PtExtlist_Domain_Model_List_ListData extends tx_pttools_objectCollection {
	
	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Model_List_Row';
	
	/**
	 * Adds a row to list data
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Row $row   Row to be added to list data
	 * @return void
	 */
	public function addRow(Tx_PtExtlist_Domain_Model_List_Row $row) {
		$this->addItem($row);
	}
	
}

?>