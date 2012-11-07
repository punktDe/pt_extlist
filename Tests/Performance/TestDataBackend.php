<?php

require_once(t3lib_extMgm::extPath('pt_extlist') . 'Tests/Performance/TestDataSource.php');

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
 * Test DataBackend for generating test data
 * 
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Tests\Performance\TestDataBackend
 *
 */
class Tx_PtExtlist_Tests_Performance_TestDataBackend extends Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend {

	/**
	 * @var int
	 */
	protected $rowCount = 20;

	/**
	 * @var int
	 */
	protected $colCount = 5;


	/**
	 *
	 * Build the listData and cache it in $this->listData
	 */
	protected function buildListData() {

		$dataSource = new Tx_PtExtlist_Tests_Performance_TestDataSource($this->rowCount, $this->colCount);

		$rawData = $dataSource->fetchAll();

		for($i = 0; $i < $this->rowCount; $i++) {
			for($j = 1; $j <= $this->colCount; $j++) {
				$rawData[$i]['col_' . $j] = "Testdaten aus der Koordinate $i:$j" ;
			}
		}

		$mappedData =  $this->dataMapper->getMappedListData($rawData);

		unset($rawData);

		return $mappedData;
	}


	/**
	 * @return Tx_PtExtlist_Domain_Model_List_IterationListDataInterface|void
	 */
	public function getIterationListData() {

		$rendererChainConfiguration = $this->configurationBuilder->buildRendererChainConfiguration();
		$rendererChain = Tx_PtExtlist_Domain_Renderer_RendererChainFactory::getRendererChain($rendererChainConfiguration);

		$dataSource = new Tx_PtExtlist_Tests_Performance_TestDataSource($this->rowCount, $this->colCount);

		$iterationListData = new Tx_PtExtlist_Domain_Model_List_IterationListData();
		$iterationListData->_injectDataSource($dataSource);
		$iterationListData->_injectDataMapper($this->dataMapper);
		$iterationListData->_injectRenderChain($rendererChain);

		return $iterationListData;
	}



	/**
	 * Returns raw data for all filters excluding given filters.
	 *
	 * Result is given as associative array with fields given in query object.
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery Query that defines which group data to get
	 * @param array $excludeFilters List of filters to be excluded from query (<filterboxIdentifier>.<filterIdentifier>)
	 * @return array Array of group data with given fields as array keys
	 */
	public function getGroupData(Tx_PtExtlist_Domain_QueryObject_Query $groupDataQuery, $excludeFilters = array(),
								 Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig = NULL) {
		// TODO: Implement getGroupData() method.
	}


	/**
	 * Returns the number of items for current settings without pager settings
	 *
	 * @return int Total number of items for current data set
	 */
	public function getTotalItemsCount() {
		// TODO: Implement getTotalItemsCount() method.
	}


	/**
	 * Return an aggregate for a field and with a method defined in the given config
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig $aggregateDataConfig
	 */
	public function getAggregatesByConfigCollection(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateDataConfigCollection) {
		// TODO: Implement getAggregatesByConfigCollection() method.
	}


	/**
	 * @param $rowCount
	 * @return Tx_PtExtlist_Tests_Performance_TestDataBackend
	 */
	public function setRowCount($rowCount) {
		$this->rowCount = $rowCount;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRowCount() {
		return $this->rowCount;
	}

	/**
	 * @param int $colCount
	 */
	public function setColCount($colCount) {
		$this->colCount = $colCount;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getColCount() {
		return $this->colCount;
	}
}

?>