<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>, Christoph Ehscheidt <ehscheidt@punkt.de>
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
 * This class implements a dummy data backend for generating
 * some output for testing and development.
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_DataBackend_DummyDataBackend extends Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend {
	
	/**
	 * Constructor for dummy data backend
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		parent::__construct($configurationBuilder);
	}
	
	
	
	/**
	 * Generates dummy list data
	 *
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getListData() {
		
		
		$rawListData = $this->getListDataFromDataSource();
		
		
		$mappedListData = $this->dataMapper->getMappedListData($rawListData);
		return $mappedListData;
	}
	
	
	
	/**
	 * 
	 * Generates dummy list data and returns a wrapped list
	 * including header data.
	 * 
	 * @return Tx_PtExtlist_Domain_Model_List_List
	 */
	public function getList() {
		$rawListData = $this->getListDataFromDataSource();
		$mappedListData = $this->dataMapper->getMappedListData($rawListData);
		
		$listHeader = Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory::createInstance($this->configurationBuilder);
		
		$list = new Tx_PtExtlist_Domain_Model_List_List();
		$list->setListData($mappedListData);
		$list->setListHeader($listHeader);
		
		return $list;
	}
	
	
	
	/**
	 * Executes query on data source
	 *
	 * @return array   Raw list data array
	 */
	protected function getListDataFromDataSource() {
		
		if($this->pager->isEnabled()) {
			$startIndex = $this->pager->getFirstItemIndex();
			$endIndex = $this->pager->getLastItemIndex();
			
			return $this->dataSource->executeWithLimit($startIndex, $endIndex);
		}
		
		return $this->dataSource->execute();
	}
	
}

?>