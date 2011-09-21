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
 * Testcase for simple criteria translator for extbase backend interpreter
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $repositoryMock;
	
	
	
	public function setup() {
		$this->repositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository', array(), array(), '', FALSE); 
	}
	
	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator'));
	}
	
	
	
	public function testThrowExceptionOnWrongCriteriaType() {
		$criteria = new Tx_PtExtlist_Domain_QueryObject_NotCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field', 'value', '='));
		$query = $this->getMock('Tx_Extbase_Persistence_Query', array(), array(), '', FALSE);
        try {
            Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator::translateCriteria(
                $criteria, $query, $this->repositoryMock
            );
        } catch(Exception $e) {
            return;
        }
        $this->fail('No exception has been thrown on trying to translate wrong criteria class!');
	}
	
	
	
	public function testEqualsCriteria() {
		$criteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field', 'value', '=');
		$query = $this->getQueryMockWithMatchingCall(array('matching', 'equals'));
		$query->expects($this->once())
		    ->method('equals')
		    ->with('field', 'value')
		    ->will($this->returnValue($this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE)));
		Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator::translateCriteria(
		    $criteria, $query, $this->repositoryMock);
	}
	
	
	
	public function testLessThanCriteria() {
		$criteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field', 'value', '<');
		$query = $this->getQueryMockWithMatchingCall(array('matching', 'lessThan'));
		$query->expects($this->once())
		    ->method('lessThan')
		    ->with('field', 'value')
            ->will($this->returnValue($this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE)));
		Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator::translateCriteria(
		    $criteria, $query, $this->repositoryMock);
	}
	
	
	
	public function testLessThanEqualCriteria() {
		$criteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field', 'value', '<=');
        $query = $this->getQueryMockWithMatchingCall(array('matching', 'lessThanOrEqual'));
        $query->expects($this->once())
            ->method('lessThanOrEqual')
            ->with('field', 'value')
            ->will($this->returnValue($this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE)));
        Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator::translateCriteria(
            $criteria, $query, $this->repositoryMock);
	}
	
	
	
	public function testGreaterThanCriteria() {
		$criteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field', 'value', '>');
        $query = $this->getQueryMockWithMatchingCall(array('matching', 'greaterThan'));
        $query->expects($this->once())
            ->method('greaterThan')
            ->with('field', 'value')
            ->will($this->returnValue($this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE)));
        Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator::translateCriteria(
            $criteria, $query, $this->repositoryMock);
	}
	
	
	
	public function testGreaterThanEqualCriteria() {
		$criteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field', 'value', '>=');
        $query = $this->getQueryMockWithMatchingCall(array('matching', 'greaterThanOrEqual'));
        $query->expects($this->once())
            ->method('greaterThanOrEqual')
            ->with('field', 'value')
            ->will($this->returnValue($this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE)));
        Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator::translateCriteria(
            $criteria, $query, $this->repositoryMock);
	}
	
	
	
	public function testUseAndForMultipleConstraints() {
		$criteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field', 'value', '=');
		$firstConstraintMock = $this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE);
		$secondConstraintMock = $this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE);
		$andConstraintMock = $this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE);
		$query = $this->getMock('Tx_Extbase_Persistence_Query', array('matching', 'equals', 'getConstraint', 'logicalAnd'), array(), '', FALSE);
        $query->expects($this->once())
          ->method('matching')
          ->with($andConstraintMock);
        $query->expects($this->any())
            ->method('getConstraint')
            ->will($this->returnValue($firstConstraintMock));
		$query->expects($this->once())
            ->method('equals')
            ->with('field', 'value')
            ->will($this->returnValue($secondConstraintMock));
        $query->expects($this->once())
            ->method('logicalAnd')
            ->with($firstConstraintMock, $secondConstraintMock)
            ->will($this->returnValue($andConstraintMock));
        Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_SimpleCriteriaTranslator::translateCriteria(
            $criteria, $query, $this->repositoryMock);
	}
	
	
	
	/************************************************************************************************
	 * Helper methods
	 ************************************************************************************************/
	
	/**
	 * Creates query mock with given methods
	 *
	 * @param array $mockedMethods Methods to be mocked
	 * @return Tx_Extbase_Persistence_Query
	 */
	protected function getQueryMockWithMatchingCall(array $mockedMethods = array()) {
		$query = $this->getMock('Tx_Extbase_Persistence_Query', $mockedMethods, array(), '', FALSE);
        $query->expects($this->any())
          ->method('matching');
        $query->expects($this->any())
            ->method('getConstraint')
            ->will($this->returnValue(null));
        return $query;
	}
	
}
?>