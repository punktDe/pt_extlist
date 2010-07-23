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
 * 
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_QueryObject_OrCriteria extends Tx_PtExtlist_Domain_QueryObject_Criteria {

	protected $criteria1;
	
	
	
	protected $criteria2;
	
	
	
	public function __construct(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria1, Tx_PtExtlist_Domain_QueryObject_Criteria $criteria2) {
		$this->criteria1 = $criteria1;
		$this->criteria2 = $criteria2;
	}
	
	
	
	public function getCriteria1() {
		return $this->criteria1;
	}
	
	
	
	public function getCriteria2() {
		return $this->criteria2;
	}
	
}
 
?>