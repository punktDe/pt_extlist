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
 * Implements factory for filterboxe collections
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist 
 */
class Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollectionFactory {
	
	/**
	 * Factory method for creating filterbox collection for a given filterbox config collection
	 * and a given list identifier
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfigCollection $filterBoxConfigCollection
	 * @param String $listIdentifier Identifier of the list to which this filterbox collection belongs to
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollection
	 */
	public static function createInstanceByFilterBoxConfigCollectionAndListIdentifier(Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfigCollection $filterBoxConfigCollection, $listIdentifier) {
		tx_pttools_assert::isNotEmptyString($listIdentifier);
		$filterBoxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterBoxCollection($listIdentifier);
		foreach($filterBoxConfigCollection as $filterBoxConfiguration) { /* @var $filterBoxConfiguration Tx_PtExtlist_Domain_Configuration_Filter_FilterBoxConfiguration */
			$filterBox = Tx_PtExtlist_Domain_Model_Filter_FilterBoxFactory::createFilterBoxByFilterBoxConfigurationAndListIdentifier($filterBoxConfiguration, $listIdentifier);
			// TODO CodeMonkey: add appropriate method!
			$filterBoxCollection->addItem($filterBox, $filterBox->getFilterBoxIdentifier());
		}
		return $filterBoxCollection;
	}
	
}

?>