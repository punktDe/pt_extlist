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
 * Factory to put all parts of a list together.
 * 
 * TODO refactor this! Should be stateless class (STATIC)!
 * 
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 */
class Tx_PtExtlist_Domain_Model_List_ListFactory {

	/**
	 * TODO Remove object-variables (factory must be static!)
	 */
	protected $dataBackend;
	
	

	/**
	 * TODO Remove object-variables (factory must be static!)
	 */
	protected $configurationBuilder;
	
	
	
	/**
	 * TODO no constructor for static class! make this static!
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->configurationBuilder = $configurationBuilder;
		$this->dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilder);
	}
	
	
	
	/**
	 * TODO remove this! Factory must be static!
	 * Overrides the data backend.
	 * @param Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend $dataBackend
	 */
	public function injectDataBackend(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend) {
		$this->dataBackend = $dataBackend;
	}
	
	
	
	/**
	 * TODO make this static!
	 * Returns a full featured list object.
	 * 
	 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
	 * @return Tx_PtExtlist_Domain_Model_List_List
	 */
	public function createList() {
		$listData = $this->dataBackend->getListData();
		$listHeader = Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory::createInstance($this->configurationBuilder);
		$listColumnConfig = $this->configurationBuilder->buildColumnsConfiguration();
		
		
		$list = new Tx_PtExtlist_Domain_Model_List_List();
		$list->setListData($listData);
		$list->setListHeader($listHeader);
		$list->setColumnConfig($listColumnConfig);
		
		return $list;
	}
	
}

?>