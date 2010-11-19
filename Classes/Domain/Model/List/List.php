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
 * Class implementing container for all list elements, e.g. listData, filters, column descriptions...
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Domain
 * @subpackage Model\List
 */
class Tx_PtExtlist_Domain_Model_List_List {
	
	/**
	 * Holds a reference of the list data object holding all list data
	 * @var Tx_PtExtlist_Domain_Model_List_ListData
	 */
	protected $listData;
	
	
	
	/**
	 * A reference to the header data.
	 * @var Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 */
	protected $listHeader;
	

	
	/**
	 * A List Data Object holding the aggregate rows
	 * @var Tx_PtExtlist_Domain_Model_List_ListData
	 */
	protected $aggregateRows;
	
	
	
    /**
     * Getter for list data. 
     *
     * @return Tx_PtExtlist_Domain_Model_List_ListData      Returns list data of this list
     */	
	public function getListData() {
		return $this->listData;
	}
	
	
	
	/**
	 * Setter for aggregate rows
	 * @param Tx_PtExtlist_Domain_Model_List_ListData  $listData   List data object holding aggregate rows
	 */
	public function setAggregateRows(Tx_PtExtlist_Domain_Model_List_ListData $aggregates) {
		$this->aggreagteRows = $aggregates;
	}
	
	
	
	/**
	 * Setter for list data
	 * @param Tx_PtExtlist_Domain_Model_List_ListData  $listData   List data to be set for this list object
	 */
	public function setListData(Tx_PtExtlist_Domain_Model_List_ListData $listData) {
		$this->listData = $listData;
	}
	
	
	
	/**
	 * 
	 * Setter for list header.
	 * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader
	 */
	public function setListHeader(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader) {
		$this->listHeader = $listHeader;
	}
	
	
	
	/**
	 * 
	 * Getter for list header.
	 * @return Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 */
	public function getListHeader() {
		return $this->listHeader;
	}
	
	
	
	/**
	 * Getter for aggregate rows
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getAggregateRows() {
		return $this->aggregateRows;
	}
}

?>