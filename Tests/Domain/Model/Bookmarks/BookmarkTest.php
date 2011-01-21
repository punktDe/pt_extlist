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
 * Testcase for bookmark domain object
 *
 * @package Tests
 * @subpackage Domain\Model\Bookmarks
 * @author Michael Knoll 
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
    
    
    
    public function testGetSetFeGroup() {
        $bookmark = new Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark();
        $feGroupMock = $this->getMock('Tx_Extbase_Domain_Model_FrontendUserGroup');
        $bookmark->setFeGroup($feGroupMock);
        $this->assertEquals($bookmark->getFeGroup(), $feGroupMock); 	
    }

    
    
    public function testGetSetIsPublic() {
    	$bookmark = new Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark();
    	$isPublic = false;
    	$bookmark->setIsPublic($isPublic);
    	$this->assertEquals($bookmark->getIsPublic(), $isPublic);
    }

}
?>