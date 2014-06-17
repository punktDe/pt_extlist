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
 * Class implements a filter for settings static values
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_StaticValueFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractFilter {

	/**
	 * Holds the current filter value
	 *
	 * @var string
	 */
	protected $filterValue = NULL;


	/**
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier
	 * @return Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
	 */
	protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {
		$fieldName = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($fieldIdentifier);
		$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::equals($fieldName, $this->filterValue);

		return $criteria;
	}


	/**
	 * Template method for initializing filter by TS configuration
	 */
	protected function initFilterByTsConfig() {
		$filterValue = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($this->filterConfig->getSettings('filterValue'));
		$this->filterValue = $filterValue;
	}


	/**
	 * Template method for initializing filter after setting all data
	 */
	protected function initFilter() {
		// TODO: Implement initFilter() method.
	}


	/**
	 * Sets the active state of this filter
	 */
	protected function setActiveState() {
		$this->isActive = $this->filterValue ? TRUE : FALSE;
	}


	/**
	 * Returns the current filtervalues of this filter
	 *
	 * @return variant
	 */
	public function getValue() {
		return $this->filterValue;
	}


	/**
	 * @param $filterValue
	 * @return Tx_PtExtlist_Domain_Model_Filter_AbstractSingleValueFilter
	 */
	public function setFilterValue($filterValue) {
		$this->filterValue = $filterValue;
		return $this;
	}


	/**
	 * This filter is not persisted, the value has to be set
	 * during the lifecycle
	 */
	public function persistToSession() {}
	protected function initFilterByGpVars() {}
	protected function initFilterBySession() {}
}