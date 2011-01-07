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
 * Class implements field aggregator
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\List\Aggregates
 */
class Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator {
	
	/**
	 * Array of fielddata by column
	 * 
	 * @var array
	 */
	protected $fieldData;
	
	
	/**
	 * Reference to the data Backend
	 * 
	 * @var Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 */
	protected $dataBackend;
	
	
	
	/**
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 */
	public function injectDataBackend(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend) {
		$this->dataBackend = $dataBackend;
	}
	
	
	
	/**
	 * Return a aggregated column by method
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateConfig
	 */
	public function getAggregateByConfig(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateConfig) {
		$fieldIdentifier = $aggregateConfig->getFieldIdentifier()->getIdentifier();
		
		if(!is_array($this->fieldData[$fieldIdentifier])) {
			$this->buildFieldData($fieldIdentifier);
		}
		
		$methodName = 'getField' . ucfirst($aggregateConfig->getMethod());
		if(!method_exists($this, $methodName)) {
			throw new Exception('The array aggregate Method "' . $aggregateConfig->getMethod() . '" is not implemented 1282905192');
		}
		
		return $this->$methodName($fieldIdentifier);
	}
	
	
	/**
	 * Convert the list structure of "rows->columns->data" to an array 
	 * of 'columns->rows->data' for easy aggregate
	 * 
	 * @param string $fieldIdentifier
	 * @return array
	 */
	protected function buildFieldData($fieldIdentifier) {	
		$fieldData[$fieldIdentifier] = array();
	
		foreach($this->dataBackend->getListData() as $row) {
			$this->fieldData[$fieldIdentifier][] = $row[$fieldIdentifier]->getValue();
		}	
	}
	
	
	
	/**
	 * sum all values of a column
	 * 
	 * @param string $fieldIdentifier
	 */
	protected function getFieldSum($fieldIdentifier) {
		return array_sum($this->fieldData[$fieldIdentifier]);
	}
	
	
	/**
	 * get the average of all values of a column
	 * 
	 * @param string $fieldIdentifier
	 */
	protected function getFieldAvg($fieldIdentifier) {
		return array_sum($this->fieldData[$fieldIdentifier])/count($this->fieldData[$fieldIdentifier]);
	}
	
	
	/**
	 * maximum of all values of a column
	 * 
	 * @param string $fieldIdentifier
	 */
	protected function getFieldMax($fieldIdentifier) {
		return max($this->fieldData[$fieldIdentifier]);
	}
	
	
	/**
	 * minimum of all values of a column
	 * 
	 * @param string $fieldIdentifier
	 */
	protected function getFieldMin($fieldIdentifier) {
		return min($this->fieldData[$fieldIdentifier]);
	}
}
?>