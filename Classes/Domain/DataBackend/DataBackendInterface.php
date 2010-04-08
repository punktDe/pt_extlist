<?php

interface Tx_PtExtlist_Domain_DataBackend_DataBackendInterface {

	/**
	 * Returns mapped List structure
	 * 
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
    public function getListData();

}

?>