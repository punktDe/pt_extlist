<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
*
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/



/**
 * Abstract class as base class for all data backends
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
abstract class Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend implements Tx_PtExtlist_Domain_DataBackend_DataBackendInterface {
	
	/**
	 * @var string List identifier of list this data backend belongs to
	 */
	protected $listIdentifier;
	
	
	/**
	 * 
	 * @var Tx_PtExtlist_Domain_DataBackend_MapperInterface
	 */
	protected $dataMapper;
	
	
	
	/**
	 * @var Tx_PtExtlist_Domain_DataBackend_DataSourceInterface
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
	
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollection
	 */
	protected $filterBoxCollection;
	
	
	// TODO replace with actual class name etc
	protected $pager;
	
	
	// TODO think about sorting(s)/collection
	#protected $sorting
	
	
	
	/**
	 * Constructor for data backend
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->configurationBuilder = $configurationBuilder;
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
	}

	
	
	/**
	 * Injector for data mapper
	 *
	 * @param Tx_PtExtlist_Domain_DataBackend_MapperInterface $mapper
	 */
	public function injectDataMapper(Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $mapper) {
		$this->dataMapper = $mapper;
	}
	
	
	
	/**
	 * Injector for data source
	 *
	 * @param mixed $dataSource
	 */
	public function injectDataSource($dataSource) {
		$this->dataSource = $dataSource;
	}
	
	
	
	/**
	 * Injector for filter box collection
	 *
	 * @param Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollection $filterBoxCollection
	 */
	public function injectFilterBoxCollection(Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollection $filterBoxCollection) {
		$this->filterBoxCollection = $filterBoxCollection;
	}
	
	
	
	// TODO think about pager ;)
	public function injectPager() {
		
	}
	
}

?>