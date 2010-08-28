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
 * Class implements factory the list of aggregate rows
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package pt_extlist
 * @subpackage \Domain\Model\List\Aggregates
 */
class Tx_PtExtlist_Domain_Model_List_Aggregates_AggregateListFactory {
	
	
	/**
	 * Get defined aggregate rows as list
	 * 
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public static function getAggregateList(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		
		
	}
	
	
	/**
	 * Build the aggregate list
	 * 
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	protected static function buildAggregateList(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$aggregateRowConfigurations = $configurationBuilder->buildAggregateRowConfig();
		
		foreach($aggregateRowConfigurations as $aggregateRowConfiguration) {
			
		}
	}
	
	
	
	/**
	 * Build the aggregate row
	 * 
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 * @param Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfig $aggregateRowConfiguration
	 */
	public static function buildAggregateRow(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend, Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfig $aggregateRowConfiguration) {
		$columnConfig = $
	}
	
}
?>