<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de>
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
 * Lifecycle Manager allowes to register class wich will be notified 
 * by different lifecycle envents.
 * 
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * @package pt_extlist
 * @subpackage Lifecycle
 */
class Tx_PtExtlist_Domain_Lifecycle_LifecycleManager {

	const UNDEFINED = 0;
	const START = 1;
	const END = 10;
	
	/**
	 * Holds the state of the lifecycle.
	 * 
	 * @var int
	 */
	protected $state;
	
	/**
	 * Holds all observers which need to be updated.
	 * 
	 * @var array
	 */
	protected $observers;
	
	public function __construct() {
		$this->state = self::UNDEFINED;
	}
	
	/**
	 * Returns the current state of the lifecycle.
	 * 
	 * @return int
	 */
	public function getState() {
		return $this->state;
	}
	
	/**
	 * Updates the current lifecycle state. 
	 * If given state is not a advanced state, nothing will happen.
	 * 
	 * @param int $state
	 */
	public function updateState($state) {
		if($state <= $this->state) return;
		
//		echo "NEW LIFECYCLE STATE: ".$this->state." -> ".$state;
		$this->state = $state;
	}
	
	public function register(Tx_PtExtlist_Domain_Lifecycle_LifecycleEventInterface $observer) {
		$this->observers[] = $observer;
	}
	
	protected function fireUpdate() {
		foreach($this->observers as $observer) {
			$observer->lifecycleUpdate($this->state);
		}
	} 
}

?>