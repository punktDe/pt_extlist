<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll,
 *           Christoph Ehscheidt, Joachim Mathes
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
 * Testcase for DatePicker Filter class
 *
 * @package Tests
 * @subpackage Domain\Model\Filter
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DatePickerFilterTest extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $proxyClass;



	protected $proxy;



	public function setUp() {
		$this->initDefaultConfigurationBuilderMock();
		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DatePickerFilter');
		$this->proxy = new $this->proxyClass();
	}



	public function testRemoveQuotationsFromJsonObjectValues() {
		$input = array(
			"changeMonth" => "true",
			"changeYear" => "false",
			"monthNames" => "['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']"
		);
		$expected = "{\"changeMonth\":true,\"changeYear\":false,\"monthNames\":['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']}";
		$actual = $this->proxy->_call('buildJson', $input);
		$this->assertEquals($expected, $actual);
	}

}
 
?>