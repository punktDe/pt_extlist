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
    	$pagerMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager', array('setItemCount'));
    	$dataBackendMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend', array('getTotalItemsCount'), array($this->configurationBuilderMock));
    	$dataBackendMock->expects($this->once())
    	   ->method('getTotalItemsCount')
    	   ->will($this->returnValue(10));
    	
    	$mockView = $this->getMock('Tx_Fluid_Core_View_TemplateView', array('assign'));
    	$mockView->expects($this->once())
    	   ->method('assign')
    	   ->with('pager', $this->isInstanceOf('Tx_PtExtlist_Domain_Model_Pager_PagerInterface'));
    	   
    	$configurationBuilderMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder', array('getPagerSettings'), array(), '', FALSE);
    	$configurationBuilderMock->expects($this->once())
    	   ->method('getPagerSettings')
    	   ->will($this->returnValue(array('itemsPerPage' => '10', 'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager')));
    	
    	$pagerControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_PagerController'), array('dummy'), array(), '', FALSE);
    	$pagerControllerMock->_set('dataBackend', $dataBackendMock);
    	$pagerControllerMock->_set('view', $mockView);
    	$pagerControllerMock->_set('configurationBuilder', $configurationBuilderMock);
    	$pagerControllerMock->showAction();
    }
    
}

?>
