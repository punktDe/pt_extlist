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
 * Class implements a factory for session persistence manager.
 *
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManagerFactory {
	
	/**
	 * Singleton instance of session persistence manager object
	 *
	 * @var Tx_PtExtlist_Domain_SessionPersistence_SessionPersistenceManager
	 */
	private static $instance;
	
	
	
	/**
	 * Factory method for session persistence manager 
	 * 
	 * @return Tx_PtExtlist_Domain_SessionPersistence_SessionPersistenceManager Singleton instance of session persistence manager 
	 */
	public static function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager();
			self::initializeInstance();
		}
		return self::$instance;
	}
	
	
	
	/**
	 * Initializes session persistence manager object
	 *
	 * @return void
	 */
	private static function initializeInstance() {
		// TODO think about the fact that here a static property is manipulated... pass by reference?!?
		self::$instance->injectSessionAdapter(tx_pttools_sessionStorageAdapter::getInstance());
	}
	
}

?>