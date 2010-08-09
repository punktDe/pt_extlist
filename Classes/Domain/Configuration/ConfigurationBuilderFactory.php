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
 * @package Typo3
 * @subpackage pt_extlist
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory {
	/**
	 * Holds an associative array of instances of configuration builder objects
	 * Each list identifier holds its own configuration builder object
	 * @var array<Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder>
	 */
	private static $instances = null;
	
	
	
	
	/**
	 * Returns a singleton instance of a configurationBuilder class
	 * @param $settings The current settings for this extension.
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder 
	 */
	public static function getInstance(array $settings) {
		if ($settings['listIdentifier'] != '') {
            if (!array_key_exists($settings['listIdentifier'],self::$instances)) {
            	self::$instances[$settings['listIdentifier']] = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($settings);
            }
        } else {
            throw new Exception('No list identifier could be found in settings! 1280230579');
        }
        return self::$instances[$settings['listIdentifier']];
	}
	
}
?>