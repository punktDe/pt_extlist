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
 * Testcase for ExtBase Query interpreter
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter'));
    }

    
    
    public function testInterpretQueryByRepository() {
    	$this->markTestIncomplete();
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
        $extbaseQueryMock->expects($this->once())
            ->method('matching');
        $extbaseQueryMock->expects($this->once())
            ->method('equals')
            ->with('field', 'value');
        Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter::setCriteriaOnExtBaseQueryByCriteria($criteria, $extbaseQueryMock, $repositoryMock);
        
    }
    
    
    
}

?>