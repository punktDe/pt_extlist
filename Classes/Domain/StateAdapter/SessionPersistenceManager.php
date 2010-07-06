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
 * Persistence manager to store objects to session and reload objects from session.
 * Uses pt_tools sessionStorageAdapter for accessing T3 session.
 *
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager {
	
	/**
	 * Holds an instance for a session adapter to store data to session
	 * 
	 * @var tx_pttools_sessionStorageAdapter
	 */
	private $sessionAdapter = null;
	
	
	
	/**
	 * Injector for session adapter
	 *
	 * @param tx_pttools_sessionStorageAdapter $sessionAdapter
	 */
	public function injectSessionAdapter(tx_pttools_sessionStorageAdapter $sessionAdapter) {
		$this->sessionAdapter = $sessionAdapter;
		
	}
	
	
	
	/**
	 * Persists a given object to session
	 *
	 * @param Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface $object
	 */
	public function persistToSession(Tx_PtExtlist_Domain_StateAdapter_SessionPersistableInterface $object) {
		$sessionNamespace = $object->getObjectNamespace();
		tx_pttools_assert::isNotEmptyString($sessionNamespace, array('message' => 'Object namespace must not be empty! 1278436822'));
		$objectData = $object->persistToSession();
		$this->persistObjectDataToSessionByNamespace($sessionNamespace, $objectData);
	}
	
	

	/**
	 * Loads session data into given object
	 *
	 * @param Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface $object   Object to inject session data into
	 */
	public function loadFromSession(Tx_PtExtlist_Domain_StateAdapter_SessionPersistableInterface $object) {
		$objectNamespace = $object->getObjectNamespace();
		tx_pttools_assert::isNotEmptyString($objectNamespace, array('message' => 'object namespace must not be empty! 1278436823'));
		$objectData = $this->getSessionDataByNamespace($objectNamespace);
		$object->injectSessionData($objectData);
	}
	


    /**
	 * Persists a given array to session into a given namespace
	 *
	 * @param String $objectNamespace
	 * @param array $objectData
	 */
	private function persistObjectDataToSessionByNamespace($objectNamespace, $objectData) {
		$this->sessionAdapter->store($objectNamespace, $objectData);
	}
	
	
	/**
	 * Returns data from session for given namespace
	 *
	 * @param string $objectNamespace
	 * @return array
	 */
	private function getSessionDataByNamespace($objectNamespace) {
		$sessionData = $this->sessionAdapter->read($objectNamespace);
		/* Interface expects an array, so fix this here */
		if ($sessionData == null) {
			$sessionData = array();
		}
	    return $sessionData;	
	}
	
}

?>