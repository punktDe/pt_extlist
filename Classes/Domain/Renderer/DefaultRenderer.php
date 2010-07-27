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
	
	public function __construct(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration $config) {
		parent::__construct($config);
	}
	
	public function render(Tx_PtExtlist_Domain_Model_List_ListData $list) {
		if(!$this->rendererConfiguration->isEnabled()) return $list;
		
		$renderedList = $this->renderList($list);
	
		return $renderedList;
	}
	
	protected function renderList(Tx_PtExtlist_Domain_Model_List_ListData $list) {
		$renderedList = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		if($this->rendererConfiguration->showCaptions()) {
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
		
		foreach($columnCollection->getIterator() as $column) {
			$fieldIdent = $column->getFieldIdentifier();
			$columnIdent = $column->getColumnIdentifier();
			
			$renderedRow->addCell($columnIdent, $row->getItemById($fieldIdent));
		}
		
		return $renderedRow;
	}
	
	/**
	 * 
	 * Creates a row with captions.
	 * 
	 * @return Tx_PtExtlist_Domain_Model_List_Row The caption row.
	 */
	protected function renderCaptions() {
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		$columnCollection = $this->rendererConfiguration->getColumnConfigCollection();
		
		
		foreach($columnCollection->getIterator() as $column) {
			$row->addCell($column->getColumnIdentifier(), $column->getLabel());
		}
		
		return $row;
	}
	
}

?>