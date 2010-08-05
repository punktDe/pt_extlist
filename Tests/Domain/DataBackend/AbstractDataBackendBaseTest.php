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
 * Base testcase for testing data backends
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
abstract class Tx_PtExtlist_Tests_Domain_DataBackend_AbstractDataBackendBaseTest extends Tx_Extbase_BaseTestcase {
   
    /**
     * Holds configuration string for demo TS setup
     *
     * @var string
     */
    protected $tsConfigString =
"plugin.tx_ptextlist.settings {

    # This comes from flexform!
    listIdentifier = list1

    listConfig.list1 {
    
        backendConfig {

            dataBackendClass = Tx_PtExtlist_Domain_DataBackend_DummyDataBackend
            dataSourceClass = Tx_PtExtlist_Domain_DataBackend_DataSource_DummyDataSource
            dataMapperClass = Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper
            
            datasource {
                host = localhost
                username = typo3
                password = typo3
                database = typo3
            }
            
            tables (
                static_countries, 
                static_territories st_continent, 
                static_territories st_subcontinent
            )
            
            baseFromClause (
            )
            
            baseWhereClause (
            ) 
            
        }
}";
    
    
    
    /**
     * Holds array with demo ts config
     *
     * @var unknown_type
     */
    protected $tsConfig;
    
        
    
    /**
     * Holds an instance of TS parser
     *
     * @var t3lib_TSparser
     */
    protected $typoScriptParser;
    
    
    
    /**
     * Holds an instance of extlist configuration builder
     *
     * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
     */
    protected $configurationBuilder;
    
    
    
    /**
     * Setup test by loading some demo ts settings
     */
    public function setup() {
         $this->typoScriptParser = t3lib_div::makeInstance('t3lib_TSparser');
         $this->typoScriptParser->parse($this->tsConfigString);
         $this->tsConfig = Tx_Extbase_Utility_TypoScript::convertTypoScriptArrayToPlainArray($this->typoScriptParser->setup);
         $this->configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->tsConfig['plugin']['tx_ptextlist']['settings']);
    }
	
}
?>