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
 * Default renderer for list data
 * 
 * @package Domain
 * @subpackage Renderer\Default
 * @author Daniel Lienert
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_Renderer_Default_Renderer extends Tx_PtExtlist_Domain_Renderer_AbstractRenderer {
    
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
     * Holds an instance of a row renderer
     *
     * @var Tx_PtExtlist_Domain_Renderer_Default_RowRenderer
     */
    protected $rowRenderer;
    
    

	/**
	 * Injector for configuration
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration
	 */
	public function _injectConfiguration(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration) {
		// TODO remove this after refactoring!
        parent::_injectConfiguration($rendererConfiguration);
        $this->initRowRenderer();
        $this->initCaptionRenderer();
    }
    
    
    
	/**
	 * Initializes the row renderer
	 */
	protected function initRowRenderer() {
		$this->rowRenderer = new Tx_PtExtlist_Domain_Renderer_Default_RowRenderer();
		$this->rowRenderer->injectRendererConfiguration($this->rendererConfiguration);
		$this->rowRenderer->injectCellRenderer(new Tx_PtExtlist_Domain_Renderer_Default_CellRenderer($this->rendererConfiguration));
	}
	
	

	/**
	 * Initializes the caption renderer
	 */
	protected function initCaptionRenderer() {
		$this->captionRenderer = new Tx_PtExtlist_Domain_Renderer_Default_CaptionRenderer();
	}
	

	
	/**
	 * @see Classes/Domain/Renderer/Tx_PtExtlist_Domain_Renderer_RendererInterface::renderCaptions()
	 */
	public function renderCaptions(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader) {
		return $this->captionRenderer->renderCaptions($listHeader);
	}
	
	
	
	/**
	 * Renders list data
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $listData
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function renderList(Tx_PtExtlist_Domain_Model_List_ListData $listData) {
		Tx_PtExtbase_Assertions_Assert::isNotNull($listData, array(message => 'No list data found in list. 1280405145'));

		// We could get another type of list data here, so we have to instantiate this class
		$listDataClassName = get_class($listData);
		$renderedList = new $listDataClassName();

		// We need some generic way to copy non-standard data from "old" list data to "new" list data
		if (method_exists($renderedList, 'copyListData')) {
			// TODO refactor this by using a getEmptyInstance method on the list data we want to copy.
			$renderedList->copyListData($listData);
		}

		foreach($listData as $rowIndex => $row) {
			$renderedList->addRow($this->rowRenderer->renderRow($row, $rowIndex));
		}

		unset($listData);
		
		return $renderedList;
	}



	/**
	 * @param Tx_PtExtlist_Domain_Model_List_Row $row
	 * @param $rowIndex
	 * @return Tx_PtExtlist_Domain_Model_List_Row
	 */
	public function renderSingleRow(Tx_PtExtlist_Domain_Model_List_Row $row, $rowIndex) {
		return $this->rowRenderer->renderRow($row, $rowIndex);
	}


	
	/**
	 * Returns a rendered aggregate list for a given row of aggregates
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $aggregateListData
	 * @return Tx_PtExtlist_Domain_Model_List_ListData Rendererd List of aggregate rows
	 */
	public function renderAggregateList(Tx_PtExtlist_Domain_Model_List_ListData $aggregateListData) {
		
		if($aggregateListData->count() == 0) return $aggregateListData;
		
		$renderedAggregateList = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		$aggregateRowsConfiguration = $this->rendererConfiguration->getConfigurationBuilder()->buildAggregateRowsConfig();
		$aggregateDataRow = $aggregateListData->getItemByIndex(0);

		foreach ($aggregateRowsConfiguration as $aggregateRowIndex => $aggregateRowConfiguration) {
			$renderedAggregateList->addRow($this->rowRenderer->renderAggregateRow($aggregateDataRow, $aggregateRowConfiguration, $aggregateRowIndex));
		}

		return $renderedAggregateList;
	}
}
?>