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
 * Class implements a collection of aggregate configurations.
 * 
 * @package Domain
 * @subpackage Configuration\Data\Aggregates
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection extends Tx_PtExtbase_Collection_ObjectCollection {
	
	/**
	 * This collection is restricted to objects of type Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig
	 *
	 * @var string
	 */
	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig';
	
	
	
	/**
	 * Adds a aggregate configuration object to collection
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateConfig
	 */
	public function addAggregateConfig(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateConfig) {
		$this->addItem($aggregateConfig, $aggregateConfig->getIdentifier());
	}
	
	
	
	/**
	 * Returns a aggregate configuration object for a given identifier
	 *
	 * @param string $identifier
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig
	 */
	public function getAggregateConfigByIdentifier($identifier) {
		if ($this->hasItem($identifier)) {
			return $this->getItemById($identifier);
		} else {
			throw new Exception('Aggregate configuration for key ' . $identifier . ' does not exist! 1282893634');
		}
	}
	
	
	/**
	 * Extract the items with the given scope
	 * 
	 * @param string $scope
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection
	 */
	public function extractCollectionByScope($scope) {
		$collection = new Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection;
		foreach($this->itemsArr as $itemKey => $item) {
			if($item->getScope() == $scope) {
				$collection->addAggregateConfig($item,$itemKey);
			}
		}
		return $collection;
	}
	
}

?>