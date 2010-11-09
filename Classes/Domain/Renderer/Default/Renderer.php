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
 * Default renderer for list data
 * 
 * @package Domain
 * @subpackage Renderer
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 * @author Michael Knoll <knoll@punkt.de>
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
	public function injectConfiguration(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration) {
		// TODO remove this after refactoring!
        parent::injectConfiguration($rendererConfiguration);
        $this->initRowRenderer();
    }
    
    
    
	/**
	 * Initializes the rendering strategies
	 *
	 */
	public function initRowRenderer() {
		$this->rowRenderer = new Tx_PtExtlist_Domain_Renderer_Default_RowRenderer();
		$this->rowRenderer->injectRendererConfiguration($this->rendererConfiguration);
		// TODO change this to cell renderer type
		$this->rowRenderer->injectCellRenderer(new Tx_PtExtlist_Domain_Renderer_Default_DefaultCellRenderingStrategy($this->rendererConfiguration));
		
		$this->captionRenderer = new Tx_PtExtlist_Domain_Renderer_Default_DefaultCaptionRenderingStrategy();
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
		if(!$this->rendererConfiguration->isEnabled()) return $listData;
		
		tx_pttools_assert::isNotNull($listData, array(message => 'No list data found in list. 1280405145'));
		
		$renderedList = new Tx_PtExtlist_Domain_Model_List_ListData();

		foreach($listData as $rowIndex => $row) {
			$renderedList->addRow($this->rowRenderer->renderRow($row, $rowIndex));
			
			
			#$renderedList->addRow($this->renderRow($row, $rowIndex));
		}
		
		return $renderedList;
	}
	
}

?>