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
 * @package Test
 * @subpackage Domain\Model\Filter
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_TimeSpanFilterTest extends Tx_PtExtlist_Tests_BaseTestcase {


	/**
	 *
	 * @returns array
	 */
	public static function fieldsDataProvider() {
		return array(
			
		);
	}


	/**
	 * @test
	 */
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_TimeSpanFilter'));
	}


	/**
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldStart
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldEnd
 	 *	@dataProvider fieldsDataProvider
	 * @return void
	 * @test
	 */
	//public function buildTimeSpanFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldStart, Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldEnd, $resultingQuery) {

	//}

}
?>