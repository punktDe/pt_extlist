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
 * Class implements a factory for GET/POST Var Adapter.
 *
 * @package Domain
 * @subpackage StateAdapter
 */
class Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory {
	
	/**
	 * Singleton instance of GET/POST Var Adapter.
	 *
	 * @var Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter
	 */
	private static $instance;
	
	
	
	/**
	 * Factory method for GET/POST Var Adapter.
	 * 
	 * @return Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter Singleton instance of GET/POST Var Adapter.
	 */
	public static function getInstance() {
		if (self::$instance == NULL) {
			$extensionNameSpace = Tx_PtExtlist_Utility_Extension::getExtensionNameSpace();
			
			self::$instance = new Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter();
			self::$instance->injectGetVars(self::extractExtensionVariables($_GET, $extensionNameSpace));
			self::$instance->injectPostVars(self::extractExtensionVariables($_POST, $extensionNameSpace));
			self::$instance->setExtensionNamespace($extensionNameSpace);
		}
	
		return self::$instance;
	}
		
	
	/**
	 * Remove the extension name from the variables
	 * 
	 * @param string $vars
	 * @param string $nameSpace
	 */
	protected function extractExtensionVariables($vars, $extensionNameSpace) {
		$extractedVars = $vars[$extensionNameSpace];
		if(!is_array($extractedVars)) {
			$extractedVars = array();
		}
		
		return $extractedVars;
	}
}
?>