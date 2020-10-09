<?php

namespace PunktDe\PtExtlist\Domain\Configuration\Aggregates;

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

use PunktDe\PtExtlist\Domain\Configuration\Columns\ColumnConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtbase\Exception\InternalException;

/**
 * AggregateRowConfigFactory for Aggregate config collection Objects 
 *
 * @package Domain
 * @subpackage Configuration\Aggregates
 * @author Daniel Lienert 
 */
class AggregateRowConfigFactory
{
    /**
     * Build and return AggregateRowConfigObject
     *  
     * @param ConfigurationBuilder $configurationBuilder
     * @param array $aggregateRowSettings
     * @return ColumnConfigCollection
     * @throws InternalException
     */
    public static function getAggregateRowConfig(ConfigurationBuilder $configurationBuilder, array $aggregateRowSettings)
    {
        return self::buildAggregateRowConfigObject($configurationBuilder, $aggregateRowSettings);
    }
    
    
    
    /**
     * @param ConfigurationBuilder $configurationBuilder
     * @param array $aggregateRowSettings
     * @return ColumnConfigCollection
     */
    /**
     * @param ConfigurationBuilder $configurationBuilder
     * @param array $aggregateRowSettings
     * @return AggregateRowConfig
     * @throws InternalException
     */
    protected static function buildAggregateRowConfigObject(ConfigurationBuilder $configurationBuilder, array $aggregateRowSettings)
    {
        $aggregateRowConfig = new AggregateRowConfig();
        
        foreach ($aggregateRowSettings as $columnIdentifier => $aggregateColumnSettings) {
            $aggregateColumnConfig = new AggregateColumnConfig($configurationBuilder, $aggregateColumnSettings, $columnIdentifier);
            $aggregateRowConfig->addAggregateColumnConfig($aggregateColumnConfig, $columnIdentifier);
        }
        
        return $aggregateRowConfig;
    }
}
