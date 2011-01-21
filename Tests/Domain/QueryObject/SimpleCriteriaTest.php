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
 * Testcase for simple criteria
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_QueryObject_SimpleCriteria_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	protected $simpleCriteria = null;
	
	
	public function setup() {
		$this->simpleCriteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('testfield', 'testvalue', '>');
	}
	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
	}
	
	
	
	public function testConstruct() {
		$simpleCriteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('testfield', 'testvalue', '>');
		$this->assertTrue(is_a($simpleCriteria, 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
	}
	
	
	
	public function testGetField() {
		$this->assertTrue($this->simpleCriteria->getField() == 'testfield');
	}
	
	
	
	public function testGetValue() {
		$this->assertTrue($this->simpleCriteria->getValue() == 'testvalue');
	}
	
	
	
	public function testGetOperator() {
		$this->assertTrue($this->simpleCriteria->getOperator() == '>');
	}
	
	
	
	public function testIsEqualTo() {
		$criteriaReference = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 'value', '=');
		$criteriaEqual = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', 'value', '=');
		$criteriaInEqual = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field', 'value', '=');
		$this->assertTrue($criteriaReference->isEqualTo($criteriaEqual));
		$this->assertTrue(!($criteriaReference->isEqualTo($criteriaInEqual)));
	}
	
}
 
?>