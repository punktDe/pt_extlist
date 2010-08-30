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
 * Testcase for bookmarks controller
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Controller_BookmarksController_testcase  extends Tx_PtExtlist_Tests_BaseTestcase {

	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Controller_BookmarksController'));
	}
	
	
	
	public function testShowAction() {
		$bookmarksCollectionMock = array('test' => 'value');
		$bookmarksRepositoryMock = $this->getMock('Tx_PtExtlist_Domain_Repository_BookmarksRepository', array(), array(), '', FALSE);
		$bookmarksRepositoryMock->expects($this->once())->method('findBookmarksByFeUserAndListIdentifier')->will($this->returnValue($bookmarksCollectionMock));
		
		$feUserMock = $this->getMock('Tx_Extbase_Domain_Model_FrontendUser');
		
		$mockView = $this->getMock(
            'Tx_Fluid_Core_View_TemplateView',
            array('assign'), array(), '', FALSE);
        $mockView->expects($this->once())->method('assign')->with('bookmarks', $bookmarksCollectionMock);
        
        $mockController = $this->getMock(
            $this->buildAccessibleProxy('Tx_PtExtlist_Controller_BookmarksController'),
            array('dummy'),array(), '', FALSE);
        $mockController->_set('view', $mockView);
        $mockController->_set('bookmarksRepository', $bookmarksRepositoryMock);
        $mockController->_set('feUser', $feUserMock);
        
        $mockController->showAction();
	}
	
}
?>