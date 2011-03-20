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
 * Class implements an abstract configuration object
 *
 * @package Domain
 * @subpackage Configuration
 * @author Michael Knoll 
 * @author Daniel Lienert 
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
	 * Constructor for configuration object
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param array $settings
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, array $settings = array()) {
		$this->configurationBuilder = $configurationBuilder;
		$this->settings = $settings;
		$this->init();
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
		if ($this->configValueExiststAndNotNothing($tsKey)) {
			$property = $internalPropertyName ? $internalPropertyName : $tsKey;
			$this->$property = $this->settings[$tsKey];
		}
	}
	

	/**
	 * Checks if config value exists and not nothing
	 * 
	 * @param unknown_type $tsKey
	 */
	protected function configValueExiststAndNotNothing($tsKey) {
		return array_key_exists($tsKey, $this->settings) && (is_array($this->settings[$tsKey]) || trim($this->settings[$tsKey]));
	}
	
	
	/**
	 * Set the internal property from the given tsKey if the key exists, and is not nothing
	 * 
	 * @param string $tsKey with the value to copy to the internal property
	 * @param string $internalPropertyName optional property name if it is deiferent from the tsKey
	 */
	protected function setBooleanIfExistsAndNotNothing($tsKey, $internalPropertyName = NULL) {
		$property = $internalPropertyName ? $internalPropertyName : $tsKey;
		if (array_key_exists($tsKey, $this->settings)) {
			if(trim($this->settings[$tsKey]) != '1') {
				$this->$property = false;
			} else {
				$this->$property = true;
			}
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
		if (!array_key_exists($tsKey, $this->settings) || (!trim($this->settings[$tsKey]) && !is_array($this->settings[$tsKey]))) {
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