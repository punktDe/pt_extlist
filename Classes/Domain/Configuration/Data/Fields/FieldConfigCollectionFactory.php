<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Data\Fields;

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
 *  FieldConfigCollection Factory
 *
 * @package Domain
 * @subpackage Configuration\Data\Fields
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Data_Fields_FieldConfigCollectionFactoryTest
 */
class FieldConfigCollectionFactory
{
    /**
     * Returns an instance of a field config collection for given field settings
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @return \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection
     */
    public static function getInstance(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $fieldsSettings = $configurationBuilder->getSettingsForConfigObject('fields');
        $fieldConfigCollection = self::buildFieldConfigCollection($configurationBuilder, $fieldsSettings);
        return $fieldConfigCollection;
    }



    /**
     * Builds a collection of field config objects for a given settings array
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @param array $fieldSettingsArray
     * @return \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection
     */
    protected static function buildFieldConfigCollection(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder, array $fieldSettingsArray = null)
    {
        $fieldConfigCollection = new \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection();
        foreach ($fieldSettingsArray as $fieldIdentifier => $fieldSettings) {
            $fieldConfig = new \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig($configurationBuilder, $fieldIdentifier, $fieldSettings);
            //$fieldConfigCollection->addItem($fieldConfig, $fieldConfig->getIdentifier());
            $fieldConfigCollection->addFieldConfig($fieldConfig);
        }
        return $fieldConfigCollection;
    }
}
