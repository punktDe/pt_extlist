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
 * Testcase for 
 *
 * @package Tests
 * @subpackage Controller
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Controller_BreadCrumbsController_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
     
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
	
	public function testSetup() {
		$this->assertTrue(class_exists(Tx_PtExtlist_Controller_BreadCrumbsController));
	}
	
	
	
	public function testIndexAction() {
		$this->markTestIncomplete();
		
		$breadCrumb = $this->getMock('Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb', array(), array(), '', FALSE);

		$filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter');
		$filterMock->expects($this->any())->method('getFilterBreadCrumb')->will($this->returnValue($breadCrumb));
		
		$filterbox = new Tx_PtExtlist_Domain_Model_Filter_Filterbox();
		$filterbox->addFilter($filterMock);
		
		$filterboxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection();
		$filterboxCollection->addFilterBox($filterbox);
	
		$breadCrumbCollection = new Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollection();
		
		$mockView = $this->getMock(
            'Tx_Fluid_Core_View_TemplateView',
            array('assign'), array(), '', FALSE);
        $mockView->expects($this->once())->method('assign')->with('breadcrumbs', $breadCrumbCollection);
    
        
        $mockController = $this->getMock(
              $this->buildAccessibleProxy('Tx_PtExtlist_Controller_BreadCrumbsController'),
              array('dummy'),array(), '', FALSE);
        $mockController->_set('configurationBuilder', $this->configurationBuilderMock);
        $mockController->_set('filterboxCollection', $filterboxCollection);
        $mockController->_set('view', $mockView);
        
        $mockController->indexAction();
	}
	
	
	
	public function testResetFilterAction() {
		$this->markTestIncomplete();
		$breadCrumb = $this->getMock('Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb', array(), array(), '', FALSE);
        
        $filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter');
        $filterMock->expects($this->any())->method('getFilterBreadCrumb')->will($this->returnValue($breadCrumb));
        
        $filterbox = new Tx_PtExtlist_Domain_Model_Filter_Filterbox();
        $filterbox->addFilter($filterMock, 'test');
        
        $filterboxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection();
        $filterboxCollection->addFilterBox($filterbox, 'test');
		
		$mockController = $this->getMock(
              $this->buildAccessibleProxy('Tx_PtExtlist_Controller_BreadCrumbsController'),
              array('forward'),array(), '', FALSE);
        $mockController->expects($this->once())->method('forward')->with('index');
        $mockController->_set('configurationBuilder', $this->configurationBuilderMock);
        $mockController->_set('filterboxCollection', $filterboxCollection);
        
        $mockController->resetFilterAction();
	}
	
}

?>