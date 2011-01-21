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
 * Testcase for mysql query interpreter
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll 
 * @author Daniel Lienert 
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
		
		// group by
		$this->queryObject->addGroupBy('name');
		$this->queryObject->addGroupBy('company');
		
		// limit
		$this->queryObject->setLimit('10:10');
		
		// sortings
		$this->queryObject->addSorting('test');
		$this->queryObject->addSorting('test2', Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC);
		
	}
	
	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter'));
	}
	
	
	
	public function testGetLimit() {
		$this->assertTrue(Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter::getLimit($this->queryObject) == '10,10');
	}
	
	
	
	public function testGroupByClause() {
		$this->assertTrue(Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter::getGroupBy($this->queryObject) == 'name, company');
	}
	
	
	
	public function testGetSortings() {
		$sorting = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter::getSorting($this->queryObject);
		$this->assertTrue($sorting == 'test ASC, test2 DESC', $sorting);
	}
	
	
	
	public function testInterpretQuery() {
		$this->markTestIncomplete();
	}
	
	
}
?>