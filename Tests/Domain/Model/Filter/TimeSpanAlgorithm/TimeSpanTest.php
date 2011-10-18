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
 * @subpackage Tests\Domain\Model\Filter\TimeSpanAlgorithm
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpanTest extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $proxyClass;



	protected $proxy;



	public function setUp() {
		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpan');
		$this->proxy = new $this->proxyClass();
	}



	public function tearDown() {
		unset($this->proxyClass);
		unset($this->proxy);
	}



	public function testSetStartTimeSpan() {
		$expected = $input = 1324681200;
		$this->proxy->setStartTimestamp($input);
		$actual = $this->proxy->_get('startTimestamp');
		$this->assertEquals($actual, $expected);
	}



	public function testGetStartTimeSpan() {
		$expected = $input = 1324681200;
		$this->proxy->_set('startTimestamp', $input);
		$actual = $this->proxy->getStartTimestamp();
		$this->assertEquals($actual, $expected);
	}



	public function testSetEndTimeSpan() {
		$expected = $input = 1324681200;
		$this->proxy->setEndTimestamp($input);
		$actual = $this->proxy->_get('endTimestamp');
		$this->assertEquals($actual, $expected);
	}



	public function testGetEndTimeSpan() {
		$expected = $input = 1324681200;
		$this->proxy->_set('endTimestamp', $input);
		$actual = $this->proxy->getEndTimestamp();
		$this->assertEquals($actual, $expected);
	}



	public function testGetSortingValueReturnsStartTimestamp() {
		$expected = $input = 1324681200;
		$this->proxy->_set('startTimestamp', $input);
		$actual = $this->proxy->getSortingValue();
		$this->assertEquals($actual, $expected);
	}



	public function testGetJsonValue() {
		$inputStart = 1324681200;
		$inputEnd = 1324681200;
		$expected = "{\"start\":1324681200,\"end\":1324681200}";
		$this->proxy->_set('startTimestamp', $inputStart);
		$this->proxy->_set('endTimestamp', $inputEnd);
		$actual = $this->proxy->getJsonValue();
		//$actual = print_r($this->proxy, TRUE);
		$this->assertEquals($actual, $expected);
	}



	public function testToStringReturnsJsonValue() {
		$inputStart = 1324681200;
		$inputEnd = 1324681200;
		$expected = "{\"start\":1324681200,\"end\":1324681200}";
		$this->proxy->_set('startTimestamp', $inputStart);
		$this->proxy->_set('endTimestamp', $inputEnd);
		$actual = $this->proxy->__toString();
		$this->assertEquals($actual, $expected);
	}

}
