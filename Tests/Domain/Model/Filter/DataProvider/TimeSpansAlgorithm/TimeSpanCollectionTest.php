<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt, Joachim Mathes
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
 * TimeSpanCollection Testcase
 *
 * @package pt_extlist
 * @subpackage Tests\Domain\Model\Filter\DataProvider\TimeSpanAlgorithm
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpanCollectionTest extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $proxyClass;



	protected $proxy;



	public function setUp() {
		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpanCollection');
		$this->proxy = new $this->proxyClass();
	}



	public function tearDown() {
		unset($this->proxyClass);
		unset($this->proxy);
	}



	public function testRestrictedCollectionItemsType() {
		$expected = 'Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan';
		$actual = $this->proxy->_get('restrictedClassName');
		$this->assertEquals($expected, $actual);
	}



	public function testType() {
		$this->assertInstanceOf('Tx_PtExtbase_Collection_SortableObjectCollection', $this->proxy);
	}



	public function testGetJsonValue() {
		$timeSpan01 = $this->getAccessibleMock('Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan');
		$timeSpan01->expects($this->any())
			->method('getJsonValue')
			->will($this->returnValue("{\"start\":\"20111224\",\"end\":\"20111231\"}")); // 2011-12-24 - 2011-12-31

		$timeSpan02 = $this->getAccessibleMock('Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan');
		$timeSpan02->expects($this->any())
			->method('getJsonValue')
			->will($this->returnValue("{\"start\":\"20120504\",\"end\":\"20120504\"}")); // 2012-05-04 - 2012-05-04

		$timeSpans = array($timeSpan01, $timeSpan02);

		$this->proxy->_set('itemsArr', $timeSpans);

		$expected = "{\"timeSpans\":[{\"start\":\"20111224\",\"end\":\"20111231\"},{\"start\":\"20120504\",\"end\":\"20120504\"}]}";
		$actual = $this->proxy->getJsonValue();
		$this->assertEquals($expected, $actual);
	}


}
