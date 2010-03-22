<?php

class Tx_PtExtlist_Domain_DataBackend_MySqlBackend_MySqlDataBackend extends Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend {
	
	protected function initQueryBuilder() {
		$this->queryBuilder = new Tx_PtExtlist_Domain_DataBackend_Query_MySqlQueryBuilder();
	}
	
	public function getListStructure() {
		$query = $this->queryBuilder->buildQuery($this->configuration->getQueryConfiguration());
//		var_dump($this->configuration->getQueryConfiguration());
		var_dump($query);
			//$query = null;
			
			//$res = $this->dataSource->execute($query);
			
			//$structure = $this->mapper->map($res);
			
			//return $structure;
	}
}

?>