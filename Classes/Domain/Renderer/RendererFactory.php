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
 * Factory for renderer
 * 
 * @package Domain
 * @subpackage Renderer
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Renderer_RendererFactory {

    /**
     * Build and return the renderer
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Renderer_ConfigurableRendererInterface
     */	
	public static function getRenderer(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration) {
		$rendererClassName = $rendererConfiguration->getRendererClassName();
		tx_pttools_assert::isTrue(class_exists($rendererClassName), array('message' => 'Configured renderer class ' . $rendererClassName . ' does not exist! 1286986512'));

		$renderer = new $rendererClassName(); /* @var $renderer Tx_PtExtlist_Domain_Renderer_ConfigurableRendererInterface */
		tx_pttools_assert::isTrue(is_a($renderer, 'Tx_PtExtlist_Domain_Renderer_ConfigurableRendererInterface'), array('message' => 'Configured renderer class ' . $className . ' does not implement Tx_PtExtlist_Domain_Renderer_RendererInterface 1286986513'));
		
		$renderer->injectConfiguration($rendererConfiguration);
		
		return $renderer;
	}
	
}

?>