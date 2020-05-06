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
 * Interface for criteria translator classes for extbase data backend 
 *
 * @package Domain
 * @subpackage DataBackend\ExtBaseDataBackend\ExtBaseInterpreter
 * @author Michael Knoll 
 */
interface Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseCriteriaTranslatorInterface
{
    /**
     * Translates a query an manipulates given query object
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria Criteria to be translated
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery Query to add criteria to
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $extbaseRepository Associated repository
     */
    public static function translateCriteria(
           \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria,
           \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery,
           \TYPO3\CMS\Extbase\Persistence\Repository $extbaseRepository);
}
