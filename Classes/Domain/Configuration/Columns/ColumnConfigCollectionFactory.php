<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Columns;

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
 * ColumnConfigCollectionFactory for ColumnConfig Objects
 *
 * @package Domain
 * @subpackage Configuration\Columns
 * @author Daniel Lienert
 * @author Michael Knoll
 * @author Christoph Ehscheidt
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Columns_ColumnConfigCollectionFactoryTest
 */
class ColumnConfigCollectionFactory
{
    /**
     * Build and return ColumnConfigurationCollection (as a singleton!)
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @return ColumnConfigCollection
     */
    public static function getInstance(ConfigurationBuilder $configurationBuilder)
    {
        return self::buildColumnConfigCollection($configurationBuilder);
    }



    /**
     * @param ConfigurationBuilder $configurationBuilder
     * @return ColumnConfigCollection
     */
    protected static function buildColumnConfigCollection(ConfigurationBuilder $configurationBuilder)
    {
        $columnSettings = $configurationBuilder->getSettingsForConfigObject('columns');
        ksort($columnSettings);
        $columnConfigCollection = new ColumnConfigCollection();

        $security = GeneralUtility::makeInstance(ObjectManager::class)->get(GroupSecurity::class); /* @var $security GroupSecurity */

        foreach ($columnSettings as $columnId => $columnSetting) {
            $columnSettingMergedWithPrototype = $configurationBuilder->getMergedSettingsWithPrototype($columnSetting, 'column.default');
            $columnConfig = new ColumnConfig($configurationBuilder, $columnSettingMergedWithPrototype);

            // Inject security information
            $accessable = $security->isAccessableColumn($columnConfig);
            $columnConfig->setAccessable($accessable);

            $columnConfigCollection->addColumnConfig($columnId, $columnConfig);
        }

        return $columnConfigCollection;
    }
}
