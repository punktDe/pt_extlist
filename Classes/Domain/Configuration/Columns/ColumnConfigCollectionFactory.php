<?php
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
 * ColumnConfigCollectionFactory for ColumnConfig Objects
 *
 * @package Domain
 * @subpackage Configuration\Columns
 * @author Daniel Lienert
 * @author Michael Knoll
 * @author Christoph Ehscheidt
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Columns_ColumnConfigCollectionFactoryTest
 */
class Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollectionFactory
{
    /**
     * Build and return ColumnConfigurationCollection (as a singleton!)
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
     */
    public static function getInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder)
    {
        return self::buildColumnConfigCollection($configurationBuilder);
    }



    /**
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
     */
    protected static function buildColumnConfigCollection(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder)
    {
        $columnSettings = $configurationBuilder->getSettingsForConfigObject('columns');
        ksort($columnSettings);
        $columnConfigCollection = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection();

        $security = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('Tx_PtExtlist_Domain_Security_GroupSecurity'); /* @var $security Tx_PtExtlist_Domain_Security_GroupSecurity */

        foreach ($columnSettings as $columnId => $columnSetting) {
            $columnSettingMergedWithPrototype = $configurationBuilder->getMergedSettingsWithPrototype($columnSetting, 'column.default');
            $columnConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($configurationBuilder, $columnSettingMergedWithPrototype);

            // Inject security information
            $accessable = $security->isAccessableColumn($columnConfig);
            $columnConfig->setAccessable($accessable);

            $columnConfigCollection->addColumnConfig($columnId, $columnConfig);
        }

        return $columnConfigCollection;
    }
}
