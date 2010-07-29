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

class Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollectionFactory {
	
	/**
	 * Parse the sorting config string and build sorting config objects 
	 * @param $sortingSettings string
	 * @return Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollection
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 28.07.2010
	 */
	public static function getInstanceBySortingSettings($sortingSettings) {

		$sortingConfigCollection = new Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollection();
		
		$sortingFields = t3lib_div::trimExplode(',', $sortingSettings);
		foreach($sortingFields as $sortingField) {
			$sortingFieldOptions = t3lib_div::trimExplode(' ', $sortingField);
			
			$fieldName  = $sortingFieldOptions[0];
			if($fieldName) {
				$sortingDir = strtolower($sortingFieldOptions[1]);
				$forceSortingDir = false;
				
				if(!in_array($sortingDir, array('asc','desc'))) {
				} elseif(!in_array($sortingDir, array('!asc','!desc'))) {
						$forceSortingDir = true;
						$sortingDir = substr($sortingDir,1);
				} else {
					$sortingDir = 'asc';
				}

				$sortingConfig = new Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig($fieldName, $sortingDir, $forceSortingDir);
				$sortingConfigCollection->addSortingField($sortingConfig, $fieldName);
			}
		}
		
		return $sortingConfigCollection;
	}
	
}
?>