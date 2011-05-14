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
 * Testcase for pager controller class
 *
 * @package Tests
 * @subpackage Controller
 * @author Michael Knoll
 */
class Tx_PtExtlist_Tests_Controller_PagerControllerTestcase extends Tx_PtExtlist_Tests_BaseTestcase {

	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}



	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Controller_PagerController'), 'Class Tx_PtExtlist_Controller_PagerController does not exist!');
	}



	public function testShowAction() {
		 
		$pagerConfiguration = $this->getMock('Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration',array('getTemplatePath'),array(),'', FALSE);
		 
		$pagerMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager', array('getPagerConfiguration'), array(), '', FALSE);
		 

		$mockView = $this->getMock('Tx_Fluid_Core_View_TemplateView', array('assign'));

		$pagerCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_PagerCollection', array('getItemCount','getItemById','hasItem'), array(),'',FALSE);
		$pagerCollectionMock->expects($this->once())
		->method('getItemCount')
		->will($this->returnValue(1));
		$pagerCollectionMock->expects($this->once())
		->method('getItemById')
		->with('default')
		->will($this->returnValue($pagerMock));
		$pagerCollectionMock->expects($this->once())
		->method('hasItem')
		->with('default')
		->will($this->returnValue(true));

		 

		$pagerControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_PagerController'), array('dummy'), array(), '', FALSE);
		$pagerControllerMock->_set('view', $mockView);
		$pagerControllerMock->_set('pagerCollection', $pagerCollectionMock);
		$pagerControllerMock->_set('pagerIdentifier', 'default');
		 
		$pagerControllerMock->showAction();
	}
}
?>