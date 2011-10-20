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
 * TimeSpan Testcase
 *
 * @package pt_extlist
 * @subpackage Tests\Domain\Model\Filter\DataProvider\TimeSpanAlgorithm
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpanTest extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $proxyClass;



	protected $proxy;



	public function setUp() {
		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan');
		$this->proxy = new $this->proxyClass();
	}



	public function tearDown() {
		unset($this->proxyClass);
		unset($this->proxy);
	}



	public function testSetStartDate() {
		$expected = $input = new DateTime("2011/10/31");
		$this->proxy->setStartDate($input);
		$actual = $this->proxy->_get('startDate');
		$this->assertEquals($expected, $actual);
	}



	public function testGetStartDate() {
		$expected = $input = new DateTime("2011/10/31");
		$this->proxy->_set('startDate', $input);
		$actual = $this->proxy->getStartDate();
		$this->assertEquals($expected, $actual);
	}



	public function testSetEndDate() {
		$expected = $input = new DateTime("2011/10/31");
		$this->proxy->setEndDate($input);
		$actual = $this->proxy->_get('endDate');
		$this->assertEquals($expected, $actual);
	}



	public function testGetEndDate() {
		$expected = $input = new DateTime("2011/10/31");
		$this->proxy->_set('endDate', $input);
		$actual = $this->proxy->getEndDate();
		$this->assertEquals($expected, $actual);
	}



	public function testGetSortingValueReturnsStartDate() {
		$input = new DateTime("2011/10/31");
		$expected = $input->format('U');
		$this->proxy->_set('startDate', $input);
		$actual = $this->proxy->getSortingValue();
		$this->assertEquals($expected, $actual);
	}



	public function testGetSortingValueCallsGetStartDateOnce() {
		$input = new DateTime("2011/10/31");
		$proxyMock = $this->getMockBuilder($this->proxyClass)
			->setMethods(array('getStartDate'))
			->getMock();
		$proxyMock->expects($this->once())
			->method('getStartDate')
				->will($this->returnValue($input));
		$proxyMock->getSortingValue();
	}



	public function testGetJsonValue() {
		$inputStartDate = new DateTime("2011/10/31");
		$inputEndDate = new DateTime("2011/11/05");
		$this->proxy->_set('startDate', $inputStartDate);
		$this->proxy->_set('endDate', $inputEndDate);
		$expected = "{\"start\":\"20111031\",\"end\":\"20111105\"}";
		$actual = $this->proxy->getJsonValue();
		$this->assertEquals($expected, $actual);
	}



	public function testGetJsonValueCallsGetStartDateAndGetEndDateOnce() {
		$inputStartDate = new DateTime("2011/10/31");
		$inputEndDate = new DateTime("2011/11/05");
		$proxyMock = $this->getMockBuilder($this->proxyClass)
			->setMethods(array('getStartDate', 'getEndDate'))
			->getMock();
		$proxyMock->expects($this->once())
			->method('getStartDate')
		    ->will($this->returnValue($inputStartDate));
		$proxyMock->expects($this->once())
			->method('getEndDate')
		    ->will($this->returnValue($inputEndDate));
		$proxyMock->getJsonValue();
	}



	public function testToStringReturnsJsonValue() {
		$inputStartDate = new DateTime("2011/10/31");
		$inputEndDate = new DateTime("2011/11/05");
		$this->proxy->_set('startDate', $inputStartDate);
		$this->proxy->_set('endDate', $inputEndDate);
		$expected = "{\"start\":\"20111031\",\"end\":\"20111105\"}";
		$actual = $this->proxy->__toString();
		$this->assertEquals($expected, $actual);
	}

}
