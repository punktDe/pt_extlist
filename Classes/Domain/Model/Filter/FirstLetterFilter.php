<?php
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
 * Class implements the firstLetter filter
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_FirstLetterFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter {	

	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter::buildFilterCriteria()
	 */
	protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {
		
		$criteria = NULL;
		$columnName = $fieldIdentifier->getTableFieldCombined();
		$filterValues = array_filter($this->filterValues);
		
		if(count($filterValues)) {
			$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::like($columnName, current($filterValues).'%');
		}
		
		return $criteria;
	}
}