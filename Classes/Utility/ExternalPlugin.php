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
 * Class contains utility functions to access extlist objects 
 * form external dependent plugins
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Utility
 */
class Tx_PtExtlist_Utility_ExternalPlugin {
	
	
	/**
	 * Initialize and return a DataBackend with the given listIndentifier
	 *  
	 * @param string $listIdentifier
	 * @return Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 */
	public static function getDataBackend($listIdentifier) {
		
		$extListTs = self::getExtListTyposcriptSettings();
		
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($extListTS);
		$extListBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($configurationBuilder);
		
		return $extListBackend;
	}
	
	
	
	/**
	 * Get Typoscript for defined listIdentifier
	 * 
	 * @param string $listIdentifier
	 * @throws Exception
	 * @return array
	 */
	protected static function getExtListTyposcriptSettings($listIdentifier) {
		$extListTS = tx_pttools_div::typoscriptRegistry('plugin.tx_ptextlist.settings');
		
		if(!array_key_exists($listIdentifier, $extListTS['listConfig'])) {
			throw new Exception('No listconfig with listIdentifier ' . $listIdentifier . ' defined on this page!');
		}
		
		$extListTS['listIdentifier'] = $listIdentifier;
		
		return $extListTS;
	}
	
}