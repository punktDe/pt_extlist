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
 * @author Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_QueryObject_OrCriteria extends Tx_PtExtlist_Domain_QueryObject_Criteria {

	protected $firstCriteria;
	
	protected $secondCriteria;
	
	
	/** 
	 * @param $firstCriteria Tx_PtExtlist_Domain_QueryObject_Criteria
	 * @param $secondCriteria Tx_PtExtlist_Domain_QueryObject_Criteria
	 * @return void
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 28.07.2010
	 */
	public function __construct(Tx_PtExtlist_Domain_QueryObject_Criteria $firstCriteria, Tx_PtExtlist_Domain_QueryObject_Criteria $secondCriteria) {
		$this->firstCriteria = $firstCriteria;
		$this->secondCriteria = $secondCriteria;
	}
	
	/**
	 * @return Tx_PtExtlist_Domain_QueryObject_Criteria
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 28.07.2010
	 */
	public function getFirstCriteria() {
		return $this->firstCriteria;
	}
	
	
	/**
	 * @return Tx_PtExtlist_Domain_QueryObject_Criteria
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 28.07.2010
	 */	
	public function getSecondCriteria() {
		return $this->secondCriteria;
	}
}
 
?>