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
 * Translator class for simple criteria for extbase data backend interpreter
 *
 * @package Domain
 * @subpackage DataBackend\ExtBaseDataBackend\ExtBaseInterpreter
 * @author Michael Knoll
 * @see
 */
class SimpleCriteriaTranslator
    implements \PunktDe\PtExtlist\Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter\ExtBaseCriteriaTranslatorInterface
{
    /**
     * Translates a query an manipulates given query object
     *  
     * TODO check, if there is already a constraint added to extbase query and use AND constraint then
     * TODO use AND to connect more than one constraint
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria Criteria to be translated
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery Query to add criteria to
     * @param \TYPO3\CMS\Extbase\Persistence\Repository $extbaseRepository Associated repository
     *
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Query
     *
     * @throws \Exception
     */
    public static function translateCriteria(
            \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria,
            \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery,
            \TYPO3\CMS\Extbase\Persistence\Repository $extbaseRepository)
    {
        Assert::isTrue(is_a($criteria, 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'),
              ['message' => 'Criteria is not a simple criteria! 1281724991']);
          /* @var $criteria Tx_PtExtlist_Domain_QueryObject_SimpleCriteria */
              
          $propertyName = self::getPropertyNameByCriteria($criteria);
              
        switch ($criteria->getOperator()) {
              case '.' :
                  self::addConstraint($extbaseQuery, $extbaseQuery->contains($propertyName, $criteria->getValue()));
              break;
              
              case '=' :
                  self::addConstraint($extbaseQuery, $extbaseQuery->equals($propertyName, $criteria->getValue()));
              break;
              
              case '<' :
                  self::addConstraint($extbaseQuery, $extbaseQuery->lessThan($propertyName, $criteria->getValue()));
              break;
              
              case '>' :
                  self::addConstraint($extbaseQuery, $extbaseQuery->greaterThan($propertyName, $criteria->getValue()));
              break;
              
              case '<=' :
                  self::addConstraint($extbaseQuery, $extbaseQuery->lessThanOrEqual($propertyName, $criteria->getValue()));
              break;
              
              case '>=' :
                  self::addConstraint($extbaseQuery, $extbaseQuery->greaterThanOrEqual($propertyName, $criteria->getValue()));
              break;
              
              case 'LIKE' :
                  self::addConstraint($extbaseQuery, $extbaseQuery->like($propertyName, $criteria->getValue()));
              break;
              
              case 'IN' :
                  self::addConstraint($extbaseQuery, $extbaseQuery->in($propertyName, $criteria->getValue()));
              break;
              
              default:
                  throw new Exception('No translation implemented for ' . $criteria->getOperator() . ' operator! 1281727494');
              break;
          }
          
        return $extbaseQuery;
    }
    
    
    
    /**
     * Adds given constraint to given query. Uses logical AND if there is already a constraint registered in query
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface $constraint
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Query
     */
    protected static function addConstraint(\TYPO3\CMS\Extbase\Persistence\Generic\Query $extbaseQuery, \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface $constraint)
    {
        if ($extbaseQuery->getConstraint() != null) {
            $extbaseQuery->matching($extbaseQuery->logicalAnd($extbaseQuery->getConstraint(), $constraint));
        } else {
            $extbaseQuery->matching($constraint);
        }
        return $extbaseQuery;
    }
    
    
    
    /**
     * Returns field name for a given criteria object
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria $criteria
     * @return string Fieldname
     */
    protected static function getPropertyNameByCriteria(\PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria $criteria)
    {
        list($predot, $postdot) = explode('.', $criteria->getField());
        return $postdot != '' ? $postdot : $predot;
    }
}
