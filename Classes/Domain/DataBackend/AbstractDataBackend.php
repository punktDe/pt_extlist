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
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_DataConfiguration
	 */
	protected $configuration;
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Query_QueryBuilderInterface
	 */
	protected $queryBuilder;
	
	
	public function __construct(Tx_PtExtlist_Domain_Configuration_DataConfiguration &$configuration) {
		$this->configuration = $configuration;
		
		$this->initQueryBuilder();
	}

	protected abstract function initQueryBuilder();

	public function injectMapper(Tx_PtExtlist_Domain_DataBackend_MapperInterface &$mapper) {
		$this->mapper = $mapper;
	}
	
	public function injectDataSource(Tx_PtExtlist_Domain_DataBackend_DataSourceInterface &$dataSource) {
		$this->dataSource = $dataSource;
	}
}

?>