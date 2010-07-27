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
 * ColumnConfigCollectionFactory for ColumnConfig Objects 
 *
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollectionFactory {
	
	/**
	 * Build and return ColumnConfigurationCollection
	 *  
	 * @param $columnSettings typoscript array of column Collection
	 * @return Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
	 * @author Daniel Lienert <lienert@punkt.de>
	 */
	public static function getColumnConfigCollection($columnSettings) {
		$columnConfigCollection = self::buildColumnConfigCollection($columnSettings);
		return $columnConfigCollection;
	}
	
	/**
	 * @param $columnSettings
	 * @return Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
	 * @author Daniel Lienert <lienert@punkt.de>
	 */
	protected static function buildColumnConfigCollection(array $columnSettings) {
		
		$columnConfigCollection = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection();

		foreach($columnSettings as $columnId => $columnSetting) {
			$columnConfigCollection->addColumnConfig($columnId, new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($columnSetting));
		}
		
		return $columnConfigCollection;
	}
}
?>