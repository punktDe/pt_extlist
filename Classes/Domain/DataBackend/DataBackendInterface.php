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
 * Interface for all data backends
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
interface Tx_PtExtlist_Domain_DataBackend_DataBackendInterface {

	/**
	 * Creates an instance of data source object to be used with current backend
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public static function createDataSource(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder);
	
	
	
	/**
	 * Returns mapped List structure
	 * 
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
    public function getListData();

    /**
	 * 
	 * Generates dummy list data and returns a wrapped list
	 * including header data.
	 * 
	 * @return Tx_PtExtlist_Domain_Model_List_List
	 */
	//public function getList();
    
    
    /**
     * Injector for pager
     *
     * @param Tx_PtExtlist_Domain_Model_Pager_PagerInterface $pager
     */
    public function injectPager(Tx_PtExtlist_Domain_Model_Pager_PagerInterface $pager);
    
    
    
    /**
     * Injector for data mapper
     *
     * @param Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $dataMapper
     */
    public function injectDataMapper(Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $dataMapper);
    
    
    
    /**
     * Injector for data source
     *
     * @param mixed $dataSource
     */
    public function injectDataSource($dataSource);
    
    
    
    /**
     * Injector for filterbox collection
     *
     * @param Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollection $filterboxCollection
     */
    public function injectFilterboxCollection(Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollection $filterboxCollection);
    
    
    
    /**
     * Injector for backend configuration
     *
     * @param array $backendConfiguration
     */
    public function injectBackendConfiguration($backendConfiguration);

}

?>