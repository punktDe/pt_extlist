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
 * Factory for renderer
 * 
 * @package Domain
 * @subpackage Renderer
 * @author Christoph Ehscheidt 
 * @author Daniel Lienert 
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Renderer_RendererFactoryTest
 */
class Tx_PtExtlist_Domain_Renderer_RendererFactory
    extends Tx_PtExtlist_Domain_AbstractComponentFactory
    implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * Build and return the renderer
     *
     * @param Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration
     * @return Tx_PtExtlist_Domain_Renderer_ConfigurableRendererInterface
     */
    public function getRenderer(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration)
    {
        $rendererClassName = $rendererConfiguration->getRendererClassName();
        Tx_PtExtbase_Assertions_Assert::classExists($rendererClassName, array('message' => 'Configured renderer class ' . $rendererClassName . ' does not exist! 1286986512'));

        $renderer = $this->objectManager->get($rendererClassName); /* @var $renderer Tx_PtExtlist_Domain_Renderer_ConfigurableRendererInterface */
        Tx_PtExtbase_Assertions_Assert::isTrue(is_a($renderer, 'Tx_PtExtlist_Domain_Renderer_ConfigurableRendererInterface'), array('message' => 'Configured renderer class ' . $rendererClassName . ' does not implement Tx_PtExtlist_Domain_Renderer_RendererInterface 1286986513'));

        $renderer->_injectConfiguration($rendererConfiguration);
        
        $renderer->initRenderer();

        return $renderer;
    }
}
