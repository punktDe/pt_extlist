<?php

namespace PunktDe\PtExtlist\Domain\Model\Filter;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Simon Schaufelberger
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

use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\QueryObject\Criteria;
use PunktDe\PtExtlist\Utility\DbUtils;

/**
 * Filter for time range
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter
 */
class TimeSpanFilter extends AbstractTimeSpanFilter
{
    /**
     * @param FieldConfig $fieldStart
     * @param FieldConfig $fieldEnd
     * @return Criteria
     *
     * TODO: Optimize this for a 1-field query
     */
    protected function buildTimeSpanFilterCriteria(FieldConfig $fieldStart, FieldConfig $fieldEnd)
    {
        $fieldStartName = DbUtils::getSelectPartByFieldConfig($fieldStart);
        $fieldEndName = DbUtils::getSelectPartByFieldConfig($fieldEnd);

        $startValueCriteria = Criteria::andOp(
                                        Criteria::lessThanEquals($fieldStartName, $this->getFilterValueStartInDBFormat()),
                                        Criteria::greaterThanEquals($fieldEndName, $this->getFilterValueStartInDBFormat()));

        $endValueCriteria = Criteria::andOp(
                                        Criteria::lessThanEquals($fieldStartName, $this->getFilterValueEndInDBFormat()),
                                        Criteria::greaterThanEquals($fieldEndName, $this->getFilterValueEndInDBFormat()));

        $betweenValuesCriteria = Criteria::andOp(
                                        Criteria::greaterThanEquals($fieldStartName, $this->getFilterValueStartInDBFormat()),
                                        Criteria::lessThanEquals($fieldEndName, $this->getFilterValueEndInDBFormat()));



        $criteria = Criteria::orOp(
            Criteria::orOp($startValueCriteria, $endValueCriteria),
            $betweenValuesCriteria
        );
                
        return $criteria;
    }
}
