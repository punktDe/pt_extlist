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
 * Class implementing container for all list elements, e.g. listData, filters, column descriptions...
 * 
 * @author Michael Knoll 
 * @author Christoph Ehscheidt 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\List
 */
class Tx_PtExtlist_Domain_Model_List_List {
	
	/**
	 * Holds a reference of the list data object holding all list data
	 * @var Tx_PtExtlist_Domain_Model_List_ListData
	 */
	protected $listData = NULL;


	/**
	 * @var Tx_PtExtlist_Domain_Model_List_IterationListDataInterface
	 */
	protected $iterationListData = NULL;


	/**
	 * A reference to the header data.
	 * @var Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 */
	protected $listHeader;

	
	/**
	 * A List Data Object holding the aggregate rows
	 * @var Tx_PtExtlist_Domain_Model_List_ListData
	 */
	protected $aggregateListData;


	/**
	 * @var Tx_PtExtlist_Domain_Renderer_RendererChain
	 */
	protected $rendererChain;


	/**
	 * @var boolean
	 */
	protected $useIterationListData = FALSE;
	
	
    /**
     * Getter for list data. 
     *
     * @return Tx_PtExtlist_Domain_Model_List_ListData      Returns list data of this list
     */	
	public function getListData() {
		return $this->listData;
	}


	/**
	 * @return \Tx_PtExtlist_Domain_Model_List_IterationListDataInterface
	 */
	public function getIterationListData() {
		return $this->iterationListData;
	}
	
	
	/**
	 * Setter for aggregate rows
	 * @param Tx_PtExtlist_Domain_Model_List_ListData  $aggregateListData   List data object holding aggregate rows
	 */
	public function setAggregateListData(Tx_PtExtlist_Domain_Model_List_ListData $aggregateListData) {
		$this->aggregateListData = $aggregateListData;
	}
	
	
	
	/**
	 * Setter for list data
	 * @param Tx_PtExtlist_Domain_Model_List_ListData  $listData   List data to be set for this list object
	 */
	public function setListData(Tx_PtExtlist_Domain_Model_List_ListData $listData) {
		$this->listData = $listData;
	}


	/**
	 * @param Tx_PtExtlist_Domain_Model_List_IterationListDataInterface $iterationListData
	 */
	public function setIterationListData(Tx_PtExtlist_Domain_Model_List_IterationListDataInterface $iterationListData) {
		$this->iterationListData = $iterationListData;
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
	public function getAggregateListData() {
		return $this->aggregateListData;
	}


	/**
	 * @return Traversable
	 */
	public function getRenderedListData() {
		if($this->useIterationListData === TRUE) {
			return $this->iterationListData;
		} else {
			return $this->rendererChain->renderList($this->listData);
		}
	}


	/**
	 * @return int
	 */
	public function count() {
		if($this->useIterationListData) {
			return $this->iterationListData->count();
		} else {
			return $this->listData->count();
		}
	}


	/**
	 * @return Tx_PtExtlist_Domain_Model_List_Row
	 */
	public function getRenderedListHeader() {
		return $this->rendererChain->renderCaptions($this->listHeader);
	}


	/**
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getRenderedAggregateListData() {
		return $this->rendererChain->renderAggregateList($this->aggregateListData);
	}


	/**
	 * @param \Tx_PtExtlist_Domain_Renderer_RendererChain $rendererChain
	 */
	public function setRendererChain($rendererChain) {
		$this->rendererChain = $rendererChain;
	}


	/**
	 * @return \Tx_PtExtlist_Domain_Renderer_RendererChain
	 */
	public function getRendererChain() {
		return $this->rendererChain;
	}

	/**
	 * @param boolean $useIterationListData
	 */
	public function setUseIterationListData($useIterationListData) {
		$this->useIterationListData = $useIterationListData;
	}

	/**
	 * @return boolean
	 */
	public function getUseIterationListData() {
		return $this->useIterationListData;
	}
}

?>