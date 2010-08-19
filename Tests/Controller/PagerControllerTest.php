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
 * Testcase for pager controller class
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Controller_PagerControllerTestcase extends Tx_PtExtlist_Tests_BaseTestcase {

	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
	
   public function testSetup() {
   	    $this->assertTrue(class_exists('Tx_PtExtlist_Controller_PagerController'), 'Class Tx_PtExtlist_Controller_PagerController does not exist!');
    }
    
    
    
    public function testShowAction() {
   	
    	$mockView = $this->getMock('Tx_Fluid_Core_View_TemplateView', array('assign'));
    	$mockView->expects($this->once())
    	   ->method('assign')
    	   ->with('pager', $this->isInstanceOf('Tx_PtExtlist_Domain_Model_Pager_PagerInterface'));

    	$pagerMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager', array(), array(), '', FALSE);
    	
    	$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('getItemCount', 'getItemById'), array(),'',FALSE);
    	$pagerCollectionMock->expects($this->once())
    		->method('getItemCount')
    		->will($this->returnValue(1));
    	$pagerCollectionMock->expects($this->once())
    		->method('getItemById')
    		->with('default')
    		->will($this->returnValue($pagerMock));
    	
    	$pagerControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_PagerController'), array('dummy'), array(), '', FALSE);
    	$pagerControllerMock->_set('view', $mockView);
    	$pagerControllerMock->_set('pagerCollection', $pagerCollectionMock);
    	$pagerControllerMock->showAction();
    }
    
    
    
    public function testSubmitAction() {
    	$pagerControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_PagerController'), array('forward'), array(), '', FALSE);
    	$pagerControllerMock->expects($this->once())
    	   ->method('forward')
    	   ->with('show');
    	
    	$pagerControllerMock->_set('listIdentifier','list');
    	
    	$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setCurrentPage'), array(),'',FALSE);
    	$pagerCollectionMock->expects($this->once())
    		->method('setCurrentPage')
    		->with(42);
    		
    	$pagerControllerMock->_set('pagerCollection', $pagerCollectionMock);
    	    		
        $pagerControllerMock->submitAction(42,'list');
    }
    
    public function testSubmitActionWithWrongList() {
    	$pagerControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_PagerController'), array('forward'), array(), '', FALSE);
    	$pagerControllerMock->expects($this->once())
    	   ->method('forward')
    	   ->with('show');
	
    	$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('setCurrentPage'), array(),'',FALSE);
    	$pagerCollectionMock->expects($this->never())
    		->method('setCurrentPage');
    		
    	$pagerControllerMock->_set('listIdentifier','list');
    	$pagerControllerMock->_set('pagerCollection', $pagerCollectionMock);
    	    		
        $pagerControllerMock->submitAction(42,'wronglist');
    }
    
}

?>
