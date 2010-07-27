<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de>
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
 * Class implements a Builder for all configurations required in extlist.
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder {

	/**
	 * Holds an associative array of instances of configuration builder objects
	 * Each list identifier holds its own configuration builder object
	 * @var array<Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder>
	 */
	private static $instances = null;

	
	
	/**
	 * Holds an instance of a session adapter
	 * @var Tx_PtExtlist_Domain_Configuration_SessionAdapter
	 */
	protected $sessionAdapter;
	
	
	
	/**
	 * Merged settings of global and local configuration
	 * @var array
	 */
	protected $settings;
	
	
	
	/**
	 * Non-merged settings of plugin
	 * @var array
	 */
	protected $origSettings;
	
	
	
	/**
	 * Holds list identifier of current list
	 * @var string
	 */
	protected $listIdentifier;
	

    /**
	 * Holds an instance of extension configuration adapter
	 * @var Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter
	 */
	protected $extensionConfigurationAdapter; 
	
	
	
	/**
	 * Holds an instance of a fields configuration and handles it as a singleton instance
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	protected $fieldsConfiguration = null;
	
	
	
	/**
	 * Holds an instance of a columns configuration and handles it as a singleton instance
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
	 */
	protected $columnsConfiguration = null;
	
	/**
	 * Holds an instance of a renderer configuration and handles it as a singleton instance.
	 * @var Tx_Ptextlist_Configuration_Renderer_RendererConfiguration
	 */
	protected $rendererConfiguration = null;
	
    /**
     * Holds an instance of a pager configuration associated to this list
     * @var Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration
     */
	protected $pagerConfiguration;
	
	
	
	/**
	 * Returns a singleton instance of this class
	 * @param $settings The current settings for this extension.
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder   Singleton instance of this class
	 */
	public static function getInstance(array $settings) {
	    if ($settings['listIdentifier'] != '') {
            if (!array_key_exists($settings['listIdentifier'],self::$instances)) {
            	self::$instances[$settings['listIdentifier']] = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($settings);
            }
        } else {
            throw new Exception('No list identifier could be found in settings!');
        }
        return self::$instances[$settings['listIdentifier']];
	}

	
	
	/**
	 * Constructor is private, use getInstance instead!
	 * 
	 * @param array $settings  Settings of extension
	 */
	protected function __construct(array $settings) {
		$this->setListIdentifier($settings);
		$this->origSettings = $settings;
		$this->mergeAndSetGlobalAndLocalConf();
		
		$this->sessionAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_SessionAdapter');
		$this->extensionConfigurationAdapter = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Configuration_ExtensionConfigurationAdapter', $settings);
		// TODO: MUST be injected through the factory 
	}
	
	
	
	protected function setListIdentifier($settings) {
		tx_pttools_assert::isNotEmptyString($settings['listIdentifier'], array('message' => 'List identifier must not be empty! 1278419535'));
		tx_pttools_assert::isArray($settings['listConfig'][$settings['listIdentifier']], array('message' => 'No list configuration can be found for list identifier ' . $settings['listIdentifier'] . ' 1278419536'));
        $this->listIdentifier = $settings['listIdentifier'];    
	}
	
	
	
	protected function mergeAndSetGlobalAndLocalConf() {
		if (is_array($this->origSettings['listConfig'][$this->listIdentifier])) {
			$mergedSettings = t3lib_div::array_merge_recursive_overrule(
	            $this->origSettings,
	            $this->origSettings['listConfig'][$this->listIdentifier]
	        );
	        $this->settings = $mergedSettings;
		}
	}

	
	
    /**
     * Returns array of settings for current plugin configuration
     *
     * @return array
     * @author Michael Knoll <knoll@punkt.de>
     */
    public function getSettings() {
        return $this->settings;
    }
    
    
    /**
     * Returns identifier of list
     *
     * @return String
     */
    public function getListIdentifier() {
        return $this->listIdentifier;
    }
    
    
    
    /**
     * Returns configuration for backend
     *
     * @return array    Part of list settings for backend configuration
     */
    public function getBackendConfiguration() {
    	return $this->settings['backendConfig'];
    }
    
    
    
    /**
     * Returns configuration array for filterbox identifier
     *
     * @param array $filterboxIdentifier
     */
    public function getFilterboxConfigurationByFilterboxIdentifier($filterboxIdentifier) {
    	tx_pttools_assert::isNotEmptyString($filterboxIdentifier, array('message' => 'Filterbox identifier must not be empty! 1277889453'));
    	return $this->settings['filters'][$filterboxIdentifier];
    }
    
    
    
    /**
     * Returns configuration array for all filters
     *
     * @return array Settings for all filters
     */
    public function getFilterSettings() {
    	return $this->settings['filters'];
    }
    
    
    /**
     * Returns a singleton instance of a fields configuration collection for current list configuration
     *
     * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
     */
    public function buildFieldsConfiguration() {
    	if (is_null($this->fieldsConfiguration)) {
    		$this->fieldsConfiguration = Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollectionFactory::getFieldConfigCollection($this->settings['fields']);
    	}
    	return $this->fieldsConfiguration;
    }

    
    
    /**
     * Returns a singleton instance of columns configuration collection for current list configuration
     *
     * @return Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
     */
    public function buildColumnsConfiguration() {
    	if (is_null($this->columnsConfiguration)) {
    		$this->columnsConfiguration = Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollectionFactory::getColumnConfigCollection($this->settings['columns']);
    	}
    	return $this->fieldsConfiguration;
    }
    
    /**
     * Returns a singleton instance of the renderer configuration object.
     * 
     * @return Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration
     */
    public function buildRendererConfiguration() {
    	if(is_null($this->rendererConfiguration)) {
    		$this->rendererConfiguration = Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfigFactory::getRendererConfiguration($this->settings['renderer'], $this->buildColumnsConfiguration());
    	}
    	
    	return $this->rendererConfiguration;
    }
    
    /**
     * Returns configuration object for pager
     *
     * @return Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration Configuration object for pager
     */
    public function buildPagerConfiguration() {
    	if (is_null($this->pagerConfiguration)) {
    		$this->pagerConfiguration = new Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration($this, $this->getPagerSettings());
    	}
    	return $this->pagerConfiguration;
    }
    
    
    
    /**
     * Returns an array with pager configuration 
     *
     * @return array Pager configuration
     */
    public function getPagerSettings() {
    	return $this->settings['pagerConfig'];
    }
    

    
}

?>