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
 * Class implements a factory for GET/POST Var Adapter.
 *
 * @package TYPO3
 * @subpackage pt_extlist
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
			self::$instance = new Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter();
			
			self::$instance->injectGetVars($_GET);
			self::$instance->injectPostVars($_POST);
		}
		
		return self::$instance;
	}
}
?>