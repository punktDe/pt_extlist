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
 * Testcase for bookmark domain object
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Model_Bookmarks_Bookmark_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
     
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark'));
	}
	
	
	
    public function testGetSetContent() {
        $bookmark = new Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark();
        $bookmark->setContent('test');
        $this->assertEquals($bookmark->getContent(), 'test');
    }
    
    
    
    public function testGetSetCreateDate() {
        $bookmark = new Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark();
        $bookmark->setCreateDate(1234);
        $this->assertEquals($bookmark->getCreateDate(), 1234);
    }
    
    
    
    public function testGetSetDescription() {
        $bookmark = new Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark();
        $bookmark->setDescription('description');
        $this->assertEquals($bookmark->getDescription(), 'description');
    }
    
    
    
    public function testGetSetFeUser() {
        $feUserMock = $this->getMock('Tx_Extbase_Domain_Model_FrontendUser');
        $bookmark = new Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark();
        $bookmark->setFeUser($feUserMock);
        $this->assertEquals($bookmark->getFeUser(), $feUserMock);
    }
    
    

    public function testGetSetListId() {
        $bookmark = new Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark();
        $bookmark->setListId('list_id');
        $this->assertEquals($bookmark->getListId(),'list_id');
    }
    
    
    
    public function testGetSetName() {
        $bookmark = new Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark();
        $bookmark->setName('testname');
        $this->assertEquals($bookmark->getName(), 'testname');
    }
    
}
?>