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
 * Class implements factory for filter boxes
 * 
 * @author Michael Knoll <mimi@kaktusteam.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory {

	/**
	 * Factory method for filter boxes. Returns filterbox for a given filterbox configuration and list identifier
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig $filterboxConfiguration
	 * @return Tx_PtExtlist_Domain_Model_Filter_Filterbox
	 */
	public static function createInstance(Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig $filterboxConfiguration) {
	   	tx_pttools_assert::isNotEmptyString($filterConfiguration->getListIdentifier(), array('message' => 'List identifier must not be empty 1277889458'));
		$filterbox = new Tx_PtExtlist_Domain_Model_Filter_Filterbox($filterboxConfiguration);
		foreach ($filterboxConfiguration as $filterConfiguration) {
			$filter = Tx_PtExtlist_Domain_Model_Filter_FilterFactory::createInstance($filterConfiguration);
			// TODO CodeMonkey: Add method to collection for adding filters to filterbox ;)
			$filterbox->addItem($filter,$filter->getFilterIdentifier());
		}
		return $filterbox;
	}
	
}



?>