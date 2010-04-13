<?php

class Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig extends tx_pttools_objectCollection {

	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig';
	
	protected $filterBoxIdentifier;
	
	
	public function setFilterBoxIdentifier($filterBoxIdentifier) {
		$this->filterBoxIdentifier = $filterBoxIdentifier;
	}
	
	
	
	public function getFilterBoxIdentifier() {
		return $this->filterBoxIdentifier;
	}
	
	
}

?>