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
 * Class implements adapter to store the plugins state into the database 
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Domain
 * @subpackage StateAdapter\Storage
 */
class Tx_PtExtlist_Domain_StateAdapter_Storage_DBStorageAdapter implements tx_pttools_iStorageAdapter {

	
	/**
	 * @var Tx_PtExtlist_Domain_Repository_State_StateRepository
	 */
	protected $stateRepository;
	
	
	/**
	 * @var Tx_PtExtlist_Domain_SessionPersistence_SessionPersistenceManager
	 */
	protected $sessionPersistanceManager;
	
	
	
	/**
	 * MD5 sum identifying the state to load from database 
	 * 
	 * @var string
	 */
	protected $stateHash;
	
	
	
	/**
	 * The object holding the current state
	 * 
	 * @var Tx_PtExtlist_Domain_Model_State_State
	 */
	protected $state;
	
	
	
	/**
	 * Inject the state repository
	 * 
	 * @param Tx_PtExtlist_Domain_Repository_State_StateRepository $stateRepository
	 */
	public function injectStateRepository(Tx_PtExtlist_Domain_Repository_State_StateRepository $stateRepository) {
		$this->stateRepository = $stateRepository;
	}
	
	
	
	/**
	 * Inject the sessionPersistanceManager
	 * 
	 * @param Tx_PtExtlist_Domain_SessionPersistence_SessionPersistenceManager $sessionPersistanaceManager
	 */
	public function injectSessionPersistanceManager(Tx_PtExtlist_Domain_SessionPersistence_SessionPersistenceManager $sessionPersistanaceManager) {
		$this->sessionPersistanceManager = $sessionPersistanaceManager;
	}
	
	
	
	/**
	 * Set the statehash 
	 * 
	 * @param string $stateHash
	 */
	public function setStateHash($stateHash) {
		$this->stateHash = $stateHash;
	}
	
	
	
	/**
	 * Init method.
	 */
	public function init() {
		$this->state = $this->loadStateObject($this->stateHash);
	}
	
	
	
	/**
	 * Load a stateobject from db or create a new one
	 * 
	 * @param string $stateHash
	 * @return Tx_PtExtlist_Domain_Model_State_State
	 */
	protected function loadStateObject($stateHash) {
		$state = NULL;
		
		if($stateHash) {
			$state = $this->stateRepository->findOneByHash($stateHash);	
		}
		
		if(!$state) {
			$state = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Model_State_State'); 
			$this->stateRepository->add($state);
		}
		
		return $state;
	}
	
	
	
	/**
	 * Retrieve a state dataObject from the repository and return the requested value
	 * 
	 * @param string $key
	 */
	public function read($key) {
		$stateData = $this->state->getStateDataAsArray();
		return $stateData[$key];
	}
	
	
	
	/**
	 * Save a value to state data
	 * 
	 * @param string $key
	 * @param string $value
	 */
	public function store($key, $value) {
		/* TODO: the extlist save only one value to the session when the lifecycle ends (the internal session cache)
		 * because of that, the session hash is used in links before the session is written to database. that means, in this
		 * mode only one value can be written to the session (Daniel)
		 */
		//$stateData = $this->state->getStateDataAsArray();
		$stateData[$key] = $value;
		$this->state->setStateDataByArray($stateData);
		$this->state->setHash(md5(serialize($value)));
		$this->stateRepository->update($this->state);
		
		$persistenceManager = Tx_Extbase_Dispatcher::getPersistenceManager();
		$persistenceManager->persistAll();
	}
	
	
	
	/**
	 * Remove a value from state data
	 * 
	 * @param string $key
	 */
	public function delete($key) {
		$stateData = $this->state->getStateDataAsArray();
		unset($stateData[$key]);
		$this->state->setStateDataByArray($stateData);
	}
}
?>