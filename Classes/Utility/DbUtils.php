<?php


namespace PunktDe\PtExtlist\Utility;

use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection;

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
 * Class contains utility functions concerning database related stuff
 *
 * @author Daniel Lienert
 * @author Michael Knoll
 * @package Utility
 * @see Tx_PtExtlist_Tests_Utility_DbUtilsTest
 */
class DbUtils
{
    /**
     * Creates an aliased select part for given field config
     *
     * <table>.<field> as <fieldIdentifier>
     *
     * Or: if a special mysql string is given
     * <special mysql string> as <fieldIdentifier>
     *
     * @param FieldConfig $fieldConfiguration
     * @return string
     */
    public static function getAliasedSelectPartByFieldConfig(FieldConfig $fieldConfiguration)
    {
        return self::getSelectPartByFieldConfig($fieldConfiguration) . ' AS ' . $fieldConfiguration->getIdentifier();
    }



    /**
     * Creates the select part for given field config
     *
     * <table>.<field>
     *
     * Or: if a special mysql string is given
     * <special mysql string>
     *
     * @param FieldConfig $fieldConfiguration
     * @return string
     */
    public static function getSelectPartByFieldConfig(FieldConfig $fieldConfiguration)
    {
        if ($fieldConfiguration->getSpecial()) {
            $selectPart = '(' . $fieldConfiguration->getSpecial() . ')';
        } else {
            $selectPart = $fieldConfiguration->getTableFieldCombined();
        }

        return $selectPart;
    }



    /**
     * Turns a fieldConfigCollection into a list of comma separated selectParts
     *
     * @static
     * @param FieldConfigCollection $fieldConfigCollection
     * @return string
     */
    public static function getSelectPartByFieldConfigCollection(FieldConfigCollection $fieldConfigCollection)
    {
        $selectParts = [];

        foreach ($fieldConfigCollection as $field) {
            $selectParts[] = self::getSelectPartByFieldConfig($field);
        }

        return implode(', ', $selectParts);
    }
}
