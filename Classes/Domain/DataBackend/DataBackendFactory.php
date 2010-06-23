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
		if (!array_key_exists($configurationBuilder->getListIdentifier(),self::$instances)) {
	        $dataBackendSettings = $configurationBuilder->getBackendConfiguration();
	        tx_pttools_assert::isNotEmptyString($dataBackendSettings['dataBackendClass'], array('message' => 'dataBackendClass must not be empty!'));   
	        
	        $dataBackendClassName = $dataBackendSettings['dataBackendClass'];
	        
	        // Check whether backend class exists
	        tx_pttools_assert::isTrue(class_exists($dataBackendClassName), array('message' =>' Data Backend class ' . $dataBackendClassName . ' does not exist!'));
	        $dataBackend = new $dataBackendClassName($configurationBuilder);
	        
	        // Check whether backend class implements abstract backend class
	        tx_pttools_assert::isTrue($dataBackend instanceof Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend , 
	            array( 'message' => 'Data Backend class ' . $dataBackendClassName . ' does not implement Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend'));

	        $dataBackend = self::injectDataMapper($dataBackend, $configurationBuilder);
	        $dataBackend = self::injectDataSource($dataBackend, $configurationBuilder);
	        
	        self::$instances[$configurationBuilder->getListIdentifier()] = $dataBackend;
		}
		return self::$instances[$configurationBuilder->getListIdentifier()];
	}
	
	
	
	/**
	 * Initializes the data source used for this data backend
	 *
	 * @param Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend $dataBackend
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
    protected static function injectDataSource(Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend $dataBackend, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
         $dataSource = Tx_PtExtlist_Domain_DataBackend_DataSource_DataSourceFactory::createDataSource($configurationBuilder);
         $dataBackend->injectDataSource($dataSource);
         return $dataBackend;
    }
    
    
    
    /**
     * Injects the data mapper used for created backend
     *
     * @param Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend $dataBackend
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     */
    protected function injectDataMapper(Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend $dataBackend, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
        $dataMapper = Tx_PtExtlist_Domain_DataBackend_Mapper_MapperFactory::createDataMapper($configurationBuilder);
        // TODO this configuration might not be set!
        // TODO is this a good place to set configuration?!?
        $mapperConfiguration = $configurationBuilder->buildFieldsConfiguration(); 
        $dataMapper->setMapperConfiguration($mapperConfiguration);
        $dataBackend->injectDataMapper($dataMapper);
        return $dataBackend;
    }
	
}

?>