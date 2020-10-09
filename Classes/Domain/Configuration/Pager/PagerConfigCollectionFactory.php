<?php
namespace PunktDe\PtExtlist\Domain\Configuration\Pager;

use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;

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
 * Class implementing factory for collection of filterbox configurations
 *  
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Configuration\Pager
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Pager_PagerConfigCollectionFactoryTest
 */
class PagerConfigCollectionFactory
{
    /**
     * @param ConfigurationBuilder $configurationBuilder
     * @return PagerConfigCollection
     */
    public static function getInstance(ConfigurationBuilder $configurationBuilder)
    {
        $pagerCollectionSettings = $configurationBuilder->getSettingsForConfigObject('pager');

        $pagerConfigCollection = new PagerConfigCollection($configurationBuilder);
        
        foreach ($pagerCollectionSettings['pagerConfigs'] as $pagerIdentifier => $pagerSettings) {
            $pagerSettings['itemsPerPage'] = $pagerCollectionSettings['itemsPerPage'];
            $pagerConfiguration = PagerConfigFactory::getInstance($configurationBuilder, $pagerIdentifier, $pagerSettings);

            $pagerConfigCollection->addPagerConfig($pagerConfiguration, $pagerIdentifier);
        }

        return $pagerConfigCollection;
    }
}
