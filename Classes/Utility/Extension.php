<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert , Michael Knoll 
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
 * Class contains STATIC utility functions to determine plugin behaviour 
 * 
 * @author Daniel Lienert 
 * @package Utility
 */
class Tx_PtExtlist_Utility_Extension {

	/**
	 * Get the extension namespace from framework konfiguration
	 * 
	 *  @return string extensionNamespace
	 */
	public static function getExtensionNameSpace() {
		$frameWorkKonfiguration = Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration();
		return strtolower('tx_' .$frameWorkKonfiguration['extensionName'].'_'.$frameWorkKonfiguration['pluginName']);
	}
		
	
	/**
	 * Determine if the extension operates in cached mode
	 * 
	 * @return boolean isCached
	 */
	public static function isInCachedMode() {
		$frameWorkKonfiguration = Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration();
		return $frameWorkKonfiguration['pluginName'] == 'Cached' ? true : false;
	}
	
	
	/**
	 * Get the listIdentifier
	 * 
	 * @return string currentListIdentifier
	 */
	public static function getCurrentListIdentifier() {
		$frameWorkKonfiguration = Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration();
		return $frameWorkKonfiguration['settings']['listIdentifier'];
	}
}