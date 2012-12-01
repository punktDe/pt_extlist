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

/**
 * Class implements a base testcase for pt_extlist testcases
 *
 * TODO we should use pt_extbase base testcase to be extended here. Add some generic functions there!
 *
 * @package Tests
 * @author Michael Knoll 
 */
abstract class Tx_PtExtlist_Tests_BaseTestcase extends Tx_Extbase_Tests_Unit_BaseTestCase {

	protected $extBaseSettings = array();
    
    
    
    protected $extBaseSettingsString = '
    plugin.tx_ptextlist.settings.persistence.storagePid = 12
    
    extensionName = PtExtlist
    pluginName = pi1
    controller = List
    action = list
    switchableControllerActions {
       10 {
           controller = List
           action = list
       }
    
    }
    
    # Required for requestBuilder
    
    persistence{
        enableAutomaticCacheClearing = 1
        updateReferenceIndex = 0
        classes {
            Tx_Extbase_Domain_Model_FrontendUser {
                mapping {
                    tableName = fe_users
                    recordType = Tx_Extbase_Domain_Model_FrontendUser
                    columns {
                        lockToDomain.mapOnProperty = lockToDomain
                    }
                }
            }
            Tx_Extbase_Domain_Model_FrontendUserGroup {
                mapping {
                    tableName = fe_groups
                    recordType =
                    columns {
                        lockToDomain.mapOnProperty = lockToDomain
                    }
                }
            }
        }
    }';
	
    
    
	/**
	 * Holds a configuration builder mock for testcase
	 * @var Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock
	 */
	protected $configurationBuilderMock = null;
	
	
	
	/**
	 * Holds settings for testcase setup
	 * @var array
	 */
	protected $settings = array();
	
	
	
	public function setup() {
		$typoScriptParser = t3lib_div::makeInstance('t3lib_TSparser'); /* @var $typoScriptParser t3lib_TSparser */
        $typoScriptParser->parse($this->extBaseSettingsString);
        $this->extBaseSettings = Tx_PtExtbase_Compatibility_Extbase_Service_TypoScript::convertTypoScriptArrayToPlainArray($typoScriptParser->setup);
        
	}
	
	
	
	/**
	 * Initializes default configuration builder mock used throughout different testcases
	 */
	protected function initDefaultConfigurationBuilderMock($overwriteSettings = NULL) {
        $this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($this->settings, $overwriteSettings);		
	}
    
    
    
    /**
     * Returns a renderer configuration created by current configuration builder mock settings
     *
     * @return Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig
     */
    public function getRendererConfiguration() {
    	$rendererChainConfig = Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfigFactory::getInstance($this->configurationBuilderMock);
        $rendererConfiguration = $rendererChainConfig->getItemById('100');
        return $rendererConfiguration;
    }



    /**
     * Returns mock object for given class name with methods given in override methods overwritten in returned object.
     *
     * @param $className Name of class to be mocked
     * @param array $overrideMethods Methods to be overwritten in mocked class
     * @return PHPUnit_Framework_MockObject_MockObject Mock instance of $className
     */
    public function getSimpleMock($className, $overrideMethods = array()) {
        return $this->getMock($className, array(), $overrideMethods, '', FALSE);
    }

}
?>