<?php


namespace PunktDe\PtExtlist\Domain\Renderer;

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
 * Class implements factory for renderer chain. 
 *
 * @package Domain
 * @subpackage Renderer
 * @author Michael Knoll 
 */
class RendererChainFactory
    extends \PunktDe\PtExtlist\Domain\AbstractComponentFactory
    implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @var \PunktDe\PtExtlist\Domain\Renderer\RendererFactory
     */
    protected $rendererFactory;



    /**
     * @param \PunktDe\PtExtlist\Domain\Renderer\RendererFactory $rendererFactory
     */
    public function injectRendererFactory(\PunktDe\PtExtlist\Domain\Renderer\RendererFactory $rendererFactory)
    {
        $this->rendererFactory = $rendererFactory;
    }



    /**
     * Creates an instance of renderer chain object for given renderer chain configuration
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererChainConfig $rendererChainConfiguration
     * @return \PunktDe\PtExtlist\Domain\Renderer\RendererChain
     */
    public function getRendererChain(\PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererChainConfig $rendererChainConfiguration)
    {
        $rendererChain = $this->objectManager->get('Tx_PtExtlist_Domain_Renderer_RendererChain', $rendererChainConfiguration);
        //$rendererChain = new Tx_PtExtlist_Domain_Renderer_RendererChain($rendererChainConfiguration);
        foreach ($rendererChainConfiguration as $rendererConfiguration) {
            $renderer = $this->rendererFactory->getRenderer($rendererConfiguration);
            $rendererChain->addRenderer($renderer);
        }
        return $rendererChain;
    }
}
