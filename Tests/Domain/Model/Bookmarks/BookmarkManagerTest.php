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
 * Testcase for bookmark manager
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Model_Bookmarks_BookmarkManager_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager'));
	}
	
	
	
	public function testSingletonInstance() {
		$listIdentifier1 = 'test1';
		$listIdentifier2 = 'test2';
		
		$instance1 = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager::getInstanceByListIdentifier($listIdentifier1);
		$instance2 = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager::getInstanceByListIdentifier($listIdentifier1);
		$instance3 = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager::getInstanceByListIdentifier($listIdentifier2);
		
		$this->assertEquals($instance1, $instance2);
		$this->assertTrue($instance1 != $instance3);
	}
	
	
	
	public function testGetSetCurrentBookmark() {
		$sessionPersistenceManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager');
		
		$bookmarkMock = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark');
		$bookmarkManager = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager::getInstanceByListIdentifier('test');
		$bookmarkManager->injectSessionPersistenceManager($sessionPersistenceManagerMock);
		$bookmarkManager->setCurrentBookmark($bookmarkMock);
		
		$this->assertTrue($bookmarkManager->getCurrentBookmark() === $bookmarkMock);
	}
	
	
	
	public function testAddContentToBookmark() {	
        $bookmark = new Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark();

        $sessionPersistenceManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager',array('getSessionDataByNamespace'), array(), '', FALSE);
        $returnArray = array('test');
		$sessionPersistenceManagerMock->expects($this->once())->method('getSessionDataByNamespace')->with('tx_ptextlist_pi1.test.filters')->will($this->returnValue($returnArray));
        
		$bookmarkManager = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager::getInstanceByListIdentifier('test');
        $bookmarkManager->injectSessionPersistenceManager($sessionPersistenceManagerMock);

        $bookmarkManager->addContentToBookmark($bookmark);
        
        $this->assertEquals(serialize(array('filters' => $returnArray)), $bookmark->getContent());
	}
	
	
	
	public function testInjectSessionPersistenceManager() {
		$sessionPersistenceManagerMock = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager');
		
		$bookmarkManager = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager::getInstanceByListIdentifier('test');
		$bookmarkManager->injectSessionPersistenceManager($sessionPersistenceManagerMock);
	}
	
}

?>