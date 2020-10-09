<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates;


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
 *  AggregateConfigCollection Factory
 *
 * @package Domain
 * @subpackage Configuration\Data\Aggregates
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactoryTest
 */
class AggregateConfigCollectionFactory
{
    /**
     * Returns an instance of a aggregate config collection for given aggregate settings
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @return AggregateConfigCollection
     */
    public static function getInstance(ConfigurationBuilder $configurationBuilder)
    {
        $aggregateConfigCollection = self::buildAggregateConfigCollection($configurationBuilder);
        return $aggregateConfigCollection;
    }



    /**
     * Builds a collection of aggregate config objects for a given settings array
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @return ConfigurationBuilder $configurationBuilder
     */
    protected static function buildAggregateConfigCollection(ConfigurationBuilder $configurationBuilder)
    {
        $aggregateConfigCollection = new AggregateConfigCollection();
        $aggregateSettingsArray = $configurationBuilder->getSettingsForConfigObject('aggregateData');
        
        foreach ($aggregateSettingsArray as $aggregateIdentifier => $aggregateSettings) {
            $aggregateConfig = new AggregateConfig($aggregateIdentifier, $aggregateSettings, $configurationBuilder);
            $aggregateConfigCollection->addAggregateConfig($aggregateConfig);
        }
        return $aggregateConfigCollection;
    }
}
