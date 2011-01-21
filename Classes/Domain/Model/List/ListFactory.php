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
 * Factory to put all parts of a list together.
 * 
 * @author Christoph Ehscheidt 
 * @author Michael Knoll 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\List
 */
class Tx_PtExtlist_Domain_Model_List_ListFactory {
    
    /**
	 * Returns a full featured list object.
	 * 
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Model_List_List
	 */
	public static function createList(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$list = new Tx_PtExtlist_Domain_Model_List_List();
		
		$list->setListData($dataBackend->getListData());
		$list->setListHeader($dataBackend->getListHeader());
		$list->setAggregateListData(self::buildAggregateListData($dataBackend, $configurationBuilder));	
		
		return $list;
	}
	
	
	/**
	 * Build the aggregate list data if any aggregates are defined

	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public static function buildAggregateListData(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		if($configurationBuilder->buildAggregateDataConfig()->count() > 0) {
			return $dataBackend->getAggregateListData();	
		} else {
			return new Tx_PtExtlist_Domain_Model_List_ListData();
		}
	}
}
?>