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
 * Class implements an or criteria
 *
 * @package Domain
 * @subpackage QueryObject
 * @author Daniel Lienert
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_QueryObject_OrCriteriaTest
 */
class OrCriteria extends \PunktDe\PtExtlist\Domain\QueryObject\Criteria
{
    /**
     * Holds first criteria for or conjunction
     *
     * @var \PunktDe\PtExtlist\Domain\QueryObject\Criteria
     */
    protected $firstCriteria;
    
    
    
    /**
     * Holds second criteria for or conjunction
     *
     * @var \PunktDe\PtExtlist\Domain\QueryObject\Criteria
     */
    protected $secondCriteria;



    /**
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $firstCriteria
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $secondCriteria
     * @return \PunktDe\PtExtlist\Domain\QueryObject\OrCriteria
     */
    public function __construct(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $firstCriteria, \PunktDe\PtExtlist\Domain\QueryObject\Criteria $secondCriteria)
    {
        $this->firstCriteria = $firstCriteria;
        $this->secondCriteria = $secondCriteria;
    }
    
    
    
    /**
     * Returns true, if criteria is equal to this criteria
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria Criteria to be compared with this criteria
     * @return bool
     */
    public function isEqualTo(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria)
    {
        if (!is_a($criteria, __CLASS__)) {
            return false;
        } else {
            /* @var $criteria Tx_PtExtlist_Domain_QueryObject_OrCriteria */
            if ($this->firstCriteria == $criteria->firstCriteria || $this->secondCriteria == $criteria->secondCriteria) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    
    
    /**
     * @return \PunktDe\PtExtlist\Domain\QueryObject\Criteria
     */
    public function getFirstCriteria()
    {
        return $this->firstCriteria;
    }
    
    
    
    /**
     * @return \PunktDe\PtExtlist\Domain\QueryObject\Criteria
     */
    public function getSecondCriteria()
    {
        return $this->secondCriteria;
    }
}
