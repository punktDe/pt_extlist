<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Renderer;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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

use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;

/**
 * Class implementing factory for for the renderer chain configuration
 *  
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Configuration\Renderer
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Renderer_RendererChainConfigFactoryTest
 */
class RendererChainConfigFactory
{
    /**
     * @param ConfigurationBuilder $configurationBuilder
     * @return RendererChainConfig
     */
    public static function getInstance(ConfigurationBuilder $configurationBuilder)
    {
        $rendererChainConfiguration = null;
        $rendererChainSettings = $configurationBuilder->getSettingsForConfigObject('rendererChain');

        if (is_array($rendererChainSettings['rendererConfigs'])) {
            ksort($rendererChainSettings['rendererConfigs']);
            $rendererChainConfiguration = new RendererChainConfig($configurationBuilder, $rendererChainSettings);

            foreach ($rendererChainSettings['rendererConfigs'] as $rendererIdentifier => $rendererSettings) {
                $rendererConfiguration = RendererConfigFactory::getRendererConfiguration($configurationBuilder, $rendererSettings);
                $rendererChainConfiguration->addRendererConfig($rendererConfiguration, $rendererIdentifier);
            }
        }

        return $rendererChainConfiguration;
    }
}
