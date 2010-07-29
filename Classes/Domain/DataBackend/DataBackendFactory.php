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
 * Factory for data backend objects
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_DataBackend_DataBackendFactory {
	
	/**
     * Holds an associative array of instances of data backend objects
     * Each list identifier holds its own data backend object
     * @var array<Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder>
     */
	private static $instances = null;
	
	
	
	/**
	 * Create new data backend object for given configuration
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend
	 */
	public static function createDataBackend(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		
		$listIdentifier = $configurationBuilder->getListIdentifier();
		
		if (!array_key_exists($listIdentifier, self::$instances)) {
	        $dataBackendSettings = $configurationBuilder->getBackendConfiguration();
	        tx_pttools_assert::isNotEmptyString($dataBackendSettings['dataBackendClass'], array('message' => 'dataBackendClass must not be empty! 1277889456'));   
	        
	        $dataBackendClassName = $dataBackendSettings['dataBackendClass'];
	        
	        // Check whether backend class exists
	        tx_pttools_assert::isTrue(class_exists($dataBackendClassName), array('message' =>' Data Backend class ' . $dataBackendClassName . ' does not exist! 1280400021'));
	        $dataBackend = new $dataBackendClassName($configurationBuilder); 
	        
	        // Check whether backend class implements abstract backend class
	        tx_pttools_assert::isTrue($dataBackend instanceof Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend, array( 'message' => 'Data Backend class ' . $dataBackendClassName . ' does not implement Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend 1280400022'));

	        
	        $dataSource = self::getDataSource($dataBackendClassName, $configurationBuilder);
	        $pager = self::getPager($configurationBuilder);
	        
	        //Register pager as datasource observer
	        // TODO pager must not be registered as an observer in data source!!!
	        #$dataSource->registerObserver($pager);
	        
	        $dataBackend->injectDataMapper(self::getDataMapper($configurationBuilder));
	        $dataBackend->injectDataSource($dataSource);
	        $dataBackend->injectPager($pager);
	        $dataBackend->injectFilterboxCollection(self::getfilterboxCollection($configurationBuilder));
	        
	        
	        self::$instances[$listIdentifier] = $dataBackend;
		}
		return self::$instances[$listIdentifier];
	}
	
	
	
	/**
	 * Initializes the data source used for this data backend
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
    protected static function getDataSource($dataBackendClassName, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	 // Use data backend class to create data source, as only backend knows which data source to use and how to configure it!
         $dataSource = call_user_func($dataBackendClassName . '::createDataSource', $configurationBuilder);
         print_r($dataSource);
         return $dataSource;
    }
    
    
    
    /**
     * Injects the data mapper used for created backend
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     */
    protected function getDataMapper(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
        $dataMapper = Tx_PtExtlist_Domain_DataBackend_Mapper_MapperFactory::createDataMapper($configurationBuilder);
        return $dataMapper;
    }
    
    
    
    /**
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection
     * @author Daniel Lienert <lienert@punkt.de>
     * @since 23.06.2010
     */
    protected function getfilterboxCollection(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	$filterboxCollection = Tx_PtExtlist_Domain_Model_Filter_FilterboxCollectionFactory::createInstance($configurationBuilder);
    	return $filterboxCollection;
    }
    
    
    
    /**
     * 
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Model_Pager_PagerInterface
     * @author Christoph Ehscheidt <ehscheidt@punkt.de>
     * @since 19.07.2010
     */
    protected function getPager(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	$pager = Tx_PtExtlist_Domain_Model_Pager_PagerFactory::getInstance($configurationBuilder, $configurationBuilder->buildPagerConfiguration());
    	return $pager;
    }
	
}

?>