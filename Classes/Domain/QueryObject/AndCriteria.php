<?php


namespace PunktDe\PtExtlist\Domain\QueryObject;

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
 * AND criteria
 *
 * @package Domain
 * @subpackage QueryObject
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_QueryObject_AndCriteriaTest
 */
class AndCriteria extends \PunktDe\PtExtlist\Domain\QueryObject\Criteria
{
    /**
    * Holds first criteria to be used with and conjunction
    *
    * @var \PunktDe\PtExtlist\Domain\QueryObject\Criteria
    */
   protected $firstCriteria;
   
   
   
   /**
    * Holds second criteria to be used with conjunction
    *
    * @var \PunktDe\PtExtlist\Domain\QueryObject\Criteria
    */
   protected $secondCriteria;
   
   
   
   /**
    * Constructor takes two criterias to be conjuncted with AND
    *
    * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $firstCriteria
    * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $secondCriteria
    */
   public function __construct(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $firstCriteria, \PunktDe\PtExtlist\Domain\QueryObject\Criteria $secondCriteria)
   {
       $this->firstCriteria = $firstCriteria;
       $this->secondCriteria = $secondCriteria;
   }
   
   
   
   /**
    * Returns true, if given criteria is equal to this object
    *
    * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria Criteria to be compared with this object
    * @return bool
    */
   public function isEqualTo(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria)
   {
       if (is_a($criteria, __CLASS__)) {
           if ($this->firstCriteria->isEqualTo($criteria->firstCriteria) && $this->secondCriteria->isEqualTo($criteria->secondCriteria)) {
               return true;
           }
       }
       return false;
   }
   
   
   
   /**
    * Return first criteria of and criteria
    *
    * @return \PunktDe\PtExtlist\Domain\QueryObject\Criteria
    */
   public function getFirstCriteria()
   {
       return $this->firstCriteria;
   }
   
   
   
   /**
    * Returns second criteria of and criteria
    *
    * @return \PunktDe\PtExtlist\Domain\QueryObject\Criteria
    */
   public function getSecondCriteria()
   {
       return $this->secondCriteria;
   }
}
