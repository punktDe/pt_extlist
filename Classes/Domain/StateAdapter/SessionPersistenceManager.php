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
 * @author Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager implements Tx_PtExtlist_Domain_Lifecycle_LifecycleEventInterface {
	
	/**
	 * Holds an instance for a session adapter to store data to session
	 * 
	 * @var tx_pttools_sessionStorageAdapter
	 */
	private $sessionAdapter = null;
	
	
	
	/**
	 * Holds cached session data.
	 * 
	 * @var array
	 */
	private $sessionData = array();
	
	
	
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
	    
		if ($objectData == null) {
            $objectData = array();
        }
        
        if ($this->sessionData == null) {
        	$this->sessionData = array();
        }
        
        // TODO adding keys to namespace array should go into utility class!
        $this->sessionData = $this->addKeysToArray($sessionNamespace, $this->sessionData);
		$this->sessionData = Tx_PtExtlist_Utility_NameSpaceArray::saveDataInNamespaceTree($sessionNamespace, $this->sessionData, $objectData);
	}
	
	
	
	/**
	 * Adds keys to an array given by namespace string (dot-seperated)
	 * 
	 * TODO this method should go into namespace array utility class
	 *
	 * @param string $keyString
	 * @param array $array
	 * @return array
	 */
	protected function addKeysToArray($keyString, $array) {
		$keysArray = explode('.', $keyString);
		$pointer = &$array;
		foreach($keysArray as $key) {
			if (!array_key_exists($key, $pointer)) {
			    $pointer[$key] = array();
			}
			$pointer = &$pointer[$key];
		}
		return $array;
	}
	
	

	/**
	 * Loads session data into given object
	 *
	 * @param Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface $object   Object to inject session data into
	 */
	public function loadFromSession(Tx_PtExtlist_Domain_StateAdapter_SessionPersistableInterface $object) {
		$objectNamespace = $object->getObjectNamespace();
		tx_pttools_assert::isNotEmptyString($objectNamespace, array('message' => 'object namespace must not be empty! 1278436823'));

		$objectData = Tx_PtExtlist_Utility_NameSpaceArray::getArrayContentByArrayAndNamespace($this->sessionData, $objectNamespace);
		
		if (is_array($objectData)) {
			$object->injectSessionData($objectData);
		}

	}
	
	
	
	/**
	 * Persist the cached session data.
	 * 
	 */
	public function persist() {
		$this->sessionAdapter->store('pt_extlist.cached.session', $this->sessionData);
	}
	
	
	
	/**
	 * Read the session data into the cache.
	 * 
	 */
	public function read() {
		$this->sessionData = $this->sessionAdapter->read('pt_extlist.cached.session');
	}
	
	
	
	/**
	 * React on lifecycle events.
	 * 
	 * @param int $state
	 */
	public function lifecycleUpdate($state) {
		switch($state) {
			case Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START:
				$this->read();
				break;
			case Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::END:
				$this->persist();
				break;
			
		}
	}

	
	
	/**
	 * Returns data from session for given namespace
	 *
	 * TODO test me!
	 * 
	 * @param string $objectNamespace
	 * @return array
	 */
	public function getSessionDataByNamespace($objectNamespace) {
		return Tx_PtExtlist_Utility_NameSpaceArray::getArrayContentByArrayAndNamespace($this->sessionData, $objectNamespace);
	}
	
	
	
	/**
	 * Remove session data by given namespace
	 * 
	 * @param $objectNamespace string
	 */
	public function removeSessionDataByNamespace($objectNamespace) {
		$this->sessionAdapter->delete($objectNamespace);
	}
	
}

?>