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
 * Factory for Configuration Builder
 * 
 * @package Domain
 * @subpackage Configuration
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory {
	/**
	 * Holds an associative array of instances of configuration builder objects
	 * Each list identifier holds its own configuration builder object
	 * @var array<Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder>
	 */
	private static $instances = NULL;
	
	
	/**
	 * Holds an array of all extList settings
	 * 
	 * @var array
	 */
	private static $settings = NULL;
	
	
	
	/**
	 * Inject all settings of the extension 
	 * @param $settings The current settings for this extension.
	 */
	public static function injectSettings(array $settings) {
		self::$settings = $settings;
	}
	
	
	/**
	 * Returns a singleton instance of a configurationBuilder class
	 * @param $listIdentifier the listidentifier of the list
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder 
	 */
	public static function getInstance($listIdentifier = NULL) {
		
		if($listIdentifier == NULL) {
			$listIdentifier = Tx_PtExtlist_Utility_Extension::getCurrentListIdentifier();
		}

		if ($listIdentifier == '') {
			throw new Exception('No list identifier could be found in settings! 1280230579');
		}
		
		if (!array_key_exists($listIdentifier,self::$instances)) {
			
			if(!array_key_exists($listIdentifier, self::$settings['listConfig'])) {
				throw new Exception('No list with listIdentifier '.$listIdentifier.' could be found in settings! 1288110596');
			}
        
            self::$instances[$listIdentifier] = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder(self::$settings);
        }
        
        return self::$instances[$listIdentifier];
	}	
}
?>