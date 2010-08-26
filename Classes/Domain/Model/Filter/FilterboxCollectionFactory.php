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
class Tx_PtExtlist_Domain_Model_Filter_FilterboxCollectionFactory {
	
	/**
	 * Factory method for creating filterbox collection for a given filterbox config collection
	 * and a given list identifier
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfigCollection $filterboxConfigCollection
	 * @param String $listIdentifier Identifier of the list to which this filterbox collection belongs to
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection
	 */
	public static function createInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$filterboxConfigCollection = $configurationBuilder->buildFilterConfiguration(); 
		$filterboxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection($configurationBuilder);
		foreach($filterboxConfigCollection as $filterboxConfiguration) { /* @var $filterboxConfiguration Tx_PtExtlist_Domain_Configuration_Filter_FilterboxConfiguration */
			$filterbox = Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory::createInstance($filterboxConfiguration);

			$filterboxCollection->addFilterBox($filterbox, $filterbox->getfilterboxIdentifier());
		}

		return $filterboxCollection;
	}
	
}

?>