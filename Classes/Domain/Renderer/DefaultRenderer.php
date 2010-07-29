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

class Tx_PtExtlist_Domain_Renderer_DefaultRenderer extends Tx_PtExtlist_Domain_Renderer_AbstractRenderer {
	
	protected $cObj;
	
	protected $cellRenderer;
	protected $captionRenderer;
	
	public function __construct(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration $config) {
		parent::__construct($config);
		
		$this->cellRenderer = new Tx_PtExtlist_Domain_Renderer_Strategy_DefaultCellRenderingStrategy($config);
		$this->captionRenderer = new Tx_PtExtlist_Domain_Renderer_Strategy_DefaultCaptionRendererStrategy();
		
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
	}
	
	/**
	 * 
	 * @see Classes/Domain/Renderer/Tx_PtExtlist_Domain_Renderer_RendererInterface::render()
	 */
	public function render(Tx_PtExtlist_Domain_Model_List_ListData $list) {
		if(!$this->rendererConfiguration->isEnabled()) return $list;
		
		tx_pttools_assert::isNotEmpty($this->rendererConfiguration->getColumnConfigCollection(), array('message' => 'No column configuration found. 1280315003'));
		
		$renderedList = $this->renderList($list);
	
		return $renderedList;
	}
	
	/**
	 * 
	 * @see Classes/Domain/Renderer/Tx_PtExtlist_Domain_Renderer_RendererInterface::renderCaptions()
	 */
	public function renderCaptions() {
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		
		tx_pttools_assert::isNotEmpty($this->rendererConfiguration->getColumnConfigCollection(), array('message' => 'No column configuration found. 1280315045'));
		
		$columnCollection = $this->rendererConfiguration->getColumnConfigCollection();
		
		
		foreach($columnCollection->getIterator() as $column) {
			$label = $column->getLabel();
			
			// Use TS for rendering
			if(is_array($label)) {
				$conf = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray( $label );
				$label = $this->cObj->cObjGet($conf);
			}
			
			$row->addCell($column->getColumnIdentifier(), $label);
		}
		
		return $row;
	}
	
	
	protected function renderList(Tx_PtExtlist_Domain_Model_List_ListData $list) {
		$renderedList = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		// if defined in TS, use captions as the first row.
		if($this->rendererConfiguration->showCaptionsInBody()) {
			$renderedList->addRow($this->renderCaptions());
		}
		
		foreach($list->getIterator() as $row) {
			$renderedList->addRow($this->renderRow($row));
		}
		
		return $renderedList;
	}
	
	
	protected function renderRow(Tx_PtExtlist_Domain_Model_List_Row $row) {
		$renderedRow = new Tx_PtExtlist_Domain_Model_List_Row();
		$columnCollection = $this->rendererConfiguration->getColumnConfigCollection();
	
		foreach($columnCollection->getIterator() as $id => $column) {
			$fieldIdent = $column->getFieldIdentifier();
			$columnIdent = $column->getColumnIdentifier();
			
			// Use strategy to render cells
			$cell = $this->cellRenderer->renderCell($fieldIdent, $id, $row);
			
			$renderedRow->addCell($columnIdent, $cell);
		}
		
		return $renderedRow;
	}
		
	
	
	
	
}

?>