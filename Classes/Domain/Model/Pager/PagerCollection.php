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
 * A collection to manage a bunch of pagers.
 * 
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * @package pt_extlist
 * @subpackage Pager
 */
class Tx_PtExtlist_Domain_Model_Pager_PagerCollection extends tx_pttools_collection {

	/**
	 * Holds the current page index.
	 * New pagers need to know the current page.
	 * 
	 * @var int
	 */
	protected $currentPage;
	
	
	
	/**
	 * Adds a pager to the collection.
	 * 
	 * @param Tx_PtExtlist_Domain_Model_Pager_PagerInterface $pager
	 */
	public function addPager(Tx_PtExtlist_Domain_Model_Pager_PagerInterface $pager) {
		$pager->setCurrentPage($this->currentPage);
		$this->addItem($pager, $pager->getPagerIdentifier());
	}
	
	
	
	/**
	 * Sets the current page index to all pagers.
	 *
	 * @param int $pageIndex
	 */
	public function setCurrentPage($pageIndex) {
		$this->currentPage = $pageIndex;
		
		foreach($this->itemsArr as $id => $pager) {
			$pager->setCurrentPage($pageIndex);
		}
	}
	
	
	
	/**
	 * Returns the current page which is valid for all pagers.
	 *
	 * @return int
	 */
	public function getCurrentPage() {
		return $this->currentPage;
	}
}

?>