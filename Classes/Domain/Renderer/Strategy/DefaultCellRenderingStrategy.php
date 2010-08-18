<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de>
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
 * The default strategy to render a cell.
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 */
class Tx_PtExtlist_Domain_Renderer_Strategy_DefaultCellRenderingStrategy implements Tx_PtExtlist_Domain_Renderer_Strategy_CellRenderingStrategyInterface {

	/**
	 * The configuration.
	 * @var Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration
	 */
	protected $rendererConfiguration;
	
	
	
	/**
	 * A cObject for TS parsing
	 * @var tslib_cObj
	 */
	protected $cObj;
	
	
	
	/**
	 * Construct the strategy.
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration $configuration
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration $configuration) {
		$this->rendererConfiguration = $configuration;
		tx_pttools_assert::isNotEmpty($this->rendererConfiguration->getColumnConfigCollection(), array('message' => 'No column configuration found. 1280320558'));

		// $this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$this->cObj = $GLOBALS['TSFE']->cObj;
	}
	
	
	
	/**
	 * Renders the cell content.
	 *
	 * @param string $fieldIdentifier The index of the current row (table data).
	 * @param string $columnIdentifier The columnIdentifier.
	 * @param Tx_PtExtlist_Domain_Model_List_Row &$data The table data.
	 * @param int $columnIndex Current column index.
	 * @param int $rowIndex Current row index.
	 * 
	 * @return Tx_Pt_extlist_Domain_Model_List_Cell
	 */

	public function renderCell($fieldIdentifier, $columnIdentifier, Tx_PtExtlist_Domain_Model_List_Row &$data, $columnIndex, $rowIndex) {
				
		// Get the column config for columnId
		$columnConfig = $this->rendererConfiguration->getColumnConfigCollection()->getColumnConfigByIdentifier($columnIdentifier);
		
		// Load all available fields
		$fieldSet = $this->createFieldSet($data, $columnConfig);
		
		$content = Tx_PtExtlist_Utility_RenderValue::renderByConfigObject($fieldSet, $columnConfig);
		
		// Create new cell 
		$cell = new Tx_PtExtlist_Domain_Model_List_Cell($content);
		$cell->setRowIndex($rowIndex);
		$cell->setColumnIndex($columnIndex);
		
		// Resolve special cell values
		$this->renderSpecialValues($cell, $columnConfig);
		
		
		return $cell;
	}
	
	
	
	/**
	 * Call user functions for building special values.
	 * renderer.specialCell gets overridden by column.specialCell
	 * 
	 * @param Tx_PtExtlist_Domain_Model_List_Cell &$cell
	 * @param Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig &$columnConfig
	 */
	protected function renderSpecialValues(Tx_PtExtlist_Domain_Model_List_Cell $cell, Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfig) {
		
		// Resolve special cell values
		if(!is_null($this->rendererConfiguration->getSpecialCell())) {
			$rendererUserFunc = $this->rendererConfiguration->getSpecialCell();
		}
		
	
		if(!is_null($columnConfig->getSpecialCell())) {
			$rendererUserFunc = $columnConfig->getSpecialCell();
		}
		
		if(!empty($rendererUserFunc)) {

			$dummRef = '';			
			$specialValues = t3lib_div::callUserFunction($rendererUserFunc, $cell, $dummRef);
			
			$cell->setSpecialValues($specialValues);
		}
	}
		
	
	/**
	 * Creates a set of fields which are available. Defined by the 'fields' TS setup.
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Row $row
	 * @return unknown
	 */
	protected function createFieldSet(Tx_PtExtlist_Domain_Model_List_Row $row, Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfig) {
		$fieldSet = array();

		foreach($columnConfig->getFieldIdentifier() as $fieldIdentifier) {
			$fieldSet[$fieldIdentifier] = $row->getItemById($fieldIdentifier);	
		}
		
		return $fieldSet;
	}
	
}

?>