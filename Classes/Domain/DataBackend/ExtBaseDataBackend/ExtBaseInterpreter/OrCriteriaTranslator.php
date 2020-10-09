<?php


namespace PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter;

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
 * Translator for or criteria for extbase data backend interpreter
 *
 * @package Domain
 * @subpackage DataBackend\ExtBaseDataBackend\ExtBaseInterpreter
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_OrCriteriaTranslatorTest
 */
class OrCriteriaTranslator
    implements \PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter\ExtBaseCriteriaTranslatorInterface
{
    /**
     * Translates a query an manipulates given query object
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria Criteria to be translated
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery Query to add criteria to
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $extbaseRepository Associated repository
     * @throws Exception
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Query
     */
    public static function translateCriteria(
        \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria,
        \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery,
        \TYPO3\CMS\Extbase\Persistence\Repository $extbaseRepository)
    {
        if (!is_a($criteria, 'Tx_PtExtlist_Domain_QueryObject_OrCriteria')) {
            throw new Exception('Given criteria is not of type Tx_PtExtlist_Domain_QueryObject_Criteria --> cannot be translated by or criteria translator! 1326466193');
        }

        /**
         * This is a little ugly here:
         *
         * As we do not create Extbase criterias from our generic pt_extlist criterias
         * but set the criterias directly on the created extbase query, we have to cheat
         * here and generate two helper queries, whenever a OR query has to be translated.
         *
         * After having translated the two criterias of the generic OR criteria, we
         * put them together again in a single extbase query.
         */
        $tmpQuery1 = $extbaseRepository->createQuery();
        $tmpQuery2 = $extbaseRepository->createQuery();
        // translate first OR criteria by creating a new extbase query
        $tmpQuery1 = \PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter\ExtBaseInterpreter::setCriteriaOnExtBaseQueryByCriteria($criteria->getFirstCriteria(), $tmpQuery1, $extbaseRepository);
        // translate second OR criteria by creating a new extbase query
        $tmpQuery2 = \PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter\ExtBaseInterpreter::setCriteriaOnExtBaseQueryByCriteria($criteria->getSecondCriteria(), $tmpQuery2, $extbaseRepository);
        // put both translated criterias together again in a single extbase query
        if ($extbaseQuery->getConstraint()) {
            $extbaseQuery->matching($extbaseQuery->logicalAnd($extbaseQuery->getConstraint(),
                $extbaseQuery->logicalOr($tmpQuery1->getConstraint(), $tmpQuery2->getConstraint())));
        } else {
            $extbaseQuery->matching($extbaseQuery->logicalOr($tmpQuery1->getConstraint(), $tmpQuery2->getConstraint()));
        }
        return $extbaseQuery;
    }
}
