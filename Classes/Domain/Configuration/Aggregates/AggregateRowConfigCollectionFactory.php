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
 * AggregateRowConfigCollectionFactory for Aggregate row config collection Objects 
 *
 * @package Domain
 * @subpackage Configuration\Aggregates
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfigCollectionFactory {
	

    /**
	 * Build and return an AggregateRowConfigCollection
	 *  
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $columnSettings typoscript array of column Collection
	 * @return Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
	 */
	public static function getInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		return self::buildAggregateRowConfigCollection($configurationBuilder);	
	}
	
	
	
	/**
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfigCollection
	 */
	protected static function buildAggregateRowConfigCollection(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		
		$aggregateRowSettings = $configurationBuilder->getSettingsForConfigObject('aggregateRows');
		ksort($aggregateRowSettings);
		
		$aggregateRowConfigCollection = new Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfigCollection();

		foreach($aggregateRowSettings as $rowId => $rowSetting) {
			$aggregateRowConfigCollection->addAggregateRowConfig(Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfigFactory::getAggregateRowConfig($configurationBuilder, $rowSetting), $rowId);
		}
		
		return $aggregateRowConfigCollection;
	}
}
?>