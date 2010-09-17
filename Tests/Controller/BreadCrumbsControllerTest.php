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
 * Testcase for 
 *
 * @package Tests
 * @subpackage Controller
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Controller_BreadCrumbsController_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
     
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
	
	public function testSetup() {
		$this->assertTrue(class_exists(Tx_PtExtlist_Controller_BreadCrumbsController));
	}
	
	
	
	public function testShowAction() {
		$this->markTestIncomplete();
		
		// TODO refactor bread crumb controller and breadcrumbs collection factory
		
		/*
		// databackend is required to be found later!
		$dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilderMock);
		print_r('danach');
		$breadcrumbs = Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollectionFactory::getInstanceByConfigurationBuilder(
		    $this->configurationBuilderMock);
		
		$mockView = $this->getMock(
            'Tx_Fluid_Core_View_TemplateView',
            array('assign'), array(), '', FALSE);
        $mockView->expects($this->once())->method('assign')->with('breadcrumbs', $breadcrumbs);
    
		
		$mockController = $this->getMock(
              $this->buildAccessibleProxy('Tx_PtExtlist_Controller_BreadCrumbsController'),
              array('dummy'),array(), '', FALSE);
        $mockController->_set('configurationBuilder', $this->configurationBuilderMock);
        $mockController->_set('view', $mockView);
        
		$mockController->indexAction(); */
	}
	
	
	
	public function testResetFilterAction() {
		$this->markTestIncomplete();
	}
	
}

?>