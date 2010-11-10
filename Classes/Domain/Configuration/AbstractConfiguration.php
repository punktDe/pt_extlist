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
 * Class implements an abstract configuration object
 *
 * @package Domain
 * @subpackage Configuration
 * @author Michael Knoll <knoll@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 */
abstract class Tx_PtExtlist_Domain_Configuration_AbstractConfiguration {

	/**
	 * Holds an instance of configuration builder
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;
	
	
	
	/**
	 * Holds an array of settings for configuration object
	 *
	 * @var array
	 */
	protected $settings;
	
	
	
	/**
	 * The listidentifier this config object belings to
	 * 
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * Constructor for configuration object
	 *
	 * @param array $settings
	 */
	public function __construct(array $settings = array()) {
		$this->settings = $settings;
		$this->init();
	}
	
	
	
	/**
	 * Injects configurationbuilder
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function injectConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->configurationBuilder = $configurationBuilder;
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
	}
	
	
	
	 /**
     * @param string $key Key of settings array to be returned
     * @return mixed
     */
    public function getSettings($key = '') {
    	if ($key != '' ) {
    	   if (array_key_exists($key, $this->settings)) {
    		  return $this->settings[$key];
    	   } else {
    	   		return NULL;
    	   }
    	} else {
            return $this->settings;
    	}
    }
	
	
	
	/**
	 * Returns a reference to the configurationbuilder
	 * 
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	public function getConfigurationBuilder() {
		return $this->configurationBuilder;
	}
	
	
	
	/**
	 * @return string listIdentifier
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
	
	

	/**
	 * Set the internal property from the given tsKey if the key exists
	 * 
	 * @param string $tsKey with the value to copy to the internal property
	 * @param string $internalPropertyName optional property name if it is deiferent from the tsKey
	 */
	protected function setValueIfExists($tsKey, $internalPropertyName = NULL) {
		if (array_key_exists($tsKey, $this->settings)) {
			$property = $internalPropertyName ? $internalPropertyName : $tsKey;
			$this->$property = $this->settings[$tsKey];
		}	
	}
	
	
	
	/**
	 * Set the internal property from the given tsKey if the key exists, and is not nothing
	 * 
	 * @param string $tsKey with the value to copy to the internal property
	 * @param string $internalPropertyName optional property name if it is deiferent from the tsKey
	 */
	protected function setValueIfExistsAndNotNothing($tsKey, $internalPropertyName = NULL) {
		if (array_key_exists($tsKey, $this->settings) && (is_array($this->settings[$tsKey]) || trim($this->settings[$tsKey]))) {
			$property = $internalPropertyName ? $internalPropertyName : $tsKey;
			$this->$property = $this->settings[$tsKey];
		}
	}
	
	
	/**
	 * Set the internal property from the given tsKey if the key exists, and is not nothing
	 * 
	 * @param string $tsKey with the value to copy to the internal property
	 * @param string $internalPropertyName optional property name if it is deiferent from the tsKey
	 */
	protected function setBooleanIfExistsAndNotNothing($tsKey, $internalPropertyName = NULL) {
		$property = $internalPropertyName ? $internalPropertyName : $tsKey;
		if (array_key_exists($tsKey, $this->settings) && trim($this->settings[$tsKey])) {
			$this->$property = true;
		} else {
			$this->$property = false;
		}
	}
	
	
	/**
	 * Checks ift the tsKey exists in the settings and throw an exception with the given method if not
	 * 
	 * @param string $tsKey with the value to copy to the internal property
	 * @param string_type $errorMessageIfNotExists
	 * @param string $internalPropertyName optional property name if it is deiferent from the tsKey
	 * @throws Exception
	 */
	protected function setRequiredValue($tsKey, $errorMessageIfNotExists, $internalPropertyName = NULL) {
		if (!array_key_exists($tsKey, $this->settings) || !trim($this->settings[$tsKey])) {
	
			Throw new Exception($errorMessageIfNotExists);
		}
		
		$property = $internalPropertyName ? $internalPropertyName : $tsKey;
		$this->$property = $this->settings[$tsKey];
	}
	
	
	
	/**
	 * Template method for initializing configuration object.
	 * 
	 * Overwrite this method for implementing your own initialization
	 * functionality in concrete class.
	 */
	protected function init() { }
	
}
?>