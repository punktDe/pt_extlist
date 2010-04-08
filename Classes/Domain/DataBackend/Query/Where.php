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
 * Where Object for QueryObject
 *
 * @package TYPO3
 * @subpackage pt_extlist
 */

class Tx_PtExtlist_Domain_DataBackend_Query_Where {

	protected $wherePartial;
	protected $parameters;
	protected $combinator = 'AND';

	public function __construct() {		
		return $this;
	}
	
	
	
	public function setCombinator($combinator) {
		$this->combinator = $combinator;
		return $this;
	}
	
	
	
	public function setWherePartial($wherePartial) {
		$this->wherePartial = $wherePartial;
		return $this;
	}
	
	
	
	public function setParameters($parameters) {
		if(!is_array($parameters)) {
			$this->parameters = array($parameters);
		} else {
			$this->parameters = $parameters;
		}
		return $this;
	}
	
	
	
	public function getParameters() {
		return $this->parameters;
	}
	
	
	
	public function getWherePartial() {
		return $this->wherePartial;	
	}
	
	
	
	public function getCombinator() {
		return $this->combinator;
	}
}
?>