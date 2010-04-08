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
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Query_QueryBuilderInterface
	 */
	protected $queryBuilder;
	
	
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->configurationBuilder = $configurationBuilder;
	}

	public function injectMapper(Tx_PtExtlist_Domain_DataBackend_MapperInterface &$mapper) {
		$this->mapper = $mapper;
	}
	
	public function injectDataSource(Tx_PtExtlist_Domain_DataBackend_DataSourceInterface &$dataSource) {
		$this->dataSource = $dataSource;
	}
}

?>