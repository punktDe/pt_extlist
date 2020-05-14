<?php


namespace PunktDe\PtExtlist\Domain\Configuration;

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
 * Factory for Configuration Builder
 *
 * @package Domain
 * @subpackage Configuration
 * @author Daniel Lienert
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderFactoryTests
 */
class ConfigurationBuilderFactory
{
    /**
     * @var \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderInstancesContainer
     */
    private $configurationBuilderInstancesContainer;


    
    /**
     * Holds an array of all extList settings
     *  
     * @var array
     */
    private $settings = null;



    /**
     * Holds extbase context to determine FE / BE usage
     *
     * @var \PunktDe\PtExtlist\Extbase\ExtbaseContext
     */
    private $extbaseContext;



    /**
     * Injects configuration manager (that holds TS settings) for usage with DI
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager
     */
    public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager)
    {
        $this->settings = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
    }



    /**
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderInstancesContainer $instancesContainer
     */
    public function injectConfigurationBuilderInstancesContainer(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderInstancesContainer $instancesContainer)
    {
        $this->configurationBuilderInstancesContainer = $instancesContainer;
    }



    /**
     * As we sometimes do not have a configuration manager
     * with relevant TS settings, we can set them here manually.
     *
     * TODO at the moment we need this for extlist context. This should be fixed, once we have bootstrap and new DI
     *
     * TODO Think about the problem, that this class is a singleton, hence we get the same instance over and over, once it is
     * TODO instantiated. BUT settings are likely to change from one request to this instance to another, so we need a possibility to
     * TODO get different instances for different settings.
     *
     * @param array $settings
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }



    /**
     * Injects extbase context (for determine FE / BE usage) for usage with DI
     *
     * @param \PunktDe\PtExtlist\Extbase\ExtbaseContext $extbaseContext
     */
    public function injectExtbaseContext(\PunktDe\PtExtlist\Extbase\ExtbaseContext $extbaseContext)
    {
        $this->extbaseContext = $extbaseContext;
    }


    
    /**
     * Returns a singleton instance of a configurationBuilder class
     *
     * @static
     * @param string $listIdentifier the listidentifier of the list
     * @param boolean $resetConfigurationBuilder
     * @return \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder
     * @throws \Exception
     */
    public function getInstance($listIdentifier = null, $resetConfigurationBuilder = false)
    {
        if ($listIdentifier == null) {
            $listIdentifier = $this->extbaseContext->getCurrentListIdentifier();
        }

        if ($listIdentifier == '') {
            throw new \Exception('No list identifier could be found in settings!', 1280230579);
        }

        if ($resetConfigurationBuilder) {
            $this->configurationBuilderInstancesContainer->remove($listIdentifier); // Make sure, we overwrite a previously added configuration builder if we want to reset
        }

        if (!$this->configurationBuilderInstancesContainer->contains($listIdentifier)) {
            if (!is_array($this->settings['listConfig']) || !array_key_exists($listIdentifier, $this->settings['listConfig'])) {
                throw new \Exception('No list with listIdentifier ' . $listIdentifier . ' could be found in settings! Available are: ' .
                    implode(', ', is_array($this->settings['listConfig']) ? array_keys($this->settings['listConfig']) : []),
                    1288110596
                );
            }

            // TODO use object manager to instantiate the configuration builder object
            $this->configurationBuilderInstancesContainer->add(new \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder($this->settings, $listIdentifier));
        }

        return $this->configurationBuilderInstancesContainer->get($listIdentifier);
    }
}
