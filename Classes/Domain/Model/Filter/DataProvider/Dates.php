<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt, Joachim Mathes
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
 * Implements data provider for grouped list data
 *
 * @author Joachim Mathes
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class Tx_PtExtlist_Domain_Model_Filter_DataProvider_Dates extends Tx_PtExtlist_Domain_Model_Filter_DataProvider_AbstractDataProvider {

	/**
	 * Array of filters to be excluded if options for this filter are determined
	 *
	 * @var array
	 */
	protected $excludeFilters = NULL;



	/**
	 * A timeSpan can have a startTime and an endTime
	 * Therefore the dateFieldIdentifiers are 2-dimensional arrays of start/end field tuples
	 *
	 * @var array
	 */
	protected $dateFieldConfigs;



	/**
	 * Init the dataProvider by TS-conifg
	 *
	 * @param array $filterSettings
	 */
	protected function initDataProviderByTsConfig($filterSettings) {
		if (array_key_exists('excludeFilters', $filterSettings) && trim($filterSettings['excludeFilters'])) {
			$this->excludeFilters = t3lib_div::trimExplode(',', $filterSettings['excludeFilters']);
		}
		$this->buildDateFieldConfigArray();
	}


	/**
	 * Build the dateFieldConfigArray
	 *
	 * @return void
	 */
	protected function buildDateFieldConfigArray() {
		$fieldIdentifier = $this->filterConfig->getSettings('fieldIdentifier');

		$this->dateFieldConfigs = array();

		if (is_array($fieldIdentifier)) {
			foreach($fieldIdentifier as $tupleId => $dateFieldTuple) {

				if (!array_key_exists('start', $dateFieldTuple) || !array_key_exists('start', $dateFieldTuple)) throw new Exception('Found a fieldIdentifier array, but the array was not suitable for a timeSpanFilter. 1314627131');

				$this->dateFieldConfigs[$tupleId] = array(
					'start' => $this->filterConfig->getConfigurationBuilder()->buildFieldsConfiguration()->getFieldConfigByIdentifier($dateFieldTuple['start']),
					'end' => $this->filterConfig->getConfigurationBuilder()->buildFieldsConfiguration()->getFieldConfigByIdentifier($dateFieldTuple['end'])
				);
			}
		} else {
			foreach($this->filterConfig->getFieldIdentifier() as $fieldIdentifierConfig) {
				$this->dateFieldConfigs[] = array(
					'start' => $fieldIdentifierConfig,
					'end' => $fieldIdentifierConfig
				);
			}
		}
	}



	/**
	 * Build the group data query to retrieve the group data
	 *
	 * @return Tx_PtExtlist_Domain_QueryObject_Query
	 */
	protected function buildQuery() {
		$query = new Tx_PtExtlist_Domain_QueryObject_Query();

		foreach($this->dateFieldConfigs as $key => $selectField) {
			if ($selectField['start'] == $selectField['end']) {
				$aliasedSelectPart = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($selectField['start']) . ' AS ' . $selectField['start']->getIdentifier();
				$query->addField($aliasedSelectPart);
			} else {
				$aliasedSelectPartStart = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($selectField['start']) . ' AS ' . $this->buildFieldAlias($key, 'start');
				$aliasedSelectPartEnd = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($selectField['end']) . ' AS ' . $this->buildFieldAlias($key, 'end');
				$query->addField($aliasedSelectPartStart);
				$query->addField($aliasedSelectPartEnd);
			}
		}
		return $query;
	}



	/**
	 * Returns associative array of exclude filters for given TS configuration
	 *
	 * @return array Array with exclude filters. Encoded as (array('filterboxIdentifier' => array('excludeFilter1','excludeFilter2',...)))
	 */
	protected function buildExcludeFiltersArray() {

		$excludeFiltersAssocArray = array($this->filterConfig->getFilterboxIdentifier() => array($this->filterConfig->getFilterIdentifier()));

		if ($this->excludeFilters) {
			foreach ($this->excludeFilters as $excludeFilter) {

				list($filterboxIdentifier, $filterIdentifier) = explode('.', $excludeFilter);

				if ($filterIdentifier != '' && $filterboxIdentifier != '') {
					$excludeFiltersAssocArray[$filterboxIdentifier][] = $filterIdentifier;
				} else {
					throw new Exception('Wrong configuration of exclude filters for filter '. $this->filterConfig->getFilterboxIdentifier() . '.' . $this->filterConfig->getFilterIdentifier() . '. Should be comma seperated list of "filterboxIdentifier.filterIdentifier" but was ' . $excludeFilter . ' 1281102702');
				}

			}
		}

		return $excludeFiltersAssocArray;

	}



	/****************************************************************************************************************
	 * Methods implementing "Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface"
	 *****************************************************************************************************************/

	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::init()
	 */
	public function init() {
		$this->initDataProviderByTsConfig($this->filterConfig->getSettings());
	}



	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::getRenderedOptions()
	 */
	public function getRenderedOptions() {
		$query = $this->buildQuery();
		$excludeFiltersArray = $this->buildExcludeFiltersArray();
		$queryResult = $this->dataBackend->getGroupData($query, $excludeFiltersArray, $this->filterConfig);
		return $this->buildCondensedTimeSpans($queryResult)->getJsonValue();
	}



	/**
	 * @param array $queryResult
	 * @return Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpanCollection Condensed time spans
	 */
	protected function buildCondensedTimeSpans($queryResult) {
		$timeSpans = new Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpanCollection();

		foreach ($queryResult as $dateRanges) {
			foreach ($this->dateFieldConfigs as $key => $config) {

				if ($config['start'] == $config['end']) {
					$startField = $config['start']->getIdentifier();
					$endField = $config['end']->getIdentifier();
				} else {
					$startField = $this->buildFieldAlias($key, 'start');
					$endField = $this->buildFieldAlias($key, 'end');
				}

				$startDate = $dateRanges[$startField];
				$endDate = $dateRanges[$endField];

				if (!empty($startDate) && !empty($endDate)) {
					$timeSpan = new Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan();
					$timeSpan->setStartDate(new DateTime("@" . $startDate));
					$timeSpan->getStartDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
					$timeSpan->setEndDate(new DateTime("@" . $endDate));
					$timeSpan->getEndDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
					$timeSpans->push($timeSpan);
				}
			}
		}

		$condensedTimeSpansAlgorithm = new Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_CondensedTimeSpansAlgorithm();
		$condensedTimeSpansAlgorithm->setTimeSpans($timeSpans);
		return $condensedTimeSpansAlgorithm->process();
	}



	/**
	 * @param string $key
	 * @param string $part
	 * @return string
	 */
	protected function buildFieldAlias($key, $part) {
		return $this->dateFieldConfigs[$key][$part]->getIdentifier() . '_' . $key . '_' . $part;
	}

}
?>