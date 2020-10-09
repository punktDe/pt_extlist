<?php

namespace PunktDe\PtExtlist\Domain\Model\Filter\DataProvider\TimeSpanAlgorithm;



/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Joachim Mathes
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

use PunktDe\PtExtbase\Collection\SortableObjectCollection;


/**
 * Time span collection
 *
 * @package Domain
 * @subpackage Model\Filter\DataProvider\TimeSpanAlgorithm
 * @author Joachim Mathes
 */
class TimeSpanCollection extends SortableObjectCollection
{
    /**
     * Restricted class name
     * @var string
     * @see \PunktDe\PtExtbase\Collection\ObjectCollection::checkItemType()
     */
    protected $restrictedClassName = TimeSpan::class;



    public function getJsonValue()
    {
        $timeSpans = [];
        foreach ($this->itemsArr as $timeSpan) {
            /* @var $timeSpan TimeSpan */
            $timeSpans[] = $timeSpan->getJsonValue();
        }
        $result = "{\"timeSpans\":[" . implode(',', $timeSpans) . "]}";
        return $result;
    }
}
