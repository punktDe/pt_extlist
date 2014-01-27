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


	/**
	 * @var int
	 */
	protected $firstItemDelta = 0;


	/**
	 * @var int
	 */
	protected $lastItemDelta = 0;



	public function __construct($pagerConfiguration) {
		parent::__construct($pagerConfiguration);
		
		$this->delta = (int) $this->pagerConfiguration->getSettings('delta');
		$this->firstItemDelta = (int) $this->pagerConfiguration->getSettings('firstItemDelta');
		$this->lastItemDelta = (int) $this->pagerConfiguration->getSettings('lastItemDelta');
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
			
			if(	$i == 1																				// First Element is always visible
				|| $i <= 1 + $this->firstItemDelta && $this->currentPage == 1						// lastItemDelta before last item is visible
				|| $i >= $pageCount - $this->lastItemDelta && $this->currentPage == $pageCount		// firstItemDelta after first item is visible
				|| $i == $pageCount																	// Last element is always visible
				|| abs($i - $this->currentPage) <= $this->delta)									// Delta before and after the selected is visible
			{
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
		return $this->fillIsNeeded() && $this->currentPage - $this->delta > 2;
	}
	
	
	
	/**
	 * Indicates if the fill string is inserted after delta
	 * @return bool
	 */
	public function getUseBackFill() {
		return $this->fillIsNeeded() && $this->currentPage + $this->delta < $this->getPageCount() - 1;
	}


	/**
	 * Check overlapping delta and if we need a fill item
	 * @return bool
	 */
	protected function fillIsNeeded() {
		return $this->getPageCount() - (3 + 2 * $this->delta + $this->lastItemDelta + $this->firstItemDelta) > 0;
	}
}

?>