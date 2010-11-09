<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de
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
 * Abstract class for list renderers
 * 
 * @package Domain
 * @subpackage Renderer
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * @author Michael Knoll <knoll@punkt.de>
 */
abstract class Tx_PtExtlist_Domain_Renderer_AbstractRenderer implements Tx_PtExtlist_Domain_Renderer_ConfigurableRendererInterface {

	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig
	 */
	protected $rendererConfiguration;
	
	
	
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
	 * Inject the Configuration Builder
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration
	 */
	public function injectConfiguration(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration) {
		$this->rendererConfiguration = $rendererConfiguration;
	}
	
}

?>