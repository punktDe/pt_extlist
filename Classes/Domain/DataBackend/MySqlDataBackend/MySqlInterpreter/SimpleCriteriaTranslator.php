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
 * Translator for AND criteria
 *
 * @package Domain
 * @subpackage DataBackend\MySqlDataBackend\MySqlInterpreter
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator implements Tx_PtExtlist_Domain_DataBackend_CriteriaTranslatorInterface
{
    /**
     * translate simple criteria
     *
     * @param \Tx_PtExtlist_Domain_QueryObject_Criteria|\Tx_PtExtlist_Domain_QueryObject_SimpleCriteria $criteria Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
     * @return string
     */
    public static function translateCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria)
    {
        return '' . $criteria->getField() . ' ' . $criteria->getOperator() . ' ' . self::wrapArrayInBrackets($criteria->getValue());
    }



    /**
     * Wraps an array in ("<array[0]>",...,"<array[n]>") and escapes values.
     * Returns string as escaped string if no array is given
     *
     * @param mixed $value
     * @return integer|mixed|string
     */
    public static function wrapArrayInBrackets($value)
    {
        $connection = $GLOBALS['TYPO3_DB']; /** @var TYPO3\CMS\Core\Database\DatabaseConnection $connection */

        if (is_array($value)) {
            $escapedValues = $connection->fullQuoteArray($value, '');
            $returnString = '(' . implode(',', $escapedValues) . ')';
        } elseif (is_numeric($value)) {
            $returnString = $value;
        } else {
            $returnString = $connection->fullQuoteStr($value, '');
        }

        return $returnString;
    }
}
