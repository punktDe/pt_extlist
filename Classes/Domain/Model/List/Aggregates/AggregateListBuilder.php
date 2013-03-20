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
 * Class implements factory the list of aggregate rows
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\List\Aggregates
 */
class Tx_PtExtlist_Domain_Model_List_Aggregates_AggregateListBuilder {
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;
	
	
	
	/**
	 * @var Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 */
	protected $dataBackend;
	
	
	
	/**
	 * Aggregator for aggregating listData
	 * 
	 * @var Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator
	 */
	protected $arrayAggregator;
	
	
	
	/**
	 * Holds the aggregated data
	 * @var Tx_PtExtlist_Domain_Model_List_Row
	 */
	protected $aggregatedDataRow;
	
	
	
	/**
	 * Reference to the configured renderer
	 * 
	 * @var Tx_PtExtlist_Domain_Renderer_RendererInterface
	 */
	protected $renderer;
	
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection
	 */
	protected $aggregateDataConfiguration;
	
	
	
	/**
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->configurationBuilder = $configurationBuilder;
		$this->aggregateDataConfiguration = $configurationBuilder->buildAggregateDataConfiguration();
	}
	
	
	
	/**
	 * @param Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator $arrayAggregator
	 */
	public function injectArrayAggregator(Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator $arrayAggregator) {
		$this->arrayAggregator = $arrayAggregator;
	}

	
	
	/**
	 * @param Tx_PtExtlist_Domain_Renderer_RendererInterface $renderer
	 */
	public function injectRenderer(Tx_PtExtlist_Domain_Renderer_RendererInterface $renderer) {
		$this->renderer = $renderer;
	}
	
	
	
	/**
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 */
	public function injectDataBackend(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend) {
		$this->dataBackend = $dataBackend;	
	}
	
	
	
	/**
	 * Init the aggregates to fill in the columns
	 */
	public function init() { 
	}

	
	
	/**
	 * Build the aggregate list
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function buildAggregateListData() {
		$aggreagteListData = new Tx_PtExtlist_Domain_Model_List_ListData();
		$aggreagteListData->addRow($this->buildAggregateDataRow());
		return $aggreagteListData;
	}
	
	
	
	/**
	 * Build the aggregate data by configuration
	 * 
	 * @return Tx_PtExtlist_Domain_Model_List_Row
	 */
	protected function buildAggregateDataRow() {
		$dataRow = new Tx_PtExtlist_Domain_Model_List_Row();
		$aggregateDataConfigCollection = $this->configurationBuilder->buildAggregateDataConfig();
		
		$aggregatesForPage = $this->getAggregatesForPage($aggregateDataConfigCollection->extractCollectionByScope('page'));
		$aggregatesForQuery = $this->getAggregatesForQuery($aggregateDataConfigCollection->extractCollectionByScope('query'));
		$aggregates = t3lib_div::array_merge_recursive_overrule($aggregatesForQuery, $aggregatesForPage);	

		foreach($aggregates as $key => $value) {
			$dataRow->createAndAddCell($value, $key);
		}
		
		return $dataRow;
	}


	/**
	 * Get Aggregate data for Page
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateDataConfigCollection
	 * @return array
	 */
	protected function getAggregatesForPage(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateDataConfigCollection) {
		
		$aggregates = array();
	
		foreach($aggregateDataConfigCollection as $aggregateDataConfig) {
			$aggregates[$aggregateDataConfig->getIdentifier()] = $this->arrayAggregator->getAggregateByConfig($aggregateDataConfig);
		}

		return $aggregates;
	}


	/**
	 * Get aggregate data for the whole query
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateDataConfigCollection
	 * @return array
	 */
	protected function getAggregatesForQuery(Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection $aggregateDataConfigCollection) {
		if($aggregateDataConfigCollection->count() > 0) {
			$aggregates = $this->dataBackend->getAggregatesByConfigCollection($aggregateDataConfigCollection);
		}

		if(!is_array($aggregates)) $aggregates = array();
		return $aggregates;
	}
	
}

?>