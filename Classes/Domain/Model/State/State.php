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
 * DB Model to store the current list state when using it without session.
 *
 * @package Domain
 * @subpackage Model\State
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Model_State_State extends Tx_Extbase_DomainObject_AbstractEntity {
	
	/**
	 * hash
	 * @var string
	 * @validate NotEmpty
	 */
	protected $hash;
	
	/**
	 * shortcode
	 * @var string
	 */
	protected $shortcode;
	
	/**
	 * statedata
	 * @var string
	 */
	protected $statedata;
	
	
	
	/**
	 * Setter for hash
	 *
	 * @param string $hash hash
	 * @return void
	 */
	public function setHash($hash) {
		$this->hash = $hash;
	}

	/**
	 * Getter for hash
	 *
	 * @return string hash
	 */
	public function getHash() {
		return $this->hash;
	}
	
	/**
	 * Setter for shortcode
	 *
	 * @param string $shortcode shortcode
	 * @return void
	 */
	public function setShortcode($shortcode) {
		$this->shortcode = $shortcode;
	}

	/**
	 * Getter for shortcode
	 *
	 * @return string shortcode
	 */
	public function getShortcode() {
		return $this->shortcode;
	}
	
	/**
	 * Setter for statedata
	 *
	 * @param mixed $statedata statedata
	 * @return void
	 */
	public function setStatedata($statedata) {
		$this->statedata = $statedata;
	}
	
	
	
	/**
	 * Calculate the state hash by given dataString 
	 * 
	 * @param string $stateData
	 * @return string hash
	 */
	public function calculateStateHash($stateData) {
		return md5($stateData);
	}
	

	
	/**
	 * Getter for statedata
	 *
	 * @return string statedata
	 */
	public function getStatedata() {
		return $this->statedata;
	}
	
	
	/**
	 * Set the stateData  
	 *
	 * @param array $stateData
	 */
	public function setStateDataByArray(array $stateData) {
		$this->setStatedata(serialize($stateData));
	}
	
	
	/**
	 * return the statedata as array
	 * 
	 * @return array stateData
	 */
	public function getStateDataAsArray() {
		if($this->statedata) {
			return unserialize($this->statedata);	
		} else {
			return array();
		}
	}
}
?>