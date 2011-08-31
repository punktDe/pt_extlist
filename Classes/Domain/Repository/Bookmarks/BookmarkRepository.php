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
 * Class implements bookmarks repository
 *
 * @package Domain
 * @subpackage Repository\Bookmarks
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository extends Tx_Extbase_Persistence_Repository {
	
	/**
	 * Holds PID of folder for bookmarks. This can be set via settings.bookmarks.bookmarksPid
	 *
	 * @var int
	 */
	protected $bookmarksStoragePid = 0;

	
	
	/**
	 * Overwrites createQuery method to overwrite storage pid
	 */
	public function createQuery() {
	   $query = parent::createQuery();
	   if ($this->bookmarksStoragePid > 0)	{
	       $query->getQuerySettings()->setRespectStoragePage(FALSE);
	   }
	   return $query;
    }
	
    
    
    /**
     * Setter for storage pid for bookmarks
     *
     * @param int $bookmarksStoragePid
     */
    public function setBookmarksStoragePid($bookmarksStoragePid) {
    	$this->bookmarksStoragePid = $bookmarksStoragePid;
    }
    
	
	
    /**
     * Returns collection of bookmarks for given feUser and list identifier
     *
     * @param Tx_Extbase_Domain_Model_FrontendUser $feUser
     * @param string $listIdentifier
     * @return Tx_Extbase_Persistence_ObjectStorage<Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark>
     */	
	public function findBookmarksByFeUserAndListIdentifier(Tx_Extbase_Domain_Model_FrontendUser $feUser, $listIdentifier) {
		$feUserUid = $feUser->getUid();
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1283117065'));
		if ($feUserUid > 0) {
			$query = $this->createQuery();
			$query->matching($query->logicalAnd($query->equals('feUser', $feUserUid), $query->equals('listId', $listIdentifier)));
			$result = $query->execute();
			return $result;
		}
		else {
		    return null;
		}
	}
	
	
	
	/**
	 * Returns collection of PUBLIC bookmarks for given list identifier
	 *
	 * @param string $listIdentifier
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark>
	 */
	public function findPublicBookmarksByListIdentifier($listIdentifier) {
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1283117066'));
		$query = $this->createQuery();
		$query->matching($query->logicalAnd(
		    $query->logicalAnd($query->equals('listId', $listIdentifier), $query->equals('isPublic', 1)), 
		    $query->equals('pid', $this->bookmarksStoragePid)));
		$result = $query->execute();
		return $result;
	}
	
	
	
	/**
	 * Returns collection of bookmarks for fe groups for all fe groups given user belongs to and a given list identifier
	 *
	 * @param Tx_Extbase_Domain_Model_FrontendUser $feUser
	 * @param string $listIdentifier
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark>
	 */
	public function findGroupBookmarksByFeUserAndListIdentifier(Tx_Extbase_Domain_Model_FrontendUser $feUser, $listIdentifier) {
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1283117068'));
		$groupBookmarks = new Tx_Extbase_Persistence_ObjectStorage();
		$feUserGroups = $feUser->getUsergroups();
		foreach($feUserGroups as $feUserGroup) { /* @var $feUserGroup Tx_Extbase_Domain_Model_FrontendUserGroup */
			$groupBookmarks->addAll($this->findGroupBookmarksByFeGroupAndListIdentifier($feUserGroup, $listIdentifier));
		}
		return $groupBookmarks;
	}
	
	
	
	/**
	 * Returns collection of bookmarks for fe groups for a given fe group and list identifier
	 *
	 * @param Tx_Extbase_Domain_Model_FrontendUserGroup $feGroup
	 * @param string $listIdentifier
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark>
	 */
	public function findGroupBookmarksByFeGroupAndListIdentifier(Tx_Extbase_Domain_Model_FrontendUserGroup $feGroup, $listIdentifier) {
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1283117067'));
		$query = $this->createQuery();
		$query->matching($query->logicalAnd($query->equals('feGroup', $feGroup->getUid()), $query->equals('listId', $listIdentifier)));
		return $query->execute();
	}
	
	
	
	/**
	 * Returns all group bookmarks for a given user and a given list of group uids for which bookmarks should be collected for a given list identifier.
	 * 
	 * Example: 
	 *     User is in Groups (1,2,3,4)
	 *     Groups to be shown (3,4)
	 *     ==> all bookmarks for groups 3,4 are returned
	 *
	 * @param Tx_Extbase_Domain_Model_FrontendUser $feUser
	 * @param unknown_type $groupIds
	 * @param unknown_type $listIdentifier
	 * @return unknown
	 */
	public function findBookmarksByFeUserGroupIdsAndListIdentifier(Tx_Extbase_Domain_Model_FrontendUser $feUser, $groupIds, $listIdentifier) {
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1283117069'));
		if (!is_array($groupIds)) {
			$groupIds = explode(',', $groupIds);
		}
		
		$groupBookmarks = new Tx_Extbase_Persistence_ObjectStorage();
		$feUserGroups = $feUser->getUsergroups();
        foreach($feUserGroups as $feUserGroup) { /* @var $feUserGroup Tx_Extbase_Domain_Model_FrontendUserGroup */
            if (in_array($feUserGroup->getUid(), $groupIds)) {
            	$bookmarks = $this->findGroupBookmarksByFeGroupAndListIdentifier($feUserGroup, $listIdentifier);
            	foreach($bookmarks as $bookmark) {
            	    $groupBookmarks->attach($bookmark);
            	}
            }
        }
        
        return $groupBookmarks;
	}
	
}
 
?>