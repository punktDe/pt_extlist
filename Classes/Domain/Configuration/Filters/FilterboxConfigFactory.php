<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Filters;


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

use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtbase\Exception\Assertion;
use PunktDe\PtExtbase\Exception\InternalException;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;

/**
 * Factory for filterbox configuration
 *  
 * @author Michael Knoll 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Configuration\Filters
 * @see class Tx_PtExtlist_Tests_Domain_Configuration_Filters_FilterboxConfigFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase {
 */
class FilterboxConfigFactory
{
    /**
     * @param ConfigurationBuilder $configurationBuilder
     * @param $filterboxIdentifier
     * @param array $filterBoxSettings
     * @return FilterboxConfig
     * @throws Assertion
     * @throws InternalException
     */
    public static function createInstance(ConfigurationBuilder $configurationBuilder, $filterboxIdentifier, array $filterBoxSettings)
    {
        $filterboxConfiguration = new FilterboxConfig($configurationBuilder, $filterboxIdentifier, $filterBoxSettings);

        $filterSettingsArray = is_array($filterBoxSettings['filterConfigs']) ? $filterBoxSettings['filterConfigs'] : [];
        ksort($filterSettingsArray);

        foreach ($filterSettingsArray as $arrayIndex => $filterSettings) {
            Assert::isArray($filterSettings, ['message' => 'No array given for filter settings. Perhaps misconfiguration of TS for filterbox? 1280772788']);
            $filterConfig = FilterConfigFactory::createInstance($configurationBuilder, $filterboxIdentifier, $filterSettings);
            $filterboxConfiguration->addFilterConfig($filterConfig, $arrayIndex);
        }
        
        return $filterboxConfiguration;
    }
}
