<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Implements data provider for explicit defined SQL query
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class Tx_PtExtlist_Domain_Model_Filter_DataProvider_ExplicitSQLQuery extends Tx_PtExtlist_Domain_Model_Filter_DataProvider_AbstractDataProvider {

	/**
	 * @var string
	 */
	protected $selectPart;

	/**
	 * @var string
	 */
	protected $fromPart;

	/**
	 * @var string
	 */
	protected $wherePart;

	/**
	 * @var string
	 */
	protected $groupByPart;

	/**
	 * @var string
	 */
	protected $orderByPart;

	/**
	 * @var string
	 */
	protected $limitPart;


	/**
	 * @var string
	 */
	protected $displayFields;



	/**
	 * @var string
	 */
	protected $filterField;



	/**
	 * Init the data provider
	 */
	public function init() {
		$sqlQuerySettings = $this->filterConfig->getSettings('optionsSqlQuery');

		foreach($sqlQuerySettings as $type => $part) {
			$sqlQuerySettings[$type] = trim($part);
		}

		if($sqlQuerySettings['select']) $this->selectPart = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($sqlQuerySettings['select']);
		if($sqlQuerySettings['from']) $this->fromPart = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($sqlQuerySettings['from']);
		if($sqlQuerySettings['where']) $this->wherePart = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($sqlQuerySettings['where']);
		if($sqlQuerySettings['orderBy']) $this->orderByPart = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($sqlQuerySettings['orderBy']);
		if($sqlQuerySettings['groupBy']) $this->groupByPart = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($sqlQuerySettings['groupBy']);
		if($sqlQuerySettings['limit']) $this->limitPart = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($sqlQuerySettings['limit']);


		$this->filterField = trim($this->filterConfig->getSettings('filterField'));
		$this->displayFields = t3lib_div::trimExplode(',', $this->filterConfig->getSettings('displayFields'));

		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->filterField, array('info' => 'No filter field is given for filter ' . $this->filterConfig->getFilterIdentifier() . ' 1315221957'));
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->selectPart, array('info' => 'No Select part is given for filter ' . $this->filterConfig->getFilterIdentifier() . ' 1315221958'));
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->fromPart, array('info' => 'No from part is given for filter ' . $this->filterConfig->getFilterIdentifier() . ' 1315221959'));
	}



	/**
	 * Return the rendered filterOptions
	 *
	 * @return array filter options
	 */
	public function getRenderedOptions() {
		$options = $this->getDataFromSqlServer();
		foreach ($options as $optionData) {
			$optionKey = $optionData[$this->filterField];

			$renderedOptions[$optionKey] = $optionData;
			$renderedOptions[$optionKey]['value'] = $this->renderOptionData($optionData);
			$renderedOptions[$optionKey]['selected'] = false;
		}
		return $renderedOptions;
	}


	/**
	 * Render a single option line by cObject or default
	 *
	 * @param array $optionData
	 */
	protected function renderOptionData($optionData) {

		$option = '';

		foreach ($this->displayFields as $displayField) {
			$values[] = $optionData[$displayField];
		}

		$optionData['allDisplayFields'] = implode(' ', $values);

		$option = Tx_PtExtlist_Utility_RenderValue::renderByConfigObjectUncached($optionData, $this->filterConfig);

		return $option;
	}



	/**
	 * @throws Exception
	 * @return array of options
	 */
	protected function getDataFromSqlServer() {
		$query = $GLOBALS['TYPO3_DB']->SELECTquery($this->selectPart, $this->fromPart, $this->wherePart, $this->groupByPart, $this->orderByPart, $this->limitPart); // this method only combines the parts

		if (TYPO3_DLOG) t3lib_div::devLog('MYSQL QUERY : '.$this->filterConfig->getListIdentifier() . ' -> Filter::ExplicitSQLQuery', 'pt_extlist', 1, array('query' => $query));

		$dataSource = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::getInstanceByListIdentifier($this->filterConfig->getListIdentifier())->getDataSource();

		if(!method_exists($dataSource, 'executeQuery')) {
			throw new Exception('The defined dataSource has no method executeQuery and is therefore not usable with this dataProvider! 1315216209');
		}

		return $dataSource->executeQuery($query)->fetchAll();
	}
}
?>