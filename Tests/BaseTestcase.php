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
 * Class implements a base testcase for pt_extlist testcases
 *
 * @package Tests
 * @author Michael Knoll <knoll@punkt.de>
 */
abstract class Tx_PtExtlist_Tests_BaseTestcase extends Tx_Extbase_BaseTestcase {

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
        $this->extBaseSettings = Tx_Extbase_Utility_TypoScript::convertTypoScriptArrayToPlainArray($typoScriptParser->setup);
	}
	
	
	
	/**
	 * Initializes default configuration builder mock used throughout different testcases
	 *
	 * @return void
	 */
	protected function initDefaultConfigurationBuilderMock($overwriteSettings = NULL) {
        $this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($this->settings, $overwriteSettings);		
	}
	
	
	
	/**
	 * Sets up a extbase dispatcher that is required for some test to run
	 *
	 */
    protected function setupDispatcher() {
        $dispatcher = new Tx_Extbase_Dispatcher();
        try {
            $dispatcher->dispatch('content', $this->extBaseSettings);
        } catch (Exception $e) {
            
        }
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
	
	
}

?>