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
	 * Holds backend configuration for current backend
	 *
	 * @var array
	 */
	protected $backendConfiguration;
	
	
	
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
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;
	
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Query_QueryBuilderInterface
	 */
	protected $queryBuilder;
	
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection
	 */
	protected $filterboxCollection;
	
	
	
    /**
     * @var Tx_PtExtlist_Domain_Model_Pager_PagerInterface
     */
	protected $pager;
	
	
	
	/**
	 * Holds an instance for data source
	 *
	 * @var mixed
	 */
	protected $dataSource;
	
	
	
	/**
	 * Holds an instance of a field collection where field configurations can be found
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	protected $fieldConfigurationCollection;
	
	
	
	// TODO think about sorting(s)/collection
	#protected $sorting
	
	protected $observers = array();
	
	
	/**
	 * Per default, a data backend does not require a data source, so we return null here
	 * 
	 * // TODO think about this
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return unknown
	 */
	public static function createDataSource(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		return null;
	}
	
	
	
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
	 * Injects backend configuration for current backend
	 *
	 * @param array $backendConfiguration
	 */
    public function injectBackendConfiguration($backendConfiguration) {
        $this->backendConfiguration = $backendConfiguration;
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
	 * Injector for field configuration collection
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection $fieldConfigurationCollection
	 */
	public function injectFieldConfigurationCollection(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection $fieldConfigurationCollection) {
		$this->fieldConfigurationCollection = $fieldConfigurationCollection;
	}
	
	
	
	/**
	 * Injector for filter box collection
	 *
	 * @param Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection $filterboxCollection
	 */
	public function injectfilterboxCollection(Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection $filterboxCollection) {
		$this->filterboxCollection = $filterboxCollection;
	}
	
	
	
	/**
	 * Injector for pager
	 * 
	 * @param Tx_PtExtlist_Domain_Model_Pager_PagerInterface $pager
	 */
	public function injectPager(Tx_PtExtlist_Domain_Model_Pager_PagerInterface $pager) {
		$this->pager = $pager;
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
	 * Register a new observer object.
	 * 
	 * @param Tx_PtExtlist_Domain_DataBackend_DataSource_DataSourceObserverInterface $obs The obeserver object to register
	 */
	public function registerObserver(Tx_PtExtlist_Domain_DataBackend_DataBackendObserverInterface $obs) {
		$this->observers[] = $obs;
	}
	
	
	
	/**
	 * Updates the item count in each observer object.
	 * 
	 * @param int $itemCount The new item count.
	 */
	protected function updateObserversItemCount($itemCount) {
		foreach($this->observers as $observer) {
			
			$observer->updateItemCount($itemCount);
		}
	}
	
	
	/**
	 * Returns filterbox collection attached to this data backend
	 *
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection Filterbox collection attached to this data backend
	 */
	public function getFilterboxCollection() {
		return $this->filterboxCollection;
	}
	
	
	
	/**
	 * 
	 * Returns the pager attached to this data backend.
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerInterface The pager attached to this data backend.
	 */
	public function getPager() {
		return $this->pager;
	}
	

}

?>