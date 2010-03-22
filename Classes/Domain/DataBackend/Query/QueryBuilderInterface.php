<?php

interface Tx_PtExtlist_Domain_DataBackend_Query_QueryBuilderInterface {
	/**
	 * Builds a query object from the query configuration.
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_QueryConfiguration $configuration
	 * @return Tx_PtExtlist_Domain_Query_QueryInterface A query object
	 */
	public function buildQuery(Tx_PtExtlist_Domain_Configuration_QueryConfiguration $configuration);
}

?>