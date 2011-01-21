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
 * Testcase for criteria
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_QueryObject_Criteria_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	 
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_QueryObject_Criteria'));
	}

	
	
	public function testGreaterThan() {
		$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::greaterThan('test', 5);
		$this->assertTrue(is_a($criteria, 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
	}
	
	
	
	public function testGreaterThanEquals() {
		$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::greaterThanEquals('test', 5);
		$this->assertTrue(is_a($criteria, 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
	}
	
	
	
	public function testLessThan() {
		$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::lessThan('test', 5);
		$this->assertTrue(is_a($criteria, 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
	}
	
	
	
	public function testLessThanEquals() {
		$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::lessThanEquals('test', 5);
		$this->assertTrue(is_a($criteria, 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
	}
	
	
	
	public function testLike() {
		$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::like('test', 'test');
		$this->assertTrue(is_a($criteria, 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
	}
	
	
	
	public function testEquals() {
		$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::equals('test', 'test');
		$this->assertTrue(is_a($criteria, 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
	}
	
	
	
	public function testNot() {
		$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::lessThan('test', 10);
		$notCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::notOp($criteria);
		$this->assertTrue(is_a($notCriteria, 'Tx_PtExtlist_Domain_QueryObject_NotCriteria'));
	}
	
	
	
	public function testAnd() {
		$criteria1 = Tx_PtExtlist_Domain_QueryObject_Criteria::lessThan('test', 1);
		$criteria2 = Tx_PtExtlist_Domain_QueryObject_Criteria::greaterThan('test', 0);
		$andCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::andOp($criteria1, $criteria2);
		$this->assertTrue(is_a($andCriteria, 'Tx_PtExtlist_Domain_QueryObject_AndCriteria'));
	}
	
	
	
	public function testOr() {
		$criteria1 = Tx_PtExtlist_Domain_QueryObject_Criteria::lessThan('test', 1);
        $criteria2 = Tx_PtExtlist_Domain_QueryObject_Criteria::greaterThan('test', 0);
        $orCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::orOp($criteria1, $criteria2);
        $this->assertTrue(is_a($orCriteria, 'Tx_PtExtlist_Domain_QueryObject_OrCriteria')); 
	}
	
	
	
	public function testIn() {
		$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::in('test', 'test,test1,test2');
        $this->assertTrue(is_a($criteria, 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
	}
	
}
 
 
?>