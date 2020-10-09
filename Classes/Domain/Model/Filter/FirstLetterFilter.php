<?php

namespace PunktDe\PtExtlist\Domain\Model\Filter;

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

use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\QueryObject\Criteria;

/**
 * Class implements the firstLetter filter
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter
 */
class FirstLetterFilter extends AbstractOptionsFilter
{
    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/AbstractOptionsFilter::buildFilterCriteria()
     */
    protected function buildFilterCriteria(FieldConfig $fieldIdentifier)
    {
        $criteria = null;
        $columnName = $fieldIdentifier->getTableFieldCombined();
        $filterValues = array_filter($this->filterValues);
        
        if (count($filterValues)) {
            $criteria = Criteria::like($columnName, current($filterValues).'%');
        }
        
        return $criteria;
    }
}
