<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Renderer;

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
 * Class implements renderer chain configuration as a collection of renderer
 *
 * @package Domain
 * @subpackage Configuration\Renderer
 * @author Daniel Lienert 
 */
class RendererChainConfig extends \PunktDe\PtExtbase\Collection\ObjectCollection
{
    /**
     * Holds TS settings for rendering chain
     *
     * @var array
     */
    protected $settings;
    
    
    
    /**
     * List Identifier
     * @var string
     */
    protected $listIdentifier;
    
    
    
    /**
     * If set to false, no rendering will happen
     *
     * @var bool
     */
    protected $isEnabled = true;
    

    
    /**
     * Classname for this collection object type
     * @var string
     */
    protected $restrictedClassName = 'Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig';
    
    
    
    /**
     * Holds an instance of configuration builder
     *
     * @var \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder
     */
    protected $configurationBuilder;
    
    
    
    /**
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     */
    public function __construct(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder, array $rendererChainSettings)
    {
        $this->configurationBuilder = $configurationBuilder;
        $this->listIdentifier = $configurationBuilder->getListIdentifier();
        $this->settings = $rendererChainSettings;
        $this->init();
    }
    
    
    
    /**
     * Initializes configuration object
     */
    protected function init()
    {
        if ($this->settings['enabled'] == 0) {
            $this->isEnabled = false;
        }
    }

    
    
    /**
     * Add renderConfig to list
     *  
     * @param \PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererConfig $rendererConfig
     * @param string $rendererConfigIdentifier
     */
    public function addRendererConfig(\PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererConfig $rendererConfig, $rendererConfigIdentifier)
    {
        $this->addItem($rendererConfig, $rendererConfigIdentifier);
    }
    
    
    
    /**
     * Getter for configuration builder
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder
     */
    public function getConfigurationBuilder()
    {
        return $this->configurationBuilder;
    }
    
    
    
    /**
     * Returns true, if rendering is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }
    
    
    
    /**
     * Returns TS settings for rendering chain
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }
}
