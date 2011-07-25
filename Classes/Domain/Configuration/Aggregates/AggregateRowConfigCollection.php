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
 * collection of aggregate row configs
 *
 * @package Domain
 * @subpackage Configuration\Aggregates
 */
class Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfigCollection extends Tx_PtExtbase_Collection_ObjectCollection {

	/**
	 * @var string
	 */
	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfig';
	
	
	
	/**
	 * Add rowconfig to collection
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfig $aggregateRowConfig
	 * @param integer $rowId
	 */
	public function addAggregateRowConfig(Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfig $aggregateRowConfig, $rowId) {
		$this->addItem($aggregateRowConfig, $rowId);
	}
	
	
	
	/** 
	 * @param int $rowId
	 * @return Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfig
	 */
	public function getAggregateRowConfig($rowId) {
		if($this->hasItem($rowId)) {
			return $this->getItemById($rowId);
		} else {
			throw new Exception('The aggregate row with id ' . $rowId . ' does not exist! 1282922836');
		}
	}
	
}

?>