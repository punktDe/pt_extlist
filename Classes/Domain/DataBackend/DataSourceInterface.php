<?php

interface Tx_PtExtlist_Domain_DataBackend_DataSourceInterface {
	
	public function execute(Tx_PtExtlist_Domain_Query_QueryInterface $query);
	
}

?>