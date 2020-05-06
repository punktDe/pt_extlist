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
 * Class implements a 'not' operator for criterias
 *
 * @package Domain
 * @subpackage QueryObject
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_QueryObject_NotCriteriaTest
 */
class NotCriteria extends \PunktDe\PtExtlist\Domain\QueryObject\Criteria
{
    /**
     * Criteria to be negated
     *
     * @var \PunktDe\PtExtlist\Domain\QueryObject\Criteria
     */
    protected $criteria;
    
    
    
    /**
     * Constructor for not criteria. Takes a criteria as parameter
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria
     */
    public function __construct(\PunktDe\PtExtlist\Domain\QueryObject\Criteria $criteria)
    {
        $this->criteria = $criteria;
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
            if ($this->criteria->isEqualTo($criteria)) {
                return true;
            }
        }
        return false;
    }
    
    
    
    /**
     * Returns criteria to be negated
     *
     * @return \PunktDe\PtExtlist\Domain\QueryObject\Criteria
     */
    public function getCriteria()
    {
        return $this->criteria;
    }
}
