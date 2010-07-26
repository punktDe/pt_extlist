<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * Testcase for mysql query interpreter
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	/**
	 * Query Object
	 *
	 * @var Tx_PtExtlist_Domain_QueryObject_Query
	 */
	protected $queryObject;
	
	public function setup() {
		$this->queryObject = new Tx_PtExtlist_Domain_QueryObject_Query();
		
		// select
		$this->queryObject->addField('name');
		$this->queryObject->addField('age');
		
		// from
		$this->queryObject->addFrom('Users u');
		$this->queryObject->addFrom('Groups g');
		
		// where
		$this->queryObject->addCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria::equals('name', 'Michael'));
		
	}
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter'));
	}
	

	public function testInterpret() {
		
		$interpreter = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter();
		$this->assertTrue(method_exists($interpreter, 'interpretQuery'));
		$result = $interpreter->interpretQuery($this->queryObject);
	}
	
	public function testGetSelectPart() {
		$interpreter = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter');
		$selectPart = $interpreter->getSelectPart($this->queryObject);
	}
	
	
}
?>