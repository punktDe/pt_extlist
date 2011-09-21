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
 * Testcase for bookmarks repository
 * 
 * TODO this testcase will not run standalone as it requires Extbase Dispatcher to be set up (compare testcase for ExtBase DataBackend!)
 *
 * @package Tests
 * @subpackage Domain\Repository\Bookmarks
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_Repository_Bookmarks_BookmarkRepository_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	public function setup() {
		
	}
	
	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository'));
	}
	
	
	
	public function testFindBookmarksByFeUserAndListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository();
		$this->assertTrue(method_exists($bookmarksRepository, 'findBookmarksByFeUserAndListIdentifier'));
		// TODO think about how to test repository method!
		$this->markTestIncomplete();
	}
	
	
	
	/** @test */
	public function findBookmarksByFeUserAndListIdentifierThrowsExceptionOnEmptyListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository();
		$feUserMock = $this->getMock('Tx_Extbase_Domain_Model_FrontendUser', array(), array(), '', FALSE);
		try {
			$bookmarksRepository->findBookmarksByFeUserAndListIdentifier($feUserMock, '');
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
	
	
	public function testFindPublicBookmarksByListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository();
		$this->assertTrue(method_exists($bookmarksRepository, 'findPublicBookmarksByListIdentifier'));
		// TODO think about how to test this!
		$this->markTestIncomplete();
	}
	
	
	
	public function testThrowExceptionOnFindingPublicBookmarksWithoutListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository();
        try {
            $bookmarksRepository->findPublicBookmarksByListIdentifier('');
        } catch(Exception $e) {
            return;
        }
        $this->fail();
	}
	
	
	
	public function testFindGroupBookmarksByFeGroupAndListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository();
		$this->assertTrue(method_exists($bookmarksRepository, 'findGroupBookmarksByFeGroupAndListIdentifier'));
		// TODO think about how to test this
		$this->markTestIncomplete();
	}
	
	
	
	public function testFindGroupBookmarksByFeUserAndListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository();
		$this->assertTrue(method_exists($bookmarksRepository, 'findGroupBookmarksByFeUserAndListIdentifier'));
		// TODO think about how to test this
		$this->markTestIncomplete();
	}
	
	
	
	public function testFindBookmarksByFeUserGroupIdsAndListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository();
		$this->assertTrue(method_exists($bookmarksRepository, 'findBookmarksByFeUserGroupIdsAndListIdentifier'));
		// TODO think about how to test this
		$this->markTestIncomplete();
	}
	
	
	
	public function testFindNoBookmarksOnDifferingGroupIdsAndFeUserGroupBelongTos() {
		$groupUids = '1,2,3,4';
		
		$userGroup1Mock = $this->getMock('Tx_Extbase_Domain_Model_FrontendUserGroup');
		$userGroup1Mock->expects($this->any())->method('getUid')->will($this->returnValue(5));
		
		$userGroup2Mock = $this->getMock('Tx_Extbase_Domain_Model_FrontendUserGroup');
		$userGroup2Mock->expects($this->any())->method('getUid')->will($this->returnValue(6));
		
		$userGroupObjectStorageMock = new Tx_Extbase_Persistence_ObjectStorage();
		$userGroupObjectStorageMock->attach($userGroup1Mock);
		$userGroupObjectStorageMock->attach($userGroup2Mock);
		
		$feUserMock = $this->getMock('Tx_Extbase_Domain_Model_FrontendUser');
		$feUserMock->expects($this->any())->method('getUsergroups')->will($this->returnValue($userGroupObjectStorageMock));

		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository();
		$this->assertEquals(count($bookmarksRepository->findBookmarksByFeUserGroupIdsAndListIdentifier($feUserMock, $groupUids, 'test')), 0);
	}
	
}
?>