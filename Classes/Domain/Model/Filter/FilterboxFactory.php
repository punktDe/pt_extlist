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
 * Class implements factory for filter boxes
 * 
 * @author Michael Knoll <mimi@kaktusteam.de>
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory {

	/**
	 * Factory method for filter boxes. Returns filterbox for a given filterbox configuration and list identifier
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig $filterboxConfiguration
	 * @return Tx_PtExtlist_Domain_Model_Filter_Filterbox
	 */
	public static function createInstance(Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig $filterboxConfiguration) {
	   	Tx_PtExtbase_Assertions_Assert::isNotEmptyString($filterboxConfiguration->getListIdentifier(), array('message' => 'List identifier must not be empty 1277889458'));
		$filterbox = new Tx_PtExtlist_Domain_Model_Filter_Filterbox($filterboxConfiguration);
		foreach ($filterboxConfiguration as $filterConfiguration) {
			$filter = Tx_PtExtlist_Domain_Model_Filter_FilterFactory::createInstance($filterConfiguration);
			$filter->injectFilterbox($filterbox);
			$filterbox->addFilter($filter,$filter->getFilterIdentifier());
		}

        $sessionPersistenceManager = Tx_PtExtbase_State_Session_SessionPersistenceManagerFactory::getInstance();
        $sessionPersistenceManager->registerObjectAndLoadFromSession($filterbox);

		return $filterbox;
	}
	
	
	
	/**
	 * Factory method for filter boxes. Returns only accessible filters from a given filterbox.
	 * 
	 * @param Tx_PtExtlist_Domain_Model_Filter_FilterBox $collection
	 * @return Tx_PtExtlist_Domain_Model_Filter_Filterbox
	 */
	public static function createAccessableInstance(Tx_PtExtlist_Domain_Model_Filter_FilterBox $collection) {
		$accessibleCollection = new Tx_PtExtlist_Domain_Model_Filter_Filterbox();
		$accessibleCollection->injectFilterboxConfiguration($collection->getFilterboxConfiguration());
		
		foreach($collection as $filter) {
			if($filter->getFilterConfig()->isAccessable()) {
				$accessibleCollection->addFilter($filter, $filter->getFilterIdentifier());
			} 		
		}
		return $accessibleCollection;
	}	
}
?>