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
	 * TODO insert comment
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration $configuration
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration $configuration) {
		$this->rendererConfiguration = $configuration;
		tx_pttools_assert::isNotEmpty($this->rendererConfiguration->getColumnConfigCollection(), array('message' => 'No column configuration found. 1280320558'));

		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
	}
	
	
	
	/**
	 * Renders the cell content.
	 *
	 * @param string $fieldIdentifier The index of the current row (table data).
	 * @param string $columnId The columnIdentifier.
	 * @param Tx_PtExtlist_Domain_Model_List_Row $currentRow The table data.
	 * @param int $columnIndex Current column index.
	 * @param int $rowIndex Current row index.
	 * 
	 * @return Tx_Pt_extlist_Domain_Model_List_Cell
	 */

	public function renderCell($fieldIdentifier, $columnId, Tx_PtExtlist_Domain_Model_List_Row $currentRow, $columnIndex=0, $rowIndex = 0) {
		
		// Get the column config for columnId
		$columnConfig = $this->rendererConfiguration->getColumnConfigCollection()->getColumnConfigByIdentifier($columnId);
		
		// Get field data and concat if fieldIdentifiers is defined as a list
		$fields = explode(",",$fieldIdentifier);
		$content = '';
		foreach($fields as $i => $fieldId) {
			$field = $currentRow->getItemById(trim($fieldId))->getValue();
			$content .= ($i > 0 ? ', '.$field : $field);
		}

		// Load all available fields
		$fieldSet = $this->createFieldSet($currentRow);
						
		// TS parsing
		// This resets previous content from fieldIdentifier config.
		// TODO: set currentData <= $content
		if($columnConfig->getRenderObj() != null) {
			// Inject current data into the cObject
			if($fieldSet) $this->cObj->start($fieldSet);
			$conf = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray( $columnConfig->getRenderObj());
			$content = $this->cObj->cObjGet($conf);
		}
		
		// Call post render user functions
		$userFunctions = $columnConfig->getRenderUserFunctions();
		if($userFunctions != null) {
			$content = $this->renderWithUserFunc($content, $userFunctions, $fieldSet);
		}
		
		// Create new cell 
		$cell = new Tx_PtExtlist_Domain_Model_List_Cell($content);
			
		// Resolve special cell values
		$this->renderSpecialValues($cell);
		
		
		return $cell;
	}
	
	protected function renderSpecialValues(Tx_PtExtlist_Domain_Model_List_Cell &$cell) {
		// Resolve special cell values
		if(!is_null($this->rendererConfiguration->getSpecialCell())) {
			
			$rendererUserFunc = $this->rendererConfiguration->getSpecialCell();
			
			$params['columnIndex'] = $columnIndex;
			$params['rowIndex'] = $rowIndex;
			$params['content'] = $content;
			$dummRef = '';
			
			$specialValues = t3lib_div::callUserFunction($rendererUserFunc, $params, $dummRef);
			$cell->setSpecialValues($specialValues);
		}
	}
	
	/**
	 * Calls userFunctions with fieldset 
	 * 
	 * @param array $userFunctions
	 * @param array $fieldSet
	 * @return string The rendered content
	 */
	protected function renderWithUserFunc($content, array $userFunctions, $fieldSet) {
		$userFunctions = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray( $userFunctions );
		$sortedKeys = t3lib_TStemplate::sortedKeyList($userFunctions, false);
		$params = array();
		$params['values'] = $fieldSet;

		$dummRef = ''; 
		
		foreach ($sortedKeys as $key) {
			$rendererUserFunc = $userFunctions[$key];
			
			$params['currentContent'] = $renderedContent;
			$params['conf'] = $userFunctions[$key]; 
			$content = t3lib_div::callUserFunction($rendererUserFunc, $params, $dummRef);
		}
		
		return $content;
	}
	
	/**
	 * Creates a set of fields which are available. Defined by the 'fields' TS setup.
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Row $row
	 * @return unknown
	 */
	protected function createFieldSet(Tx_PtExtlist_Domain_Model_List_Row &$row) {
		$fieldSet = array();

		$fieldCollection = $this->rendererConfiguration->getFieldConfigCollection();
		
		foreach($fieldCollection->getIterator() as $field) {
			$fieldIdent = $field->getIdentifier();
			$fieldSet[$fieldIdent] = $row->getItemById($fieldIdent);	
		}
		
		return $fieldSet;
	}
	
}

?>