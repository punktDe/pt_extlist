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
 * Class implements factory the list of aggregate rows
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package pt_extlist
 * @subpackage \Domain\Model\List\Aggregates
 */
class Tx_PtExtlist_Domain_Model_List_Aggregates_AggregateListBuilder {
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;
	
	
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
	
	
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->configurationBuilder = $configurationBuilder;
		$this->aggregateDataConfiguration = $configurationBuilder->buildAggregateDataConfig();
	}
	
	
	
	public function injectArrayAggregator(Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator $arrayAggregator) {
		$this->arrayAggregator = $arrayAggregator;
	}

	
	public function injectRenderer(Tx_PtExtlist_Domain_Renderer_RendererInterface $renderer) {
		$this->renderer = $renderer;
	}
	
	
	public function init() {
		$this->aggregatedDataRow = $this->buildAggregateDataRow();
	}

	
	/**
	 * Build the aggregate list
	 * 
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function buildAggregateList() {
		$aggregateRowConfigurations = $this->configurationBuilder->buildAggregateRowConfig();
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		foreach($aggregateRowConfigurations as $aggregateRowIndex => $aggregateRowConfiguration) {
			$listData->addRow($this->buildAggregateRow($aggregateRowIndex, $aggregateRowConfiguration));
		}
		
		return $listData;
	}
	
	
	
	/**
	 * Build the aggregate row
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfig $aggregateRowConfiguration
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	protected function buildAggregateRow($aggregateRowIndex, Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfig $aggregateRowConfiguration) {
													
		$columnConfigCollection = $this->configurationBuilder->buildColumnsConfiguration();
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		
		$columnIndex = 0;
		foreach($columnConfigCollection as $columnConfiguration) {
			$columnIdentifier = $columnConfiguration->getColumnIdentifier();
			
			if($aggregateRowConfiguration->hasItem($columnIdentifier)) {

				$aggregateColumnConfig = $aggregateRowConfiguration->getAggregateColumnConfigByIdentifier($columnIdentifier);
				$cellContent = $this->renderer->renderCell($aggregateColumnConfig, $this->aggregatedDataRow, $columnIndex, $aggregateRowIndex);
				$row->createAndAddCell($cellContent, $columnIdentifier);		
			
			} else {
				$row->createAndAddCell('', $columnIdentifier);
			}
			
			$columnIndex++;
		}
		
		return $row;
	}
	
	
	/**
	 * Build the aggregate data by configuration
	 * @return Tx_PtExtlist_Domain_Model_List_Row
	 */
	protected function buildAggregateDataRow() {
		$dataRow = new Tx_PtExtlist_Domain_Model_List_Row();
		$aggregateDataConfigCollection = $this->configurationBuilder->buildAggregateDataConfig();
		
		foreach($aggregateDataConfigCollection as $aggregateDataConfig) {
			$dataRow->createAndAddCell($this->arrayAggregator->getAggregateByConfig($aggregateDataConfig), $aggregateDataConfig->getIdentifier());
		}
		
		return $dataRow;
	}
	
}
?>