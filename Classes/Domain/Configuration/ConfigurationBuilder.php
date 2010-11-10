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
 * @package Domain
 * @subpackage Configuration
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @author Michael Knoll <knoll@punkt.de>
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder extends Tx_PtExtlist_Domain_Configuration_AbstractConfigurationBuilder {
	
	/**
	 * Holds settings to build configuration objects
	 *
	 * @var array
	 */
	protected $configurationObjectSettings = array(
	    'aggregateData' => 
	    	array('factory' => 'Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactory'),
	    'aggregateRows' => 
	    	array('factory' => 'Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfigCollectionFactory'),
	    'dataBackend' =>
	    	array('factory' => 'Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfigurationFactory'),
	    'fields' =>
	    	array('factory' => 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollectionFactory'),
	    'list' =>
	    	array('factory' => 'Tx_PtExtlist_Domain_Configuration_List_ListConfigFactory'),
	    
	);
	
	
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
	 * Holds an instance of a columns configuration and handles it as a singleton instance
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
	 */
	protected $columnsConfiguration = NULL;
	
	
	
	/**
	 * Holds an instance og the renderer chain configuration and handles it as a singleton instance.
	 * @var Tx_Ptextlist_Configuration_Renderer_RendererChainConfig
	 */
	protected $rendererChainConfiguration = NULL;
	
	
	
    /**
     * Holds an instance of a pager configuration associated to this list
     * @var Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration
     */
	protected $pagerConfiguration = NULL;
	
	
	/**
	 * Holds an instance of the configuration of all filters associated to this list
	 * @var Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfigCollection
	 */
	protected $filterConfiguration = NULL;
	
	
	/**
	 * Holds an instance of the bookmark configuration
	 * @var Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig
	 */
	protected $bookmarkConfiguration = NULL;
	
	
	/**
	 * Holds an instance of the list defaults configuration 
	 * @var Tx_PtExtlist_Domain_Configuration_List_ListDefaultConfig
	 */
	protected $listDefaultConfiguration = NULL;
	
	
	/**
	 * Holds an instance of the export configuration
	 * @var Tx_PtExtlist_Domain_Configuration_Export_ExportConfig
	 */
	protected $exportConfiguration = NULL;
	
		
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
     */
    public function getSettings($key = NULL) {
    	if(!$key) {
        	return $this->settings;	
        } else {
        	if(array_key_exists($key, $this->settings)) {
        		return $this->settings[$key];
        	}
        }
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
     * Returns settings
     * @return array 
     */
    public function getDefaultSettings() {
    	return $this->settings['default'];
    }
    
    
    
    /**
     * Returns settings array for all filters
     *
     * @return array Settings for all filters
     */
    public function getFilterSettings() {
    	return $this->settings['filters'];
    }
    
    
    
    /**
     * 
     * 
     * @return array list settings
     */
    public function getListSettings() {
    	return $this->getMergedSettingsWithPrototype($this->settings, 'list');
    }
    
    
    
    /**
     * Returns settings array for the renderer
     *
     * @return array Settings for the renderer
     */
    public function getRendererChainSettings() {
    	$mergedSettings = $this->getMergedSettingsWithPrototype($this->settings['rendererChain'], 'rendererChain');
    	return $mergedSettings;
    }
    
    
    
    /**
     * Returns settings for all columns
     * @return array Settings for all columns
     */
    public function getColumnSettings() {
    	return $this->settings['columns'];
    }
    
    
    
    /**
     * @return array aggregate row settings
     */
    public function getAggregateRowSettings() {
    	return $this->settings['aggregateRows'];
    }
    
    
    /**
     * return aggregate data settings
     * @return array
     */
    public function getAggregateDataSettings() {
    	return $this->settings['aggregateData'];
    }

    
    /**
     * @return array export settings
     */
    public function getExportSettings() {
    	return $this->settings['export'];
    }
    
    
    

        
    
    

    
	
	
    /**
     * Returns an array with pager configuration 
     *
     * @return array Pager configuration
     */
    public function getPagerSettings() {
       	return $this->getMergedSettingsWithPrototype($this->settings['pager'], 'pager');
    }
    
    
    
    /**
     * Returns an array with bookmarks settings
     *
     * @return array Bookmarks settings
     */
    public function getBookmarksSettings() {
    	return $this->getMergedSettingsWithPrototype($this->settings['bookmarks'], 'bookmarks');
    }
	
    
	
	/**
	 * Returns a singleton instance of databackend configuration 
	 * @returns Tx_PtExtlist_Domain_Configuration_DataBackend_DatabackendConfiguration
	 */
	public function buildDataBackendConfiguration() {
		return $this->buildConfigurationGeneric('dataBackend');
	}
	
	
	
    /**
     * Returns a singleton instance of a fields configuration collection for current list configuration
     *
     * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
     */
    public function buildFieldsConfiguration() {
    	return $this->buildConfigurationGeneric('fields');
    }


    
    /**
     * Returns a singleton instance of a aggregateData configuration collection for current list configuration
     * 
     * @return Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection
     */
    public function buildAggregateDataConfig() {
    	return $this->buildConfigurationGeneric('aggregateData');
    }
    
    
    
    /**
     * return a singelton instance of aggregate row collection 
     * 
     * @return Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfigCollection
     */
    public function buildAggregateRowsConfig() {
    	return $this->buildConfigurationGeneric('aggregateRows');
    }
    
    
    
    /**
     * return a singleton instance of export configuratrion
     * @return Tx_PtExtlist_Domain_Configuration_Export_ExportConfig
     */
    public function buildExportConfiguration() {
    	if(is_null($this->exportConfiguration)) {
    		$this->exportConfiguration = Tx_PtExtlist_Domain_Configuration_Export_ExportConfigFactory::getInstance($this);
    	}
    	
    	return $this->exportConfiguration;
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
     * Returns a singleton instance of the renderer chain configuration object.
     * 
     * @return Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfig
     */
    public function buildRendererChainConfiguration() {
    	if(is_null($this->rendererChainConfiguration)) {

    		tx_pttools_assert::isArray($this->getRendererChainSettings(), array('message' => 'No renderer chain configuration can be found for list identifier ' . $this->settings['listIdentifier'] . ' 1280234810'));
    		$this->rendererChainConfiguration = Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfigFactory::getRendererChainConfiguration($this);

    	}
    	return $this->rendererChainConfiguration;
    }
    
    
   /**
     * Returns bookmarks configuration
     *
     * @return Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig
     */
    public function buildBookmarksConfiguration() {
        if(is_null($this->bookmarkConfiguration)) {
        	$this->bookmarkConfiguration = new Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig($this);
        }
    	
        return $this->bookmarkConfiguration;
    }
    
    
    /**
     * @return Tx_PtExtlist_Domain_Configuration_List_ListDefaultConfig
     */
    public function buildListDefaultConfig() {
    	if(is_null($this->listDefaultConfiguration)) {
    		$this->listDefaultConfiguration =  Tx_PtExtlist_Domain_Configuration_List_ListDefaultConfigFactory::getInstance($this);	
    	}
    	
    	return $this->listDefaultConfiguration; 
    }
    
    
    /**
     * Returns configuration object for pager
     *
     * @return Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration Configuration object for pager
     */
    public function buildPagerConfiguration() {
    	if (is_null($this->pagerConfiguration)) {
    		$this->pagerConfiguration = Tx_PtExtlist_Domain_Configuration_Pager_PagerConfigCollectionFactory::getPagerConfigCollection($this);
    	}
    	return $this->pagerConfiguration;
    }

    
    /**
     * Returns a list configuration object
     * 
     * @return Tx_PtExtlist_Domain_Configuration_List_ListConfiguration
     */
    public function buildListConfiguration() {
    	$this->buildConfigurationGeneric('list');
    }
}

?>