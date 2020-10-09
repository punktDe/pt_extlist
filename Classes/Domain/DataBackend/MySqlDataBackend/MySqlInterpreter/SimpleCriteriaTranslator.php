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

use PunktDe\PtExtlist\Domain\QueryObject\Criteria;
use PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria;
use PunktDe\PtExtlist\Utility\DbUtils;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
     * @param SimpleCriteria|Criteria $criteria
     * @return string
     */
    public static function translateCriteria($criteria): string
    {
        return '' . $criteria->getField() . ' ' . $criteria->getOperator() . ' ' . self::wrapArrayInBrackets($criteria);
    }



    /**
     * Wraps an array in ("<array[0]>",...,"<array[n]>") and escapes values.
     * Returns string as escaped string if no array is given
     *
     * @param Criteria $criteria
     * @return integer|mixed|string
     */
    public static function wrapArrayInBrackets(SimpleCriteria $criteria)
    {
        ###TODO Default Database
        /** @var Connection $connection */
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionByName(DbUtils::getDefaultDatabase());

        if (is_array($criteria->getValue())) {
            ###TODO must be 
            //$escapedValues = $connection->quote($criteria->getValue(), Connection::PARAM_STR_ARRAY);

            $escapedValues = $criteria->getValue();
            $returnString = '(' . implode(',', $escapedValues) . ')';
        } elseif (is_numeric($criteria->getValue()) && !$criteria->getTreatValueAsString()) {
            $returnString = $criteria->getValue();
        } else {
            $returnString = $connection->quote($criteria->getValue(), \PDO::PARAM_STR);
        }

        return $returnString;
    }
}
