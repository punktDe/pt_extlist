<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert , Michael Knoll 
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
 * Testcase for subcontroller wrapper
 *
 * @package Tests
 * @subpackage Controller
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Controller_SubcontrollerWrapper_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Controller_SubcontrollerWrapper'));
    }
    

    
    public function setup() {
        parent::setup();
        $this->setupDispatcher();
    }
    
    
    
    public function testInjectRequest() {
        $subcontrollerWrapper = new Tx_PtExtlist_Controller_SubcontrollerWrapper();
        $this->assertTrue(method_exists($subcontrollerWrapper, 'injectRequest'));
        $requestMock = $this->getMock('Tx_Extbase_MVC_Web_Request');
        $subcontrollerWrapper->injectRequest($requestMock);
    }
    
    
    
    public function testInjectSubcontroller() {
    	$subcontrollerWrapper = new Tx_PtExtlist_Controller_SubcontrollerWrapper();
    	$this->assertTrue(method_exists($subcontrollerWrapper, 'injectSubcontroller'));
    	$subcontrollerMock = $this->getMock('Tx_PtExtlist_Controller_ListController', array(), array(), '', FALSE);
    	$subcontrollerWrapper->injectSubcontroller($subcontrollerMock);
    }
    
    
    
    public function testInjectResponse() {
        $subcontrollerWrapper = new Tx_PtExtlist_Controller_SubcontrollerWrapper();
        $this->assertTrue(method_exists($subcontrollerWrapper, 'injectResponse'));
        $responseMock = $this->getMock('Tx_Extbase_MVC_Web_Response');
        $subcontrollerWrapper->injectResponse($responseMock);
    }
    
    
    
    public function testInjectSubcontrollerFactory() {
    	$subcontrollerWrapper = new Tx_PtExtlist_Controller_SubcontrollerWrapper();
    	$this->assertTrue(method_exists($subcontrollerWrapper, 'injectSubcontrollerFactory'));
    	$subcontrollerFactoryMock = $this->getMock('Tx_PtExtlist_Controller_SubcontrollerFactory', array(), array(), '', FALSE);
    	$subcontrollerWrapper->injectSubcontrollerFactory($subcontrollerFactoryMock);
    }
    
    
    
    public function testThrowExceptionOnWrongActionName() {
    	$subcontrollerWrapper = new Tx_PtExtlist_Controller_SubcontrollerWrapper();
    	try {
    		$subcontrollerWrapper->wrongActName();
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No Exception has been thrown when trying to call wrong action method on subcontroller wrapper!');
    }
    
    
    
    public function testThrowExceptionOnNonExistingAction() {
    	$subcontrollerMock = $this->getMock('Tx_PtExtlist_Controller_ListController', array(), array(), '', FALSE);
    	$subcontrollerWrapper = new Tx_PtExtlist_Controller_SubcontrollerWrapper();
    	$subcontrollerWrapper->injectSubcontroller($subcontrollerMock);
    	try {
    		$subcontrollerWrapper->wrongListAction();
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No Exception has been thrown when trying to call non-existing action on list controller!');
    }
    
    
    
    public function testCallActionOnControllerWhenProcessingAction() {
        $subcontrollerWrapper = new Tx_PtExtlist_Controller_SubcontrollerWrapper();
        
        $subcontrollerMock = $this->getMock('Tx_PtExtlist_Controller_ListController', array(), array(), '', FALSE);
        $subcontrollerMock->expects($this->once())->method('processRequest');
        $subcontrollerWrapper->injectSubcontroller($subcontrollerMock);

        $requestMock = $this->getMock('Tx_Extbase_MVC_Web_Request');
        $requestMock->expects($this->at(0))->method('isDispatched')->will($this->returnValue(FALSE));
        $requestMock->expects($this->at(1))->method('isDispatched')->will($this->returnValue(FALSE));
        $requestMock->expects($this->at(2))->method('isDispatched')->will($this->returnValue(TRUE));
        $requestMock->expects($this->any())->method('getControllerActionName')->will($this->returnValue('list'));
        $subcontrollerWrapper->injectRequest($requestMock);
        
        $responseMock = $this->getMock('Tx_Extbase_MVC_Web_Response');
        $subcontrollerWrapper->injectResponse($responseMock);
        
        $subcontrollerWrapper->listAction();
    }	
}

?>