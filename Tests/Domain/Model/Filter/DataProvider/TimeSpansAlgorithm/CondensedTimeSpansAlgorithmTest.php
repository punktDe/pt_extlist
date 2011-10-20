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
 * TimeSpansAlgorithm Testcase
 *
 * @package pt_extlist
 * @subpackage Tests\Domain\Model\Filter\DataProvider\TimeSpanAlgorithm
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_TimeSpansAlgorithm_CondensedTimeSpanAlgorithmTest extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $proxyClass;



	protected $proxy;



	public function setUp() {
		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_CondensedTimeSpansAlgorithm');
		$this->proxy = new $this->proxyClass();
	}



	public function testProcess() {
		$timeSpanAlgorithmMock = $this->getAccessibleMock('Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_CondensedTimeSpansAlgorithm', array('dummy'));

		$timeSpan01 = new Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan();
		$timeSpan01->setStartDate(new DateTime('2011/12/24'));
		$timeSpan01->setEndDate(new DateTime('2011/12/31'));

		$timeSpan02 = new Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan();
		$timeSpan02->setStartDate(new DateTime('2011/12/28'));
		$timeSpan02->setEndDate(new DateTime('2012/01/03'));

		$timeSpan03 = new Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan();
		$timeSpan03->setStartDate(new DateTime('2012/03/05'));
		$timeSpan03->setEndDate(new DateTime('2012/03/08'));

		$timeSpan04 = new Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan();
		$timeSpan04->setStartDate(new DateTime('2012/01/02'));
		$timeSpan04->setEndDate(new DateTime('2012/01/02'));

		$timeSpan05 = new Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan();
		$timeSpan05->setStartDate(new DateTime('2012/05/04'));
		$timeSpan05->setEndDate(new DateTime('2012/05/04'));

		$timeSpan06 = new Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan();
		$timeSpan06->setStartDate(new DateTime('2012/05/04'));
		$timeSpan06->setEndDate(new DateTime('2012/05/04'));

		$timeSpans = new Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpanCollection();
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

		$expected = new DateTime('2011/12/24');
		$this->assertEquals($expected->format('U'), $actual[0]->getStartDate()->format('U'));
		$expected = new DateTime('2012/01/03');
		$this->assertEquals($expected->format('U'), $actual[0]->getEndDate()->format('U'));


		$expected = new DateTime('2012/03/05');
		$this->assertEquals($expected->format('U'), $actual[1]->getStartDate()->format('U'));
		$expected = new DateTime('2012/03/08');
		$this->assertEquals($expected->format('U'), $actual[1]->getEndDate()->format('U'));

		$expected = new DateTime('2012/05/04');
		$this->assertEquals($expected->format('U'), $actual[2]->getStartDate()->format('U'));
		$expected = new DateTime('2012/05/04');
		$this->assertEquals($expected->format('U'), $actual[2]->getEndDate()->format('U'));
	}



	public function testSetTimeSpans() {
		$expected = $input = $this->getMockBuilder('Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpanCollection')->getMock();
		$this->proxy->setTimeSpans($input);
		$actual = $this->proxy->_get('timeSpans');
		$this->assertEquals($actual, $expected);
	}



	public function testGetTimeSpans() {
		$expected = $input = $this->getMockBuilder('Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpanCollection')->getMock();
		$this->proxy->_set('timeSpans', $input);
		$actual = $this->proxy->getTimeSpans();
		$this->assertEquals($actual, $expected);
	}



	public function testGetCondensedTimeSpans() {
		$expected = $input = $this->getMockBuilder('Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpanCollection')->getMock();
		$this->proxy->_set('condensedTimeSpans', $input);
		$actual = $this->proxy->getCondensedTimeSpans();
		$this->assertEquals($actual, $expected);
	}

}
