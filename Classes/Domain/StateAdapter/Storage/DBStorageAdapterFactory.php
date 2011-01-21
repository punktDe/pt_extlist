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
 * Factory for the DB storage adapter
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage StateAdapter\Storage
 */
class Tx_PtExtlist_Domain_StateAdapter_Storage_DBStorageAdapterFactory {
	
	/**
	 *
	 * @var Tx_PtExtlist_Domain_StateAdapter_Storage_DBStorageAdapter
	 */
	protected static $instance = NULL;
	
	
	/**
	 * Create a single instance of the db storage adapter
	 * 
	 * @return Tx_PtExtlist_Domain_StateAdapter_Storage_DBStorageAdapter
	 */
	public static function getInstance() {
		
		if(self::$instance == NULL) {
			self::$instance = new Tx_PtExtlist_Domain_StateAdapter_Storage_DBStorageAdapter();
			
			$stateRepository = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Repository_State_StateRepository');
			self::$instance->injectStateRepository($stateRepository);
			self::$instance->setStateHash(self::getStateHash());
			self::$instance->init();
		}
		
		return self::$instance;
	}
	
	
	
	/**
	 * Get the statehash from GPVars
	 * 
	 * @return string hash 
	 */
	protected static function getStateHash() {
		$getPostVarAdapter = Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory::getInstance();
		$listIdentifier = Tx_PtExtlist_Utility_Extension::getCurrentListIdentifier();
		$stateHash = $getPostVarAdapter->getParametersByNamespace($listIdentifier.'.state');
		return $stateHash;	
	}
}
?>