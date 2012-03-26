<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Joachim Mathes
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
 * Class implements a date picker filter
 *
 * @package Domain
 * @subpackage Model\Filter
 * @author Joachim Mathes
 */
class Tx_PtExtlist_Domain_Model_Filter_DatePickerFilter extends Tx_PtExtlist_Domain_Model_Filter_DateSelectListFilter {

	/**
	 * @return void
	 */
	public function initFilterByGpVars() {
		if (is_array($this->gpVarFilterData) && array_key_exists('filterValue',$this->gpVarFilterData) && $this->gpVarFilterData['filterValue']) {
			$dateTimeZone = new DateTimeZone(date_default_timezone_get());
			$startDateTime = new DateTime($this->gpVarFilterData['filterValue']);
			$startDateTime->setTimezone($dateTimeZone);

			$endDateTime = new DateTime($this->gpVarFilterData['filterValue']);
			$endDateTime->setTimezone($dateTimeZone);
			$endDateTime->modify('+86399 seconds');

			$this->gpVarFilterData['filterValueStart'] = $startDateTime->format('U');
			$this->gpVarFilterData['filterValueEnd'] = $endDateTime->format('U');
		}
		parent::initFilterByGpVars();
	}



	/**
	 * @return string
	 */
	public function getValue() {
		$result = '';
		if (isset($this->filterValueStart)) {
			$dateTimeZone = new DateTimeZone(date_default_timezone_get());
			$this->getFilterValueStart()->setTimezone($dateTimeZone);
			$result = $this->getFilterValueStart()->format('Y-m-d');
		}
		return $result;
	}



	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter::getOptions()
	 */
	public function getOptions() {
		$dataProvider = Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderFactory::createInstance($this->filterConfig);
		return $dataProvider->getRenderedOptions();
	}



    /**
     * Get DatePicker options
     *
     * Provide DatePicker options from TypoScript in JavaScript template
     *
     * @return mixed
     */
	public function getDatePickerOptions() {
		return $this->buildJson($this->filterConfig->getSettings('options'));
	}



	/**
	 * Build Json
	 *
	 * json_encode() can't be used, since object literal values mustn't be
	 * placed within double quotations.
	 *
	 * This method is highly customized with respect to DatePicker's options
	 * structure. Thus, it is not swapped to a generic helper class.
	 *
	 * @param array $options DatePicker Options as array from TypoScript
	 * @return string
	 */
	protected function buildJson($options) {
		$result = '';
		if (is_array($options)) {
			$elements = array();
			foreach ($options as $key => $value) {
				$elements[] = '"' . $key . '":' . $value;
			}
			$result = implode(',', $elements);
		}
		return "{" . $result . "}";
	}

}
?>