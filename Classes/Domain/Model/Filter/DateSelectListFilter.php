<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Simon Schaufelberger
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
 * Filter for time range
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_DateSelectListFilter extends Tx_PtExtlist_Domain_Model_Filter_TimeSpanFilter {


	/**
	 * @return void
	 */
	public function initFilterByGpVars() {
		if(is_array($this->gpVarFilterData) && array_key_exists('filterValues',$this->gpVarFilterData)) {
			list($this->gpVarFilterData['filterValueStart'], $this->gpVarFilterData['filterValueEnd']) = explode(',', $this->gpVarFilterData['filterValues']);
		}

		parent::initFilterByGpVars();
	}


	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter::getOptions()
	 */
	public function getOptions() {

		$dataProvider = Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderFactory::createInstance($this->filterConfig);

		$renderedOptions = $dataProvider->getRenderedOptions();
		$this->addInactiveOption($renderedOptions);

		return $this->convertOptionsToSelectOptions($renderedOptions);
	}



	/**
	 * @param $renderedOptions array
	 * @return array
	 */
	protected function convertOptionsToSelectOptions(&$renderedOptions) {
		$selectOptions = array();
		foreach($renderedOptions as $optionKey => $optionValue) {
			$selectOptions[$optionKey] = $optionValue['value'];
		}

		return $selectOptions;
	}


	/**
	 * Add inactiveFilterOpotion to rendered options
	 *
	 * @param array $renderedOptions
	 */
	protected function addInactiveOption(&$renderedOptions) {

		if($renderedOptions == NULL) $renderedOptions = array();

		if($this->filterConfig->getInactiveOption()) {

			unset($renderedOptions[$this->filterConfig->getInactiveValue()]);

			if(count($this->filterValues) == 0) {
				$selected = true;
			} else {
				$selected = in_array($this->filterConfig->getInactiveValue(), $this->filterValues) ? true : false;
			}

			$inactiveValue = $this->filterConfig->getInactiveValue();

			$renderedInactiveOption[$inactiveValue] = array('value' => $this->filterConfig->getInactiveOption(),
        													     'selected' => $selected);

			$renderedOptions= $renderedInactiveOption + $renderedOptions;
		}

		return $renderedOptions;
	}



	/**
	 * @return string
	 */
	public function getValue() {
		return implode(',',parent::getValue());
	}


}
?>