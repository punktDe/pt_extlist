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
 * Class implements factory for array aggregator
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package pt_extlist
 * @subpackage \Domain\Model\List\Aggregates
 */
class Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregatorFactory {

	/**
	 * Holds a single instance of an array agregator
	 * 
	 * @var Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator
	 */
	private static $instance = null;
	
	
	/**
	 *  build the arrayAgregator
	 * 
	 * @param $configurationBuilder Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 * @return Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator
	 */
	public static function createInstance(Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator $listData) {
		
		if(!self::$instance) {
			self::$instance = new Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator();
			self::$instance->injectListData($listData);	
		}
		
		return self::$instance;
	}
}
?>