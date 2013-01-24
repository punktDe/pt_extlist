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
 */
class Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory {
	/**
	 * Holds an associative array of instances of configuration builder objects
	 * Each list identifier holds its own configuration builder object
	 * @var array<Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder>
	 */
	private static $instances = array();
	
	
	/**
	 * Holds an array of all extList settings
	 * 
	 * @var array
	 */
	private static $settings = NULL;
	
	
	
	/**
	 * Inject all settings of the extension
	 * 
	 * @static
	 * @param array $settings
	 * @return void
	 */
	public static function injectSettings(array &$settings) {
		self::$settings = &$settings;
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
	public static function getInstance($listIdentifier = NULL, $resetConfigurationBuilder = FALSE) {
		
		if($listIdentifier == NULL) {
			$listIdentifier = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')
										->get('Tx_PtExtlist_Extbase_ExtbaseContext')
										->getCurrentListIdentifier();
		}

		if ($listIdentifier == '') {
			throw new Exception('No list identifier could be found in settings!', 1280230579);
		}

		if (!array_key_exists($listIdentifier, self::$instances) || $resetConfigurationBuilder) {
			
			if(!is_array(self::$settings['listConfig']) || !array_key_exists($listIdentifier, self::$settings['listConfig'])) {
				throw new Exception('No list with listIdentifier '.$listIdentifier.' could be found in settings!', 1288110596);
			}

			self::$instances[$listIdentifier] = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder(self::$settings, $listIdentifier);
      }

      return self::$instances[$listIdentifier];
	}	
}
?>