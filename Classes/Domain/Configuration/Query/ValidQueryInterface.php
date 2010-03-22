<?php

interface Tx_PtExtlist_Domain_Configuration_Query_ValidQueryInterface {
	
	/**
	 * Checks if the query configuration is valid.
	 * @return true id valid.
	 */
	public function isValid();
}

?>