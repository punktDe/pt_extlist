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
 *
 * @author Christoph Ehscheidt
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_MinFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractSingleValueFilter {

	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractFilter#initFilter()
	 */
	protected function initFilter() {

		$this->isActive = !empty($this->filterValue) ? TRUE : FALSE;
	}



	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::buildFilterCriteria()
	 */
	protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {
		$criteria = NULL;

		if ($this->isActive) {
			$columnName = $fieldIdentifier->getTableFieldCombined();
			$filterValue = intval($this->filterValue);
			$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::greaterThanEquals($columnName, $filterValue);
		}

		return $criteria;
	}



	/**
	 * Validates filter
	 *
	 * @return bool True, if filter validates
	 */
	public function validate() {
		$validation = $this->filterConfig->getSettings('validation');

		if (!$this->isActive) {
			return TRUE;
		}

		// Check whether given value is above max value set in TS
		if (array_key_exists('maxValue', $validation)
			&& intval($this->filterValue) > $validation['maxValue']) {

			// TODO localize this string!
			$this->errorMessage = 'Value is not allowed to be bigger than '.$validation['maxValue'];
			return FALSE;
		}

		// Check whether min value is below min value set in TS
		if (array_key_exists('minValue', $validation)
			&& intval($this->filterValue) < $validation['minValue']) {

			// TODO localize this string!
			$this->errorMessage = 'Value is not allowed to be smaller than '.$validation['minValue'];
			return FALSE;
		}

		return TRUE;
	}



	/**
	 * Adds some fields for rendering breadcrumbs. Values of those
	 * fields can be set in TS for filter via
	 *
	 * validation.minValue
	 * validation.maxValue
	 *
	 * @return array
	 */
	protected function getFieldsForBreadcrumb() {
		$validation = $this->filterConfig->getSettings('validation');
		$parentArray = parent::getFieldsForBreadCrumb();
		if (array_key_exists('minValue', $validation)) {
			$parentArray['minValue'] = $validation['minValue'];
		}
		if (array_key_exists('maxValue', $validation)) {
			$parentArray['maxValue'] = $validation['max'];
		}
		return $parentArray;
	}

}

?>