<?php

/**
 * Class implementing container for all list elements, e.g. listData, filters, column descriptions...
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Model_List_List {
	
	/**
	 * Holds a reference of the list data object holding all list data
	 * @var Tx_PtExtlist_Domain_Model_Data_List_ListData
	 */
	protected $listData;

    /**
     * Getter for list data. 
     *
     * @return Tx_PtExtlist_Domain_Model_List_ListData      Returns list data of this list
     */	
	public function getListData() {
		return $this->listData;
	}
	
	/**
	 * Setter for list data
	 * @param Tx_PtExtlist_Domain_Model_List_ListData  $listData   List data to be set for this list object
	 */
	public function setListData(Tx_PtExtlist_Domain_Model_List_ListData $listData) {
		$this->listData = $listData;
	}
	
}

?>