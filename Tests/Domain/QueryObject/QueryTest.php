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
 * Testcase for query
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_QueryObject_Query_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
     
    public function testSetup() {
        $this->assertTrue(class_exists('Tx_PtExtlist_Domain_QueryObject_Query'));
    }
    
    
    
    public function testAddCriteria() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$this->assertTrue(method_exists($query, 'addCriteria'));
    }
    
    
    
    public function testGetCriteria() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
        $query->addCriteria(Tx_PtExtlist_Domain_QueryObject_Criteria::lessThan('test', 10));
        $this->assertTrue(count($query->getCriterias()) == 1);
        $criterias = $query->getCriterias();
        $this->assertTrue($criterias[0]->getField() == 'test');
        $this->assertTrue($criterias[0]->getValue() == 10);
        $this->assertTrue($criterias[0]->getOperator() == '<');
    }
    
    
    
    public function testAddField() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$this->assertTrue(method_exists($query, 'addField'));
    }
    
    
    
    public function testAddGroupBy() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$this->assertTrue(method_exists($query, 'addGroupBy'));
    }
    
    
	public function testGetGroupBy() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$query->addGroupBy('test1');
    	$query->addGroupBy('test2');
    	$this->assertTrue(method_exists($query, 'getGroupBy'));
    	$groupByfields = $query->getGroupBy();
    	$this->assertTrue(in_array('test1', $groupByfields));
    	$this->assertTrue(in_array('test2', $groupByfields));
    }
    
    
    
    public function testGetField() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$query->addField('test');
    	$this->assertTrue(method_exists($query, 'getFields'));
    	$fields = $query->getFields();
    	$this->assertTrue(in_array('test', $fields));
    }
    
    
    
    public function testAddFrom() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$this->assertTrue(method_exists($query, 'addFrom'));
    }
    
    
    
    public function testGetFrom() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$query->addFrom('test');
    	$this->assertTrue(method_exists($query, 'getFrom'));
    	$froms = $query->getFrom();
    	$this->assertTrue(in_array('test', $froms));
    }
    
    
    
    public function testGetSetLimit() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$this->assertTrue(method_exists($query, 'setLimit'));
    	$this->assertTrue(method_exists($query, 'getLimit'));
    }
    
    
    
    public function testSetAndGetCorrectLimitFormat() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$query->setLimit(10);
    	$this->assertTrue($query->getLimit() == 10);
    	$query->setLimit('10:10');
    	$this->assertTrue($query->getLimit() == '10:10');
    }
    
    
    
    public function testThrowExceptionOnWrongLimitFormat() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	try {
    		$query->setLimit('10/10');
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No error thrown on wrong limit format!');
    }
    
    
    
    public function testSetSorting() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$this->assertTrue(method_exists($query, 'addSorting'));
    	$query->addSorting('test', Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC);
    	
    	$sorting = $query->getSortings();
    	$this->assertTrue(array_key_exists('test', $sorting));
    	$this->assertEquals($sorting['test'], Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC);
    }
    
    public function testAddSdortingArray() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$this->assertTrue(method_exists($query, 'addSortingArray'));
    	
    	$query->addSorting('name', Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
    	$query->addSortingArray(array('test' => Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC));
    	$query->addSorting('company', Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
    	
    	$sorting = $query->getSortings();
    	$this->assertTrue(array_key_exists('test', $sorting));
    	$this->assertEquals($sorting['test'], Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC);
    	$this->assertEquals($sorting['name'], Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
    	$this->assertEquals($sorting['company'], Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
    }
    
    
    public function testRaiseErrorOnAddingWrongSorting() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	try {
    		$query->addSorting('');
    	} catch(Exception $e) {
    		try {
    		    $query->addSorting('test', 'LKJL');
    		} catch(Exception $e) {
    			return;
    		}
    		$this->fail('No error thrown on wrong sorting direction!');
    	}
    	$this->fail('No error thrown on empty sorting column!');
    }
    
    
    
    public function testGetSortings() {
    	$query = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$query->addSorting('test1', Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
    	$query->addSorting('test2', Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC);
    	$sortings = $query->getSortings();
    	
    	$this->assertTrue($sortings['test1'] == Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
    	$this->assertTrue($sortings['test2'] == Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC);
    }
    
    
}
 
 
?>