<?php
/***************************************************************
* Copyright notice
*
*   2010 Daniel Lienert <daniel@lienert.cc>, Michael Knoll <mimi@kaktusteam.de>
* All rights reserved
*
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/


/**
 * Class implements default pager for pt_extlist
 *
 * @package Domain
 * @subpackage Model\Pager
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Model_Pager_DeltaPager extends Tx_PtExtlist_Domain_Model_Pager_DefaultPager {
	
	/**
	 * @var int
	 */
	protected $delta;
	
	
	public function __construct($pagerConfiguration) {
		parent::__construct($pagerConfiguration);
		
		$this->delta = (int) $this->pagerConfiguration->getSettings('delta');
	}
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getPages()
	 */
	public function getPages() {

		$pageCount = $this->getPageCount();
		
		if($this->delta == 0 || $this->delta >= $pageCount) return parent::getPages();
		
		$pages = array();
		
		for($i=1; $i <= $pageCount; $i++) {
			
			if($i == $pageCount && $this->getUseBackFill()) {
				$pages['bfi'] = $this->pagerConfiguration->getSettings('fillItem');
			}
			
			if($i==1 || $i == $pageCount|| abs($i - $this->currentPage) <= $this->delta) {
				$pages[$i] = $i;			
			}
			
			if($i == 1 && $this->getUseFrontFill()) {
				$pages['ffi'] = $this->pagerConfiguration->getSettings('fillItem');
			}
			
		}

		return $pages;
	}
	
	
	
	/**
	 * Indicates if the fill string is inserted before delta
	 * @return bool
	 */
	public function getUseFrontFill() {
		return $this->currentPage - $this->delta > 2;
	}
	
	
	
	/**
	 * Indicates if the fill string is inserted after delta
	 * @return bool
	 */
	public function getUseBackFill() {
		return $this->currentPage + $this->delta < $this->getPageCount() - 1;
	}
	
}

?>