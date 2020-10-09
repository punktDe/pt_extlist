<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
 *  All rights reserved
 *
 *  For further information: http://extlist.punkt.de <extlist@punkt.de>
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

use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Base testcase for testing data backends
 *
 * @author Michael Knoll
 * @package Tests
 * @subpackage Domain\DataBackend
 */
abstract class Tx_PtExtlist_Tests_Domain_DataBackend_AbstractDataBackendBaseTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * Holds configuration string for demo TS setup
     *
     * @var string
     */
    protected $tsConfigString;


    /**
     * Holds array with demo ts config
     *
     * @var unknown_type
     */
    protected $tsConfig;


    /**
     * Holds an instance of TS parser
     *
     * @var \TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser
     */
    protected $typoScriptParser;


    /**
     * Holds an instance of extlist configuration builder
     *
     * @var ConfigurationBuilder
     */
    protected $configurationBuilder;


    /**
     * Setup test by loading some demo ts settings
     */
    public function setup()
    {
        $this->tsConfigString =
            "plugin.tx_ptextlist.settings {
			
			    prototype {
			    
			       pager.pagerConfigs.default.pagerClassName = DefaultPager
	   			   pager.pagerConfigs.default.enabled = 1
	   			   pager.pagerConfigs.default.templatePath = EXT:pt_extlist/
			    
			    }
			
			    # This comes from flexform!
			    listIdentifier = list1
			
			    listConfig.list1 {
			    
			        backendConfig {
			
			            dataBackendClass = MySqlDataBackend_MySqlDataBackend
			            dataSourceClass = DataSource_Typo3DataSource
			            dataMapperClass = Mapper_ArrayMapper
			            queryInterpreterClass = MySqlDataBackend_MySqlInterpreter_MySqlInterpreter
			            
			            datasource {
			                host = " . TYPO3_db_host . "
			                username = " . TYPO3_db_username . "
			                password = " . TYPO3_db_password . "
			                database = " . TYPO3_db . "
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
			            
			            useEnableFields = 1
			            
			        }
			        
			        fields {
			        	fieldIndentifier1 {
			        		table = table1
			        		field = field1
			        	}
			        }
			   }
			}";
        $this->typoScriptParser = GeneralUtility::makeInstance(TypoScriptParser::class);
        $this->typoScriptParser->parse($this->tsConfigString);
        $this->tsConfig = GeneralUtility::makeInstance(TypoScriptService::class)->convertTypoScriptArrayToPlainArray($this->typoScriptParser->setup);
        $this->configurationBuilder = new ConfigurationBuilder($this->tsConfig['plugin']['tx_ptextlist']['settings'], 'list1');
    }
}
