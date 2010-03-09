<?php

interface Tx_PtExtlist_Domain_DataBackend_DataBackendInterface {

	/**
	 * Returns mapped List structure
	 * 
	 * @return Tx_PtExtlist_List_ListInterface
	 */
    public function getListStructure();

}

?>