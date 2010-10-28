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
 * Class implements factory for complete list header
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Domain
 * @subpackage \Model\List\Header
 */
class Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory {
	
	/**
	 * build the listheader, a collection of columnheader objects
	 * 
	 * @param $configurationBuilder Tx_PtExtlist_Domain_Model_List_ListData
	 * @return Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 */
	public static function createInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		
		$defaultSortingColumn = $configurationBuilder->buildListDefaultConfig()->getSortingColumn();
		$columnConfigurationCollection = $configurationBuilder->buildColumnsConfiguration();
		$listHeader = new Tx_PtExtlist_Domain_Model_List_Header_ListHeader($configurationBuilder->getListIdentifier());
		
		$listIsSorted = 0;
		
		foreach($columnConfigurationCollection as $columnIdentifier => $singleColumnConfiguration) {
			$headerColumn = Tx_PtExtlist_Domain_Model_List_Header_HeaderColumnFactory::createInstance($singleColumnConfiguration);
			
			if($singleColumnConfiguration->isAccessable()) {
				$listIsSorted += $headerColumn->getSortingState();
				$listHeader->addHeaderColumn($headerColumn, $singleColumnConfiguration->getColumnIdentifier());
			}
		}

		if(!$listIsSorted && $defaultSortingColumn && $listHeader->hasItem($id)) {
			$listHeader->getHeaderColumn($defaultSortingColumn)->setSortingState(1);
			$listHeader->getHeaderColumn($defaultSortingColumn)->init();
		}
		
		return $listHeader;
	}
	
	
	
}
?>