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
 * Testcase for bookmarks repository
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Repository_BookmarksRepository_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	public function testSetup() {
		$this->assertTrue(class_exists(Tx_PtExtlist_Domain_Repository_BookmarksRepository));
	}
	
	
	
	public function testFindBookmarksByFeUserAndListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_BookmarksRepository();
		$this->assertTrue(method_exists($bookmarksRepository, 'findBookmarksByFeUserAndListIdentifier'));
		// TODO think about how to test repository method!
		$this->markTestIncomplete();
	}
	
	
	
	public function testThrowExceptionOnFindBookmarksByFeUserAndListIdentifierWithEmptyListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_BookmarksRepository();
		try {
			$bookmarksRepository->findBookmarksByFeUserAndListIdentifier(null, '');
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
	
	
	public function testFindPublicBookmarksByListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_BookmarksRepository();
		$this->assertTrue(method_exists($bookmarksRepository, 'findPublicBookmarksByListIdentifier'));
		// TODO think about how to test this!
		$this->markTestIncomplete();
	}
	
	
	
	public function testThrowExceptionOnFindingPublicBookmarksWithoutListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_BookmarksRepository();
        try {
            $bookmarksRepository->findPublicBookmarksByListIdentifier('');
        } catch(Exception $e) {
            return;
        }
        $this->fail();
	}
	
	
	
	public function testFindGroupBookmarksByFeGroupAndListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_BookmarksRepository();
		$this->assertTrue(method_exists($bookmarksRepository, 'findGroupBookmarksByFeGroupAndListIdentifier'));
		// TODO think about how to test this
		$this->markTestIncomplete();
	}
	
	
	
	public function testFindGroupBookmarksByFeUserAndListIdentifier() {
		$bookmarksRepository = new Tx_PtExtlist_Domain_Repository_BookmarksRepository();
		$this->assertTrue(method_exists($bookmarksRepository, 'findGroupBookmarksByFeUserAndListIdentifier'));
		// TODO think about how to test this
		$this->markTestIncomplete();
	}
	
}
?>