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
 * Class implements an abstract configuration object
 *
 * @package Domain
 * @subpackage Configuration
 * @author Michael Knoll 
 * @author Daniel Lienert 
 */
abstract class AbstractExtlistConfiguration extends \PunktDe\PtExtbase\Configuration\AbstractConfiguration
{
    /**
     * The listidentifier this config object belings to
     *  
     * @var string
     */
    protected $listIdentifier;
    
    
    
    /**
     * Constructor for configuration object
     *  
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @param array $settings
     */
    public function __construct(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder, array $settings = [])
    {
        $this->listIdentifier = $configurationBuilder->getListIdentifier();
        parent::__construct($configurationBuilder, $settings);
    }



    /**
     * Returns a reference to the extlist configurationbuilder
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder
     */
    public function getConfigurationBuilder()
    {
        return $this->configurationBuilder;
    }


    
    /**
     * @return string listIdentifier
     */
    public function getListIdentifier()
    {
        return $this->listIdentifier;
    }
}
