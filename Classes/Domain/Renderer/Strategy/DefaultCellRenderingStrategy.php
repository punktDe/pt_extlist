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
 * TODO insert comment!
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 */
class Tx_PtExtlist_Domain_Renderer_Strategy_DefaultCellRenderingStrategy implements Tx_PtExtlist_Domain_Renderer_Strategy_CellRenderingStrategyInterface {

	/**
	 * TODO insert comment
	 *
	 * @var unknown_type
	 */
	protected $rendererConfiguration;
	
	
	
	/**
	 * TODO insert comment
	 *
	 * @var unknown_type
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
	 * TODO insert comment
	 *
	 * @param string $fieldIdentifier
	 * @param string $columnIndex
	 * @param Tx_PtExtlist_Domain_Model_List_Row $currentRow
	 * @return mixed
	 */
	public function renderCell($fieldIdentifier, $columnIndex, Tx_PtExtlist_Domain_Model_List_Row $currentRow) {
			
		$columnConfig = $this->rendererConfiguration->getColumnConfigCollection()->getColumnConfigByIdentifier($columnIndex);
		$content = $currentRow->getItemById($fieldIdentifier);
		
		$fieldSet = $this->createFieldSet($currentRow);
		
		// Inject current data into the cObject
		if($fieldSet) $this->cObj->start($fieldSet);

					
		// TS parsing
		if($columnConfig->getRenderObj() != null) {
			$conf = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray( $columnConfig->getRenderObj());
			$content = $this->cObj->cObjGet($conf);
		}
		
		return $content;
	}
	
	
	
	/**
	 * TODO insert comment
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