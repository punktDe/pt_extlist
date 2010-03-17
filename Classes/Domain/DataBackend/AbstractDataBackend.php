<?php

abstract class Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend implements Tx_PtExtlist_Domain_DataBackend_DataBackendInterface {
	
	/**
	 * 
	 * @var x_PtExtlist_Domain_DataBackend_MapperInterface $mapper
	 */
	protected $mapper;
	
	/**
	 * @var Tx_PtExtlist_Domain_DataBackend_DataSourceInterface $dataSource
	 */
	protected $dataSource;
	

	
	public function injectMapper(Tx_PtExtlist_Domain_DataBackend_MapperInterface &$mapper) {
		$this->mapper = $mapper;
	}
	
	public function injectDataSource(Tx_PtExtlist_Domain_DataBackend_DataSourceInterface &$dataSource) {
		$this->dataSource = $dataSource;
	}
}

?>