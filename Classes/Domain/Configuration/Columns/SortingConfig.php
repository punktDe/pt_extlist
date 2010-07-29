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

class Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig {
	
	protected $field; 
	
	protected $direction;
	
	/**
	 * if this is set to true, the direction cannot be changed 
	 * 
	 * @var bool
	 */
	protected $forceDirection;
	
	public function __construct($field, $direction, $forceDirection) {
		$this->direction = $direction;
		$this->field = $field; 
		$this->forceDirection = $forceDirection;
	}
	
	public function setDirection($direction) {
		if($this->forceDirection == false) {
			$this->direction = $direction;
		}
	}
	
	public function getDirection() {
		return $this->direction;
	}
	
	public function getForceDirection() {
		return $this->forceDirection;
	}
	
	public function getField() {
		return $this->field;
	}
}
?>