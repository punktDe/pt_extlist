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
 * Testcase for filterbox controller class
 *
 * @package Tests
 * @subpackage Controller
 */
class Tx_PtExtlist_Tests_Controller_FilterboxControllerTestcase extends Tx_PtExtlist_Tests_BaseTestcase {
    
   public function setup() {
      $this->initDefaultConfigurationBuilderMock();
   }
   
   
	
   public function testSetup() {
        $this->assertTrue(class_exists('Tx_PtExtlist_Controller_FilterboxController', 'Class Tx_PtExtlist_Controller_FilterboxController does not exist!'));
    }
    
    
    
    public function testThrowExceptionOnNonExistingFilterboxIdentifier() {
    	try {
	    	$mockController = $this->getMock(
	          $this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'),
	          array('dummy'),array(), '', FALSE);
	        $mockController->injectSettings(array());
	        $this->fail('No exception has been thrown, when no filterbox identifier has been set!');
    	} catch(Exception $e) {
	        return; 
    	}
    }
    
    
    
    public function testShowAction() {
    	$mockFilterbox = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_Filterbox', array(), array(), '', FALSE);
    	
		$mockView = $this->getMock(
    		'Tx_Fluid_Core_View_TemplateView',
	       	array('assign'), array(), '', FALSE);
	    //$mockView->expects($this->once())->method('assign')->with('filterbox', $mockFilterbox);
	
        $mockController = $this->getMock(
            $this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'),
            array('dummy'),array(), '', FALSE);
        $mockController->_set('view', $mockView);
        $mockController->_set('filterbox', $mockFilterbox);
        
        $mockController->showAction();
    }
    
    
    
    public function testSubmitActionWhenValidationFails() {
        $filterboxMock = $this->getMock(Tx_PtExtlist_Domain_Model_Filter_Filterbox, array('validate','getFilterValidationErrors'), array(), '', FALSE);
        $filterboxMock->expects($this->once())->method('validate')->will($this->returnValue(false));
        
     	$filterboxControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'), array('forward'),array(), '', FALSE);
        $filterboxControllerMock->expects($this->once())->method('forward')->with('show');
            
        $viewMock = $this->getMock('Tx_Fluid_Core_View_TemplateView', array('assign'), array(), '', FALSE);
        $viewMock->expects($this->once())->method('assign')->with('filtersDontValidate', true);
        
        $pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection',array('reset'),array(),'', FALSE);
    	$pagerCollectionMock->expects($this->once())->method('reset');
            
        $filterboxControllerMock->_set('view', $viewMock);
        $filterboxControllerMock->_set('pagerCollection', $pagerCollectionMock);
        $filterboxControllerMock->_set('filterbox', $filterboxMock);
            
        $filterboxControllerMock->submitAction();
    }
    
    
    
    public function testSubmitActionWhenValidationSucceeds() {
    	$filterboxMock = $this->getMock(Tx_PtExtlist_Domain_Model_Filter_Filterbox, array('validate','getFilterValidationErrors'), array(), '', FALSE);
        $filterboxMock->expects($this->once())->method('validate')->will($this->returnValue(true));
        
        $filterboxControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'), array('forward'),array(), '', FALSE);
        $filterboxControllerMock->expects($this->once())->method('forward')->with('show');
        
        $pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection',array('reset'),array(),'', FALSE);
        $pagerCollectionMock->expects($this->once())->method('reset');
            
        $filterboxControllerMock->_set('pagerCollection', $pagerCollectionMock);
        $filterboxControllerMock->_set('filterbox', $filterboxMock);
            
        $filterboxControllerMock->submitAction();
    }
    
    
    
    public function testResetAction() {
    	$filterboxMock = $this->getMock(Tx_PtExtlist_Domain_Model_Filter_Filterbox, array('reset'), array(), '', FALSE);
    	$filterboxMock->expects($this->once())->method('reset');
    	   
    	$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection',array('reset'),array(),'', FALSE);
    	$pagerCollectionMock->expects($this->once())->method('reset');
    	
    	$filterboxControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'), array('forward','getFilterboxForControllerSettings'), array(), '', FALSE);
    	$filterboxControllerMock->_set('filterboxIdentifier', 'test');
    	$filterboxControllerMock->_set('filterbox', $filterboxMock);
    	$filterboxControllerMock->_set('pagerCollection', $pagerCollectionMock);
    	$filterboxControllerMock->expects($this->once())->method('forward')->with('show');
    	   
    	$filterboxControllerMock->resetAction();
    }
    
}

?>
