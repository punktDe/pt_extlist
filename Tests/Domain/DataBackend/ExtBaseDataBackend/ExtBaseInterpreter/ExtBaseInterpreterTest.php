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
 * Testcase for ExtBase Query interpreter
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter'));
    }

    
    
    public function testInterpretQueryByRepository() {
    	$criteriaArray = array(
            new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field1', 'value1', '='),
            new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field2', 'value2', '>')
        );
        $queryObjectMock = $this->getMock('Tx_PtExtlist_Domain_QueryObject_Query', array(), array(), '', FALSE);
        $queryObjectMock->expects($this->any())->method('getLimit')->will($this->returnValue('20:10'));
        $queryObjectMock->expects($this->any())->method('getCriterias')->will($this->returnValue($criteriaArray));
        
        $extbaseQueryMock = $this->getMock('Tx_Extbase_Persistence_Query', array(), array(), '', FALSE);
        $extbaseQueryMock->expects($this->any())->method('matching');
        $extbaseQueryMock->expects($this->any())->method('getConstraint')->will($this->returnValue(null));
        $extbaseQueryMock->expects($this->once())->method('equals')->with('field1', 'value1')
            ->will($this->returnValue($this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE)));
        $extbaseQueryMock->expects($this->once())->method('greaterThan')->with('field2', 'value2')
            ->will($this->returnValue($this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE)));
        $repositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository', array(), array(), '', FALSE);
        $repositoryMock->expects($this->once())->method('createQuery')->will($this->returnValue($extbaseQueryMock));
        
        $translatedQuery = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter::
            interpretQueryByRepository($queryObjectMock, $repositoryMock);
    }

    
    
    public function testTranslateCriteria() {
        $this->assertTrue(method_exists(Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter, 'translateCriteria'));    
    }
    
    
    
    public function testSetLimitAndOffsetOnExtBaseQueryByQueryObject() {
    	$repositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository',  array(), array(), '', FALSE);
        $queryObjectMock = $this->getMock('Tx_PtExtlist_Domain_QueryObject_Query', array(), array(), '', FALSE);
    	$queryObjectMock->expects($this->any())
    	   ->method('getLimit')
    	   ->will($this->returnValue('20:10'));
    	$extbaseQueryMock = $this->getMock('Tx_Extbase_Persistence_Query', array(), array(), '', FALSE);
    	$extbaseQueryMock->expects($this->once())
    	   ->method('setOffset')
    	   ->with(intval('20'));
   	   $extbaseQueryMock->expects($this->once())
           ->method('setLimit')
           ->with(intval('10'));
       Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter::setLimitOnExtBaseQueryByQueryObject($queryObjectMock, $extbaseQueryMock, $repositoryMock);
    }
    
    
    
    public function testSetSortingOnExtBaseQueryByQueryObject() {
    	$repositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository',  array(), array(), '', FALSE);
    	$queryObjectMock = $this->getMock('Tx_PtExtlist_Domain_QueryObject_Query', array(), array(), '', FALSE);
    	$queryObjectMock->expects($this->any())
            ->method('getSortings')
            ->will($this->returnValue(
                array(
                    'fieldName1' => Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC,
                    'fieldName2' => Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC
                )
            )
        );
        $extbaseQueryMock = new Tx_Extbase_Persistence_Query();#$this->getMock('Tx_Extbase_Persistence_Query', array(), array(), '', FALSE);
        Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter::setSortingOnExtBaseQueryByQueryObject($queryObjectMock, $extbaseQueryMock, $repositoryMock);
        $extBaseOrderings = $extbaseQueryMock->getOrderings();
        $this->assertEquals($extbaseQueryMock->getOrderings(),array('fieldName1' => Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING, 'fieldName2' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING));
    }
    
    
    
    public function testSetLimitOnExtBaseQueryByQueryObject() {
    	$repositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository',  array(), array(), '', FALSE);
        $queryObjectMock = $this->getMock('Tx_PtExtlist_Domain_QueryObject_Query', array(), array(), '', FALSE);
        $queryObjectMock->expects($this->any())
           ->method('getLimit')
           ->will($this->returnValue('20'));
        $extbaseQueryMock = $this->getMock('Tx_Extbase_Persistence_Query', array(), array(), '', FALSE);
        $extbaseQueryMock->expects($this->once())
           ->method('setLimit')
           ->with(intval('20'));
       Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter::setLimitOnExtBaseQueryByQueryObject($queryObjectMock, $extbaseQueryMock, $repositoryMock);
    }
    
    
    
    public function testSetCriteriaOnExtBaseQueryByCriteria() {
    	$repositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository',  array(), array(), '', FALSE);
    	$criteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field', 'value', '=');
    	$extbaseQueryMock = $this->getMock('Tx_Extbase_Persistence_Query', array(), array(), '', FALSE);
    	$extbaseQueryMock->expects($this->any())
    	    ->method('getConstraint')
    	    ->will($this->returnValue(null));
        $extbaseQueryMock->expects($this->once())
            ->method('matching');
        $extbaseQueryMock->expects($this->once())
            ->method('equals')
            ->with('field', 'value')
            ->will($this->returnValue($this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE)));
        Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter::setCriteriaOnExtBaseQueryByCriteria($criteria, $extbaseQueryMock, $repositoryMock);
    }

    
    
    public function testSetAllCriteriasOnExtBaseQueryByQuery() {
    	$repositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository',  array(), array(), '', FALSE);
        $criteria1 = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field1', 'value1', '=');
        $criteria2 = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field2', 'value2', '>');
        $queryMock = $this->getMock('Tx_PtExtlist_Domain_QueryObject_Query', array(), array(), '', FALSE);
        $queryMock->expects($this->once())
            ->method('getCriterias')
            ->will($this->returnValue(array($criteria1, $criteria2)));
        $extbaseQueryMock = $this->getMock('Tx_Extbase_Persistence_Query', array(), array(), '', FALSE);
        $extbaseQueryMock->expects($this->any())
            ->method('matching');
        $extbaseQueryMock->expects($this->once())
            ->method('equals')
            ->with('field1', 'value1')
            ->will($this->returnValue($this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE)));
        $extbaseQueryMock->expects($this->once())
            ->method('greaterThan')
            ->with('field2', 'value2')
            ->will($this->returnValue($this->getMock('Tx_Extbase_Persistence_QOM_Constraint', array(), array(), '', FALSE)));
        $extbaseQueryMock->expects($this->any())
            ->method('getConstraint')
            ->will($this->returnValue(null));
        Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter::setAllCriteriasOnExtBaseQueryByQueryObject($queryMock, $extbaseQueryMock, $repositoryMock);
    }
    
}

?>