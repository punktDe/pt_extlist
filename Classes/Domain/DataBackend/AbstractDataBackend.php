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
 * @package Domain
 * @subpackage DataBackend
 * @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
 */
abstract class Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend implements Tx_PtExtlist_Domain_DataBackend_DataBackendInterface {
	
	/**
	 * Holds backend configuration for current backend
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration
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
	 * @var Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 */
	protected $listHeader;
	
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Model_List_ListData
	 */
	protected $listData;
	
	
	
    /**
     * @var Tx_PtExtlist_Domain_Model_Pager_PagerCollection
     */
	protected $pagerCollection;
	
	
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

	
    
    /**
     * Holds an instance of a query interpreter to be used for
     * query objects
     *
     * @var Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter
     */
    protected $queryInterpreter;
    
    
    
    /**
     * Holds an instance of bookmark manager
     *
     * @var Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager
     */
    protected $bookmarkManager;

	
		
	/**
	 * Per default, a data backend does not require a data source, so we return null here
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
	 * @param Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration $backendConfiguration
	 */
    public function injectBackendConfiguration(Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration $backendConfiguration) {
        $this->backendConfiguration = $backendConfiguration;
    }
	
    
    
    /**
     * Init method
     * 
     */
    public function init() {
    	$this->initBackendByTsConfig();
    	$this->initBackend();
    }
    
    
    
    /**
     * Init method to be overwritten in the 
     * concrete backends
     * 
     */
    protected function initBackend() {}    
    
    
    /**
     * Init the backend specific configuration from TS config
     * 
     */
    protected function initBackendByTsConfig() {}	
    
	
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
	public function injectFilterboxCollection(Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection $filterboxCollection) {
		$this->filterboxCollection = $filterboxCollection;
	}
	
	
	
	/**
	 * Injector for pager collection
	 * 
	 * @param Tx_PtExtlist_Domain_Model_Pager_PagerCollection $pagerCollection
	 */	
	public function injectPagerCollection(Tx_PtExtlist_Domain_Model_Pager_PagerCollection $pagerCollection) {
		$this->pagerCollection = $pagerCollection;
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
	 * Injector for List Header 
	 * 
	 * @param $listHeader Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 */
	public function injectListHeader(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader) {
		$this->listHeader = $listHeader;
	}
    
    
    
    /**
     * Injector for query interpreter
     *
     * @param Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter $queryInterpreter
     */
    public function injectQueryInterpreter(Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter $queryInterpreter) {
        $this->queryInterpreter = $queryInterpreter;
    }
    
    
    
    /**
     * Injector for bookmark manager
     *
     * @param Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager $bookmarkManager
     */
    public function injectBookmarkManager(Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager $bookmarkManager) {
    	$this->bookmarkManager = $bookmarkManager;
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
	 * Returns the pager collection attached to this data backend.
	 * 
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerCollection The pager collection attached to this data backend.
	 */
	public function getPagerCollection() {
		return $this->pagerCollection;
	}
	
	

	/**
	 * Returns the listHeader with sorting informations
	 * @return Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 */
	public function getListHeader() {
		return $this->listHeader;
	}
	
	
	/**
	 * @see Classes/Domain/DataBackend/Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::getListData()
	 */
	public function getListData() {
		return $this->listData;
	}
	
	
	/**
	 * Returns associated field config collection
	 *
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	public function getFieldConfigurationCollection() {
		return $this->fieldConfigurationCollection;
	}
	
}

?>