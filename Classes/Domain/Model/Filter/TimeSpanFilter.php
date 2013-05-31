<?php
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

/**
 * Filter for time range
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_TimeSpanFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractTimeSpanFilter {

	/**
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldStart
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldEnd
	 * @return Tx_PtExtlist_Domain_QueryObject_Criteria
	 *
	 * TODO: Optimize this for a 1-field query
	 */
	protected function buildTimeSpanFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldStart, Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldEnd) {

		$fieldStartName = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($fieldStart);
		$fieldEndName = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($fieldEnd);

		$startValueCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::andOp(
										Tx_PtExtlist_Domain_QueryObject_Criteria::lessThanEquals($fieldStartName, $this->getFilterValueStartInDBFormat()),
										Tx_PtExtlist_Domain_QueryObject_Criteria::greaterThanEquals($fieldEndName, $this->getFilterValueStartInDBFormat()));

		$endValueCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::andOp(
										Tx_PtExtlist_Domain_QueryObject_Criteria::lessThanEquals($fieldStartName, $this->getFilterValueEndInDBFormat()),
										Tx_PtExtlist_Domain_QueryObject_Criteria::greaterThanEquals($fieldEndName, $this->getFilterValueEndInDBFormat()));

		$betweenValuesCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::andOp(
										Tx_PtExtlist_Domain_QueryObject_Criteria::greaterThanEquals($fieldStartName, $this->getFilterValueStartInDBFormat()),
										Tx_PtExtlist_Domain_QueryObject_Criteria::lessThanEquals($fieldEndName, $this->getFilterValueEndInDBFormat()));



		$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::orOp(
			Tx_PtExtlist_Domain_QueryObject_Criteria::orOp($startValueCriteria, $endValueCriteria),
			$betweenValuesCriteria
		);
				
		return $criteria;
	}
	
}
?>