<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Pager;


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

use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;

/**
 * Factory to create configs for pager
 *
 * @package Domain
 * @subpackage Configuration\Pager
 * @author Daniel Lienert 
 * @author Christoph Ehscheidt
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Pager_PagerConfigFactoryTest
 */
class PagerConfigFactory
{
    /**
     * Returns a instance of a pager configuration.
     *  
     * @param ConfigurationBuilder $configurationBuilder
     * @param string pagerIdentifier
     * @param array $pagerSettings
     * @return PagerConfig
     */
    public static function getInstance(ConfigurationBuilder $configurationBuilder, $pagerIdentifier,  array $pagerSettings)
    {
        $pagerConfig = new PagerConfig($configurationBuilder, $pagerIdentifier, $pagerSettings);
        
        return $pagerConfig;
    }
}
