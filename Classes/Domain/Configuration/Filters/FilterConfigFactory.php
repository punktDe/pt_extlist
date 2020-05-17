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


use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Security\GroupSecurity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class implementing factory for filter configuration
 *  
 * @package Domain
 * @subpackage Configuration\Filters
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Filters_FilterConfigFactoryTest
 */
class FilterConfigFactory
{
    public static function createInstance(ConfigurationBuilder $configurationBuilder, $filterboxIdentifier, array $filterSettings)
    {
        $filterConfig = new FilterConfig($configurationBuilder, $filterSettings, $filterboxIdentifier);
        $filterConfig = self::setAccessableFlag($filterConfig, $configurationBuilder);
        return $filterConfig;
    }



    /**
     * Sets accessible flag for filter
     *
     * @param FilterConfig $filterConfig
     * @param ConfigurationBuilder $configurationBuilder
     * @return FilterConfig
     */
    protected static function setAccessableFlag(FilterConfig $filterConfig, ConfigurationBuilder $configurationBuilder)
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class); /* @var $objectManager ObjectManager */
        $security = $objectManager->get(GroupSecurity::class); /* @var $security GroupSecurity */
        $accessable = $security->isAccessableFilter($filterConfig, $configurationBuilder);
        $filterConfig->setAccessable($accessable);
        
        return $filterConfig;
    }
}
