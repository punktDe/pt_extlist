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
 * Class implements abstract configuration builder 
 *
 * @package Domain
 * @subpackage Configuration
 * @author Michael Knoll <knoll@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 */
abstract class Tx_PtExtlist_Domain_Configuration_AbstractConfigurationBuilder {
	
	
	/**
	 * Holds configuration for plugin / extension
	 *
	 * @var array
	 */
	protected $settings;
	
	
	/**
	 * Holds definition of configuration object instances
	 * 
	 * objectName
	 * 	=> factory = Classname of the factory
	 *  => tsKey typoscript key if diferent from objectName
	 *  => prototype = path to the prototype settings
	 *  
	 * @var array
	 */
	protected $configurationObjectSettings = array();
	
	
	/**
	 * Chache for all configuration Objects
	 * 
	 * @var unknown_type TODO: define a interface
	 */
	protected $configurationObjectInstances = array();
	
	
	/**
	 * Constructor for configuration builder. 
	 *
	 * @param array $settings Configuration settings
	 */
	public function __construct(array $settings = array()) {
		$this->settings = $settings;
	}
	
	
	
	/**
	 * Magic functions
	 *
	 * @param string $name Name of method called
	 * @param array $arguments Arguments passed to called method
	 */
	public function __call($functionName, $arguments) {
		$functionName = strtolower($functionName);
		$matches = array();
		$pattern = '/(get|build)(.+)configuration/is'; 
		preg_match($pattern, $functionName, $matches);
		
		if ($matches[2]) {
			return $this->buildConfigurationGeneric($matches[2]);
		}
		
		Throw new Exception('The method configurationBuilder::' . $functionName . ' could not be found or handled by magic function. 1289407912');
	}
	
	
	/**
	 * Generic factory method for configuration objects
	 *
	 * @param string $configurationName
	 * @return mixed
	 */
	protected function buildConfigurationGeneric($configurationName) {
		
		if(!$this->configurationObjectInstances[$configurationName]) {
			
			if(!array_key_exists($configurationName, $this->configurationObjectSettings)) {
				throw new Exception('No Configuration Object with name ' . $configurationName . ' defined in ConfigurationBuilder 1289397150');
			}
			
			$factoryClass = $this->configurationObjectSettings[$configurationName]['factory'];
			$this->configurationObjectInstances[$configurationName] = $factoryClass::getInstance($this);
		}
		
		return $this->configurationObjectInstances[$configurationName];
	}

	
	/**
	 * 
	 * 
	 * @param string $configurationName
	 */
	public function getSettingsForConfigObject($configurationName) {
		
		if(!array_key_exists($configurationName, $this->configurationObjectSettings)) {
			throw new Exception('No Configuration Object with name ' . $configurationName . ' defined in ConfigurationBuilder 1289397150');
		}
		
		$tsKey = array_key_exists('tsKey', $this->configurationObjectSettings[$configurationName]) ? $this->configurationObjectSettings[$configurationName]['tsKey'] : $configurationName;
		$settings = $this->settings[$tsKey];
		
		if(array_key_exists('prototype', $this->configurationObjectSettings[$configurationName]['prototype'])) {
			$settings = $this->getMergedSettingsWithPrototype($settings, $this->configurationObjectSettings[$configurationName]['prototype']);
		}
		
		return $settings;
	}
	
	
	/**
	 * Return the list specific settings merged with prototype settings
	 * 
	 * @param array $listSepcificConfig
	 * @param string $objectName
	 * @return array
	 */
	public function getMergedSettingsWithPrototype($listSepcificConfig, $objectPath) {
		// TODO cache this!
		if(!is_array($listSepcificConfig)) $listSepcificConfig = array();
			$mergedSettings = t3lib_div::array_merge_recursive_overrule(
            $this->getPrototypeSettingsForObject($objectPath),
			$listSepcificConfig
        );

        return $mergedSettings;
	}
	
	
	
    /**
     * return a slice from the prototype arrray for the given objectPath
     * 
     * @param string $objectPath
     * @return array prototypesettings
     */
    public function getPrototypeSettingsForObject($objectPath) {

    	$protoTypeSettings = Tx_PtExtlist_Utility_NameSpaceArray::getArrayContentByArrayAndNamespace($this->protoTypeSettings, $objectPath);
    	
    	if(!is_array($protoTypeSettings)) {
    		$protoTypeSettings = array();
    	} 
    	
    	return $protoTypeSettings;
    }
}
 
?>