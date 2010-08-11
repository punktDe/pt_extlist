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
 * Class implements a Builder for all configurations required in pt_extlist.
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder {
	
	
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
	 * Prototype settings for ts-configurable objects
	 * @var array
	 */
	protected $protoTypeSettings;
	
	
	/**
	 * Holds list identifier of current list
	 * @var string
	 */
	protected $listIdentifier;
	
	
	/**
	 * Holds an instance of a databackend configuration and handles it as a singleton instance
	 * @var Tx_PtExtlist_Domain_Configuration_DataBackend_DatabackendConfiguration
	 */
	protected $dataBackendConfiguration = null;
	
	
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
	protected $pagerConfiguration = null;
	
	
	/**
	 * Holds an instance of the configuration of all filters associated to this list
	 * @var Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfigCollection
	 */
	protected $filterConfiguration = null;
	
		
	/**
	 * Constructor is private, use getInstance instead!
	 * 
	 * @param array $settings  Settings of extension
	 */
	public function __construct(array $settings) {
		$this->setProtoTypeSettings($settings);
		$this->setListIdentifier($settings);
		$this->origSettings = $settings;
		$this->mergeAndSetGlobalAndLocalConf();
	}
	
	
	/**
	 * Check and set the prototype settings
	 * @param array $settings
	 */
	protected function setProtoTypeSettings($settings) {
		tx_pttools_assert::isArray($settings['prototype'], array('message' => 'The basic settings are not available. Maybe the static typoscript template for pt_extlist is not included on this page. 1281175089'));
		$this->protoTypeSettings = $settings['prototype'];
	}
	
	
	
	/**
	 * Sets the list identifier of current list
	 *
	 * @param array $settings
	 */
	protected function setListIdentifier($settings) {
		
		if(!array_key_exists($settings['listIdentifier'], $settings['listConfig'])) {
			if(count($settings['listConfig']) > 0) {
				$helpListIdentifier = 'Available list configurations on this page are: ' . implode(', ', array_keys($settings['listConfig'])) . '.';
			} else {
				$helpListIdentifier = 'No list configurations available on this page.';
			}
			throw new Exception('No list configuration can be found for list identifier "' . $settings['listIdentifier'] . '" 1278419536' . '<br>' . $helpListIdentifier);
		}

        $this->listIdentifier = $settings['listIdentifier'];    
	}
	
	
	
	/**
	 * Merges configuration of settings in namespace of list identifiert
	 * with settings from plugin.
	 * 
	 * @param void
	 * @return void
	 */
	protected function mergeAndSetGlobalAndLocalConf() {
		$settingsToBeMerged = $this->origSettings;
		unset($settingsToBeMerged['listConfig']);
		if (is_array($this->origSettings['listConfig'][$this->listIdentifier])) {
			$mergedSettings = t3lib_div::array_merge_recursive_overrule(
	            $settingsToBeMerged,
	            $this->origSettings['listConfig'][$this->listIdentifier]
	        );
	        $this->settings = $mergedSettings;
		}
	}

	
	
    /**
     * Returns array of settings for current list configuration
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
     * Returns configuration for databackend
     *
     * @return array    Part of list settings for backend configuration
     */
    public function getDatabackendSettings() {
    	return $this->settings['backendConfig'];
    }
    
    
    
    /**
     * Returns configuration object for filterbox identifier
     *
     * @param array $filterboxIdentifier
     */
    public function getFilterboxConfigurationByFilterboxIdentifier($filterboxIdentifier) {
    	tx_pttools_assert::isNotEmptyString($filterboxIdentifier, array('message' => 'Filterbox identifier must not be empty! 1277889453'));
    	return $this->buildFilterConfiguration()->getItemById($filterboxIdentifier);
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
     * Returns configuration array for the renderer
     *
     * @return array Settings for the renderer
     */
    public function getRendererSettings() {
    	return $this->getMergedSettingsWithPrototype($this->settings['renderer'], 'renderer.default');
    }
    
    
    /**
     * Returns the configuration settings for all columns
     * @return array Settings for all columns
     * @author Daniel Lienert <lienert@punkt.de>
     * @since 28.07.2010
     */
    public function getColumnSettings() {
    	return $this->settings['columns'];
    }
    
    /**
     * return a slice from the prototype arrray for the given objectPath
     * 
     * @param string $objectPath
     * @return array prototypesettings
     * @author Daniel Lienert <lienert@punkt.de>
     * @since 05.08.2010
     */
    public function getPrototypeSettingsForObject($objectPath) {

    	$protoTypeSettings = Tx_PtExtlist_Utility_NameSpaceArray::getArrayContentByArrayAndNamespace($this->protoTypeSettings, $objectPath);
    	
    	if(!is_array($protoTypeSettings)) {
    		$protoTypeSettings = array();
    	} 
    	
    	return $protoTypeSettings;
    }
        
    
	/**
	 * Return the list specific settings merged with prototype settings
	 * 
	 * @param array $listSepcificConfig
	 * @param string $objectName
	 * @return array
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 05.08.2010
	 */
	public function getMergedSettingsWithPrototype($listSepcificConfig, $objectName) {
		
		if(!is_array($listSepcificConfig)) $listSepcificConfig = array();
		
		$mergedSettings = t3lib_div::array_merge_recursive_overrule(
            $this->getPrototypeSettingsForObject($objectName),
			$listSepcificConfig
        );

        return $mergedSettings;
	}
    
	
	/**
	 * Returns a singleton instance of databackend configuration 
	 * @returns Tx_PtExtlist_Domain_Configuration_DataBackend_DatabackendConfiguration
	 */
	public function buildDataBackendConfiguration() {
		if(is_null($this->dataBackendConfiguration)) {
			$this->dataBackendConfiguration = Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfigurationFactory::getInstance($this);
		}
		
		return $this->dataBackendConfiguration;
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
    		$this->columnsConfiguration = Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollectionFactory::getColumnConfigCollection($this);
    	}
    	return $this->columnsConfiguration;
    }
    
    
    
	/**
     * Returns a singleton instance of a filter configuration collection for current list configuration
     *
     * @return Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfigCollection
     */
    public function buildFilterConfiguration() {
    	if (is_null($this->filterConfiguration)) {
    		$this->filterConfiguration = Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfigCollectionFactory::getfilterboxConfigCollection($this);
    	}
    	return $this->filterConfiguration;
	}
    
	
    
    /**
     * Returns a singleton instance of the renderer configuration object.
     * 
     * @return Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration
     */
    public function buildRendererConfiguration() {
    	if(is_null($this->rendererConfiguration)) {

    		tx_pttools_assert::isArray($this->getRendererSettings(), array('message' => 'No renderer configuration can be found for list identifier ' . $this->settings['listIdentifier'] . ' 1280234810'));
    		$this->rendererConfiguration = Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfigFactory::getRendererConfiguration($this);

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
    		$this->pagerConfiguration = Tx_PtExtlist_Domain_Configuration_Pager_PagerConfigurationFactory::getInstance($this);
    	}
    	return $this->pagerConfiguration;
    }
    
    
    
    /**
     * Returns an array with pager configuration 
     *
     * @return array Pager configuration
     */
    public function getPagerSettings() {
       	return $this->getMergedSettingsWithPrototype($this->settings['pager'], 'pager.default');
    }
    
}

?>