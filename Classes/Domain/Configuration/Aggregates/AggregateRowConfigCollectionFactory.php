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
/**
 * AggregateRowConfigCollectionFactory for Aggregate row config collection Objects 
 *
 * @package Domain
 * @subpackage Configuration\Aggregates
 * @author Daniel Lienert 
 */
class AggregateRowConfigCollectionFactory
{
    /**
     * Build and return an AggregateRowConfigCollection
     *    
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $columnSettings typoscript array of column Collection
     * @return \PunktDe\PtExtlist\Domain\Configuration\Columns\ColumnConfigCollection
     */
    public static function getInstance(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        return self::buildAggregateRowConfigCollection($configurationBuilder);
    }
    
    
    
    /**
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @return \PunktDe\PtExtlist\Domain\Configuration\Aggregates\AggregateRowConfigCollection
     */
    protected static function buildAggregateRowConfigCollection(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $aggregateRowSettings = $configurationBuilder->getSettingsForConfigObject('aggregateRows');
        ksort($aggregateRowSettings);
        
        $aggregateRowConfigCollection = new \PunktDe\PtExtlist\Domain\Configuration\Aggregates\AggregateRowConfigCollection();

        foreach ($aggregateRowSettings as $rowId => $rowSetting) {
            $aggregateRowConfigCollection->addAggregateRowConfig(\PunktDe\PtExtlist\Domain\Configuration\Aggregates\AggregateRowConfigFactory::getAggregateRowConfig($configurationBuilder, $rowSetting), $rowId);
        }
        
        return $aggregateRowConfigCollection;
    }
}
