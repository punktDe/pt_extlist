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
 * TimeSpanAlgorithm Testcase
 *
 * @package pt_extlist
 * @subpackage Tests\Domain\Model\Filter\TimeSpanAlgorithm
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_TimeSpansAlgorithm_CondensedTimeSpanAlgorithmTest extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $proxyClass;



	protected $proxy;



	public function setUp() {
		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_CondensedTimeSpansAlgorithm');
		$this->proxy = new $this->proxyClass();
	}



	public function testProcess() {
		$timeSpanAlgorithmMock = $this->getAccessibleMock('Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_CondensedTimeSpansAlgorithm', array('dummy'));

		$timeSpan01 = new Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpan();
		$timeSpan01->setStartTimestamp(1324681200); // 2011-12-24
		$timeSpan01->setEndTimestamp(1325286000); // 2011-12-31

		$timeSpan02 = new Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpan();
		$timeSpan02->setStartTimestamp(1325026800);	// 2011-12-28
		$timeSpan02->setEndTimestamp(1325545200); // 2012-01-03

		$timeSpan03 = new Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpan();
		$timeSpan03->setStartTimestamp(1330902000); // 2012-03-05
		$timeSpan03->setEndTimestamp(1331161200); // 2012-03-08

		$timeSpan04 = new Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpan();
		$timeSpan04->setStartTimestamp(1325458800);	// 2012-01-02
		$timeSpan04->setEndTimestamp(1325458800); // 2012-01-02

		$timeSpan05 = new Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpan();
		$timeSpan05->setStartTimestamp(1336082400); // 2012-05-04
		$timeSpan05->setEndTimestamp(1336082400); // 2012-05-04

		$timeSpan06 = new Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpan();
		$timeSpan06->setStartTimestamp(1336082400); // 2012-05-04
		$timeSpan06->setEndTimestamp(1336082400); // 2012-05-04

		$timeSpans = new Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpanCollection();
		$timeSpans->push($timeSpan01);
		$timeSpans->push($timeSpan02);
		$timeSpans->push($timeSpan03);
		$timeSpans->push($timeSpan04);
		$timeSpans->push($timeSpan05);
		$timeSpans->push($timeSpan06);

		$timeSpanAlgorithmMock->_set('timeSpans', $timeSpans);
		$actual = $timeSpanAlgorithmMock->process();
		$actualValueInProperty = $timeSpanAlgorithmMock->_get('condensedTimeSpans');

		$this->assertEquals($actual, $actualValueInProperty);

		$this->assertEquals(3, count($actual));

		$this->assertEquals(1324681200, $actual[0]->getStartTimestamp()); // 2011-12-24
		$this->assertEquals(1325545200, $actual[0]->getEndTimestamp()); // 2012-01-03

		$this->assertEquals(1330902000, $actual[1]->getStartTimestamp()); // 2012-03-05
		$this->assertEquals(1331161200, $actual[1]->getEndTimestamp()); // 2012-03-08

		$this->assertEquals(1336082400, $actual[2]->getStartTimestamp()); // 2012-05-04
		$this->assertEquals(1336082400, $actual[2]->getEndTimestamp()); // 2012-05-04
	}



	public function testSetTimeSpans() {
		$expected = $input = $this->getMockBuilder('Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpanCollection')->getMock();
		$this->proxy->setTimeSpans($input);
		$actual = $this->proxy->_get('timeSpans');
		$this->assertEquals($actual, $expected);
	}



	public function testGetStartTimeSpan() {
		$expected = $input = $this->getMockBuilder('Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpanCollection')->getMock();
		$this->proxy->_set('timeSpans', $input);
		$actual = $this->proxy->getTimeSpans();
		$this->assertEquals($actual, $expected);
	}



	public function testGetCondensedTimeSpans() {
		$expected = $input = $this->getMockBuilder('Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpanCollection')->getMock();
		$this->proxy->_set('condensedTimeSpans', $input);
		$actual = $this->proxy->getCondensedTimeSpans();
		$this->assertEquals($actual, $expected);
	}

}
