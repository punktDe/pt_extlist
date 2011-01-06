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
 * Class implements a factory for the link manager
 *
 * @package Domain
 * @subpackage Link
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Link_LinkManagerFactory {
	
	/**
	 * Array of singleton instance of link manager object
	 *
	 * @var Tx_PtExtlist_Domain_Link_LinkManager
	 */
	private static $instances;
	
	
	
	/**
	 * Factory method for link manager 
	 * 
	 * @param string listIdentifier
	 * @return Tx_PtExtlist_Domain_Link_LinkManager 
	 */
	public static function getInstance($listIdentifier) {
		
		if (self::$instances[$listIdentifier] == NULL) {
			
			$configurationBuidler = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($listIdentifier);
			
			
			self::$instances[$listIdentifier] = new Tx_PtExtlist_Domain_Link_LinkManager();
			self::$instances[$listIdentifier]->injectGetPostVarAdapater(Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory::getInstance());
			self::$instances[$listIdentifier]->injectListConfiguration($configurationBuidler->buildListConfiguration());
			
		}
		
		return self::$instances[$listIdentifier];
	}
}

?>