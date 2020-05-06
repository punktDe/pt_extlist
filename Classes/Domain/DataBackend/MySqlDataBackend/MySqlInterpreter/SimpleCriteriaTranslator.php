<?php


namespace PunktDe\PtExtlist\Domain\DataBackend\MySqlDataBackend\MySqlInterpreter;

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
class SimpleCriteriaTranslator implements \PunktDe\PtExtlist\Domain\DataBackend\CriteriaTranslatorInterface
{
    /**
     * translate simple criteria
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria|\PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria $criteria Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
     * @return string
     */
    public static function translateCriteria(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria)
    {
        return '' . $criteria->getField() . ' ' . $criteria->getOperator() . ' ' . self::wrapArrayInBrackets($criteria);
    }



    /**
     * Wraps an array in ("<array[0]>",...,"<array[n]>") and escapes values.
     * Returns string as escaped string if no array is given
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria
     * @return integer|mixed|string
     */
    public static function wrapArrayInBrackets(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria)
    {
        $connection = $GLOBALS['TYPO3_DB']; /** @var TYPO3\CMS\Core\Database\DatabaseConnection $connection */

        if (is_array($criteria->getValue())) {
            $escapedValues = $connection->fullQuoteArray($criteria->getValue(), '');
            $returnString = '(' . implode(',', $escapedValues) . ')';
        } elseif (is_numeric($criteria->getValue()) && !$criteria->getTreatValueAsString()) {
            $returnString = $criteria->getValue();
        } else {
            $returnString = $connection->fullQuoteStr($criteria->getValue(), '');
        }

        return $returnString;
    }
}
