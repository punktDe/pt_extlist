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
 * TODO insert comment
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 */
class Tx_PtExtlist_Domain_Renderer_DefaultRenderer extends Tx_PtExtlist_Domain_Renderer_AbstractRenderer {
	
	/**
	 * The Strategy for rendering cells.
	 *
	 * @var Tx_PtExtlist_Domain_Renderer_Strategy_CellRenderingStrategyInterface
	 */
	protected $cellRenderer;
	
	
	
	/**
	 * The strategy for rendering captions.
	 *
	 * @var Tx_PtExtlist_Domain_Renderer_Strategy_CaptionRenderingStrategyInterface
	 */
	protected $captionRenderer;
	
	
	
	/**
	 * Constructor loads strategies.
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration $config
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration $config) {
		parent::__construct($config);
		
		$this->cellRenderer = new Tx_PtExtlist_Domain_Renderer_Strategy_DefaultCellRenderingStrategy($config);
		$this->captionRenderer = new Tx_PtExtlist_Domain_Renderer_Strategy_DefaultCaptionRenderingStrategy();
		
	
	}
	
	
	
	/**
	 * @see Classes/Domain/Renderer/Tx_PtExtlist_Domain_Renderer_RendererInterface::render()
	 */
	public function render(Tx_PtExtlist_Domain_Model_List_List &$list) {
		if(!$this->rendererConfiguration->isEnabled()) return $list->getListData();
		
		tx_pttools_assert::isNotEmpty($this->rendererConfiguration->getColumnConfigCollection(), array('message' => 'No column configuration found. 1280315003'));
		
		$listData = $list->getListData();
		tx_pttools_assert::isNotNull($listData, array(message => 'No list data found in list. 1280405145'));
		
		$renderedList = $this->renderList($listData);
	
		return $renderedList;
	}
	
	
	
	/**
	 * @see Classes/Domain/Renderer/Tx_PtExtlist_Domain_Renderer_RendererInterface::renderCaptions()
	 */
	public function renderCaptions(Tx_PtExtlist_Domain_Model_List_List &$list) {
		return $this->captionRenderer->renderCaptions($list);
	}
	
	
	
	/**
	 * Renders  list data.
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_ListData &$list
	 * 
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	protected function renderList(Tx_PtExtlist_Domain_Model_List_ListData &$list) {
		$renderedList = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		// if defined in TS, use captions as the first row.
		if($this->rendererConfiguration->showCaptionsInBody()) {
			$renderedList->addRow($this->renderCaptions());
		}
		
		foreach($list->getIterator() as $rowIndex => $row) {
			$renderedList->addRow($this->renderRow($row, $rowIndex));
		}
		
		return $renderedList;
	}
	
	
	
	/**
	 * Renders a row
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Row $row
	 * @return Tx_PtExtlist_Domain_Model_List_Row
	 */
	protected function renderRow(Tx_PtExtlist_Domain_Model_List_Row &$row, $rowIndex) {
		$renderedRow = new Tx_PtExtlist_Domain_Model_List_Row();
		$columnCollection = $this->rendererConfiguration->getColumnConfigCollection();
	
		$columnIndex=0;
		foreach($columnCollection->getIterator() as $id => $column) {
			$fieldIdent = $column->getFieldIdentifier();
			$columnIdent = $column->getColumnIdentifier();
			
			// Use strategy to render cells
			$cell = $this->cellRenderer->renderCell($fieldIdent, $id, $row, $columnIndex, $rowIndex);
			
			$renderedRow->addItem($cell,$columnIdent);
			$columnIndex++;
		}
		
		return $renderedRow;
	}
	
}

?>