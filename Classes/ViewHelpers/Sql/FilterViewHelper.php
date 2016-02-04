<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2014 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 *
 * example: {extlist:Sql.Filter(filter:filters.filterbox.roleFilter,filterField:'compcheck.role_uid')}
 *
 * @author Daniel Lienert
 * @package ViewHelpers
 */
class Tx_PtExtlist_ViewHelpers_Sql_FilterViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @param Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter
     * @param string $filterField
     * @param string $notActiveQuery
     *
     * @return string
     */
    public function render(Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter, $filterField = '', $notActiveQuery = '1=1')
    {
        if (!$filter->isActive()) {
            return $notActiveQuery;
        }

        if ($filter instanceof Tx_PtExtlist_Domain_Model_Filter_DateRangeFilter) {
            $calculatedTimestampBoundaries = $filter->getCalculatedTimestampBoundaries();

            if (is_array($filterField)) {
                return sprintf('%s >= %s AND %s <= %s', $filterField[0], $calculatedTimestampBoundaries['filterValueFromTimestamp'], $filterField[1], $calculatedTimestampBoundaries['filterValueToTimestamp']);
            } else {
                return sprintf('%s >= %s AND %1$s <= %s', $filterField, $calculatedTimestampBoundaries['filterValueFromTimestamp'], $calculatedTimestampBoundaries['filterValueToTimestamp']);
            }
        }

        $filterValue = $filter->getValue();
        $filterField = $filterField ? $filterField : Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfigCollection($filter->getFilterConfig()->getFieldIdentifier());

        if (is_array($filterValue)) {
            return sprintf('%s in (%s)', $filterField, implode(', ', $filterValue));
        } else {
            return sprintf('%s = %s', $filterField, $filterValue);
        }
    }
}
