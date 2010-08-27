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
 * Class implements field aggregator
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package pt_extlist
 * @subpackage \Domain\Model\List\Aggregates
 */
class Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator {
	
	protected $fieldData;
	
	protected $listData;
	
	/**
	 * 
	 * Enter description here ...
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $listData
	 */
	public function injectListData(Tx_PtExtlist_Domain_Model_List_ListData $listData) {
		$this->listData = $listData;
	}
	
	
	/**
	 * 
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateConfig
	 */
	public function getAggregateByConfig(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateConfig) {
		$this->buildFieldData($aggregateConfig->getFieldIdentifier());
		
		$methodName = 'getField' . ucfirst($aggregateConfig->getMethod());
		if(!method_exists($this, $methodName)) {
			throw new Exception('The array aggregate Method "' . $aggregateConfig->getMethod() . '" is not implemented 1282905192');
		}
		
		return $this->$methodName($aggregateConfig->getFieldIdentifier());
	}
	
	protected function buildFieldData($fieldIdentifier) {
		if(!is_array($this->fieldData[$fieldIdentifier])) {
			foreach($this->listData as $row) {
				$this->fieldData[$fieldIdentifier][] = $row[$fieldIdentifier]->getValue();
			}	
		}
	}
	
	
	protected function getFieldSum($fieldIdentifier) {
		return array_sum($this->fieldData[$fieldIdentifier]);
	}
	
	protected function getFieldAverage($fieldIdentifier) {
		return array_sum($this->fieldData[$fieldIdentifier])/count($this->fieldData[$fieldIdentifier]);
	}
	
	protected function getFieldMax($fieldIdentifier) {
		return max($this->fieldData[$fieldIdentifier]);
	}
	
	protected function getFieldMin($fieldIdentifier) {
		return min($this->fieldData[$fieldIdentifier]);
	}
}
?>