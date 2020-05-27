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
use PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria;
use PunktDe\PtExtlist\Utility\DbUtils;
use PunktDe\PtExtlist\Utility\RenderValue;

/**
 * Class implements a filter for settings static values
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter
 */
class StaticValueFilter extends AbstractFilter
{
    /**
     * Holds the current filter value
     *
     * @var string
     */
    protected $filterValue = null;


    /**
     * @param FieldConfig $fieldIdentifier
     * @return SimpleCriteria
     */
    protected function buildFilterCriteria(FieldConfig $fieldIdentifier)
    {
        $fieldName = DbUtils::getSelectPartByFieldConfig($fieldIdentifier);
        $criteria = Criteria::equals($fieldName, $this->filterValue, $fieldIdentifier->getTreatValueAsString());

        return $criteria;
    }


    /**
     * Template method for initializing filter by TS configuration
     */
    protected function initFilterByTsConfig()
    {
        $filterValue = RenderValue::stdWrapIfPlainArray($this->filterConfig->getSettings('filterValue'));
        $this->filterValue = $filterValue;
    }


    /**
     * Template method for initializing filter after setting all data
     */
    protected function initFilter()
    {
        // TODO: Implement initFilter() method.
    }


    /**
     * Sets the active state of this filter
     */
    protected function setActiveState()
    {
        $this->isActive = $this->filterValue ? true : false;
    }


    /**
     * Returns the current filtervalues of this filter
     *
     * @return string
     */
    public function getValue()
    {
        return $this->filterValue;
    }


    /**
     * @param $filterValue
     * @return AbstractSingleValueFilter
     */
    public function setFilterValue($filterValue)
    {
        $this->filterValue = $filterValue;
        return $this;
    }


    /**
     * This filter is not persisted, the value has to be set
     * during the lifecycle
     */
    public function _persistToSession()
    {
    }
    protected function initFilterByGpVars()
    {
    }
    protected function initFilterBySession()
    {
    }
}
