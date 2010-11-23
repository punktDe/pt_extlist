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
 * Class implements a chain of renderers, responsible for renderering list data
 *
 * @package Domain
 * @subpackage Renderer
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Renderer_RendererChain implements Tx_PtExtlist_Domain_Renderer_RendererInterface {
	
	/**
	 * Holds an array of renderers
	 *
	 * @var array<Tx_PtExtlist_Domain_Renderer_RendererInterface>
	 */
	protected $renderers = array();
	
	
	
	/**
	 * Holds an instance of renderer chain configuration
	 * @var Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfig
	 */
	protected $rendererChainConfiguration;

	
	
	/**
	 * Constructor for rendering chain
	 * @param Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfig $rendererChainConfiguration
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfig $rendererChainConfiguration) {
		$this->rendererChainConfiguration = $rendererChainConfiguration;
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Renderer_RendererInterface::renderCaptions()
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader
	 * @return Tx_PtExtlist_Domain_Model_List_Row
	 */
	public function renderCaptions(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader) {
		foreach ($this->renderers as $renderer) { /* @var $renderer Tx_PtExtlist_Domain_Renderer_RendererInterface */
		    $listHeader = $renderer->renderCaptions($listHeader);	
		}
		return $listHeader;
	}



	/**
	 * @see Tx_PtExtlist_Domain_Renderer_RendererInterface::renderList()
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $listData
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function renderList(Tx_PtExtlist_Domain_Model_List_ListData $listData) {
		if(!$this->rendererChainConfiguration->isEnabled()) return $listData;
		foreach ($this->renderers as $renderer) { /* @var $renderer Tx_PtExtlist_Domain_Renderer_RendererInterface */
			$listData = $renderer->renderList($listData);
		}
		return $listData;
	}
	
	
	
	/**
	 * Renders aggregated list data
	 * 
	 * @see Tx_PtExtlist_Domain_Renderer_RendererInterface::renderAggregateList()
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Row $aggregatedRow Row to be rendered
	 * @return Tx_PtExtlist_Domain_Model_List_ListData Rendered aggregated list data
	 */
	public function renderAggregateList(Tx_PtExtlist_Domain_Model_List_Row $aggregatedRow) {
		foreach($this->renderers as $renderer) { /* @var $renderer Tx_PtExtlist_Domain_Renderer_RendererInterface */
			// TODO fix this: only first renderer is used for aggregate list data
			$listData = $renderer->renderAggregateList($aggregatedRow);
			break;
		}
		return $listData;
 	}
	
	
	
	/**
	 * Adds a renderer to the list of renderers
	 *
	 * @param Tx_PtExtlist_Domain_Renderer_RendererInterface $renderer
	 */
	public function addRenderer(Tx_PtExtlist_Domain_Renderer_RendererInterface $renderer) {
		$this->renderers[] = $renderer;
	}
	
	
	
	/**
	 * Returns all renderers added to this chain
	 *
	 * @return array
	 */
	public function getRenderers() {
		return $this->renderers;
	}
	
}
 
?>