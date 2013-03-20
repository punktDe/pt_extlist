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
 * Factory for Configuration Builder
 *
 * @package Domain
 * @subpackage Configuration
 * @author Daniel Lienert
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderFactoryTests
 */
class Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory implements t3lib_Singleton {

	/**
	 * Holds an associative array of instances of configuration builder objects
	 * Each list identifier holds its own configuration builder object
	 * @var array<Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder>
	 */
	private $instances = array();


	
	/**
	 * Holds an array of all extList settings
	 * 
	 * @var array
	 */
	private $settings = NULL;



	/**
	 * Holds extbase context to determine FE / BE usage
	 *
	 * @var Tx_PtExtlist_Extbase_ExtbaseContext
	 */
	private $extbaseContext;



	/**
	 * Injects configuration manager (that holds TS settings) for usage with DI
	 *
	 * @param Tx_Extbase_Configuration_ConfigurationManager $configurationManager
	 */
	public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManager $configurationManager) {
		$this->settings = $configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
	}



	/**
	 * As we sometimes do not have a configuration manager
	 * with relevant TS settings, we can set them here manually.
	 *
	 * TODO at the moment we need this for extlist context. This should be fixed, once we have bootstrap and new DI
	 *
	 * @param array $settings
	 */
	public function setSettings(array $settings) {
		$this->settings = $settings;
	}



	/**
	 * Injects extbase context (for determine FE / BE usage) for usage with DI
	 *
	 * @param Tx_PtExtlist_Extbase_ExtbaseContext $extbaseContext
	 */
	public function injectExtbaseContext(Tx_PtExtlist_Extbase_ExtbaseContext $extbaseContext) {
		$this->extbaseContext = $extbaseContext;
	}


	
	/**
	 * Returns a singleton instance of a configurationBuilder class
	 *
	 * @static
	 * @param $listIdentifier string the listidentifier of the list
	 * @param boolean $resetConfigurationBuilder
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 * @throws Exception
	 */
	public function getInstance($listIdentifier = NULL, $resetConfigurationBuilder = false) {
		if($listIdentifier == NULL) {
			$listIdentifier = $this->extbaseContext->getCurrentListIdentifier();
		}

		if ($listIdentifier == '') {
			throw new Exception('No list identifier could be found in settings!', 1280230579);
		}

		if (!array_key_exists($listIdentifier, $this->instances) || $resetConfigurationBuilder) {
			if(!is_array($this->settings['listConfig']) || !array_key_exists($listIdentifier, $this->settings['listConfig'])) {
				throw new Exception('No list with listIdentifier '.$listIdentifier.' could be found in settings!', 1288110596);
			}

        	$this->instances[$listIdentifier] = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, $listIdentifier);
    	}

    	return $this->instances[$listIdentifier];
	}

}