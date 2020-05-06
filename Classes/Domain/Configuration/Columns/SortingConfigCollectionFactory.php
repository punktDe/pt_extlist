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
/**
 * @package 		Domain
 * @subpackage 		Configuration\Columns  
 * @author         	Daniel Lienert 
 */
class SortingConfigCollectionFactory
{
    /**
     * Parse the sorting config string and build sorting config objects
     *
     * @param string $sortingSettings
     * @return \PunktDe\PtExtlist\Domain\Configuration\Columns\SortingConfigCollection
     */
    public static function getInstanceBySortingSettings($sortingSettings)
    {
        $nameToConstantMapping = ['asc' => \PunktDe\PtExtlist\Domain\QueryObject\Query::SORTINGSTATE_ASC,
                                       'desc' => \PunktDe\PtExtlist\Domain\QueryObject\Query::SORTINGSTATE_DESC];

        // We create new sortingConfigCollection for column that can only be sorted as a whole
        $sortingConfigCollection = new \PunktDe\PtExtlist\Domain\Configuration\Columns\SortingConfigCollection(true);
        $sortingFields = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $sortingSettings);
        foreach ($sortingFields as $sortingField) {
            $sortingFieldOptions = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(' ', $sortingField);
            $fieldName  = $sortingFieldOptions[0];
            
            if ($fieldName) {
                $tempSortingDir = strtolower($sortingFieldOptions[1]);
                $sortingDir = \PunktDe\PtExtlist\Domain\QueryObject\Query::SORTINGSTATE_ASC;
                $forceSortingDir = false;

                if (in_array($tempSortingDir, ['asc', 'desc'])) {
                    $sortingDir = $nameToConstantMapping[$tempSortingDir];
                } elseif (in_array($tempSortingDir, ['!asc', '!desc'])) {
                    $forceSortingDir = true;
                    $sortingDir = $nameToConstantMapping[substr($tempSortingDir, 1)];
                }

                $sortingConfig = new \PunktDe\PtExtlist\Domain\Configuration\Columns\SortingConfig($fieldName, $sortingDir, $forceSortingDir);
                $sortingConfigCollection->addSortingField($sortingConfig, $fieldName);
            }
        }

        return $sortingConfigCollection;
    }



    /**
     * Factory method for creating a sortingField configuration for a given
     * column.sortingFields TS-configuration array.
     *
     * @param array $sortingFieldsSettings TypoScript settings for column.sortingFields
     * @return \PunktDe\PtExtlist\Domain\Configuration\Columns\SortingConfigCollection
     */
    public static function getInstanceBySortingFieldsSettings(array $sortingFieldsSettings)
    {
        $nameToConstantMapping = ['asc' => \PunktDe\PtExtlist\Domain\QueryObject\Query::SORTINGSTATE_ASC,
                                       'desc' => \PunktDe\PtExtlist\Domain\QueryObject\Query::SORTINGSTATE_DESC];

        // We create sortingConfigCollection that can handle sorting of individual fields
        $sortingConfigCollection = new \PunktDe\PtExtlist\Domain\Configuration\Columns\SortingConfigCollection(false);

        foreach ($sortingFieldsSettings as $fieldNumber => $sortingFieldSetting) {
            $fieldIdentifier = $sortingFieldSetting['field'];
            $sortingDirection = $nameToConstantMapping[strtolower($sortingFieldSetting['direction'])];
            $forceSortingDirection = $sortingFieldSetting['forceDirection'];
            $label = $sortingFieldSetting['label'];

            $sortingFieldConfig = new \PunktDe\PtExtlist\Domain\Configuration\Columns\SortingConfig(
                $fieldIdentifier,
                $sortingDirection,
                $forceSortingDirection,
                $label
            );

            $sortingConfigCollection->addSortingField($sortingFieldConfig, $fieldIdentifier);
        }

        return $sortingConfigCollection;
    }



    /**
     * Generate an array by field configuration - direction is NULL here
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection $fieldConfigCollection
     * @return \PunktDe\PtExtlist\Domain\Configuration\Columns\SortingConfigCollection
     */
    public static function getInstanceByFieldConfiguration(\PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection $fieldConfigCollection)
    {
        // We create a sorting field configuration that only sorts a whole column at once (hence param is true)
        $sortingConfigCollection = new \PunktDe\PtExtlist\Domain\Configuration\Columns\SortingConfigCollection(true);
        foreach ($fieldConfigCollection as $fieldConfig) {
            // We create sorting config with descending sorting as default sorting
            $sortingConfig = new \PunktDe\PtExtlist\Domain\Configuration\Columns\SortingConfig($fieldConfig->getIdentifier(), \PunktDe\PtExtlist\Domain\QueryObject\Query::SORTINGSTATE_ASC, false);
            $sortingConfigCollection->addSortingField($sortingConfig, $fieldConfig->getIdentifier());
        }
        
        return $sortingConfigCollection;
    }
}
