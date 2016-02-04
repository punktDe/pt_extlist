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
 * @see Tx_PtExtlist_Tests_Domain_Repository_Bookmarks_BookmarkRepositoryTest
 */
class Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Holds PID of folder for bookmarks. This can be set via settings.bookmarks.bookmarksPid
     *
     * @var integer
     */
    protected $bookmarkStoragePid = 0;

    
    
    /**
     * Overwrites createQuery method to overwrite storage pid
     */
    public function createQuery()
    {
        $query = parent::createQuery();
        if ($this->bookmarkStoragePid > 0) {
            $query->getQuerySettings()->setRespectStoragePage(false);
        }
        return $query;
    }
    
    
    
    /**
     * Setter for storage pid for bookmarks
     *
     * @param integer $bookmarkStoragePid
     */
    public function setBookmarkStoragePid($bookmarkStoragePid)
    {
        $this->bookmarkStoragePid = $bookmarkStoragePid;
    }
    
    
    
    /**
     * Returns collection of private bookmarks for given feUser and list identifier
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $feUser
     * @param string $listIdentifier
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_PtExtlist_Domain_Model_Bookmark_Bookmark>
     */
    public function findPrivateBookmarksByFeUserAndListIdentifier(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $feUser, $listIdentifier)
    {
        $feUserUid = $feUser->getUid();
        Tx_PtExtbase_Assertions_Assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1283117065'));
        if ($feUserUid > 0) {
            $query = $this->createQuery();
            $query->setOrderings(array('name' => 'ASC'));
            $query->matching($query->logicalAnd(
                $query->logicalAnd(
                    $query->equals('feUser', $feUserUid), $query->equals('listId', $listIdentifier)),
                $query->equals('type', Tx_PtExtlist_Domain_Model_Bookmark_Bookmark::PTEXTLIST_BOOKMARK_PRIVATE)));
            $result = $query->execute();
            return $result;
        } else {
            return null;
        }
    }
    
    
    
    /**
     * Returns collection of PUBLIC bookmarks for given list identifier
     *
     * @param string $listIdentifier
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_PtExtlist_Domain_Model_Bookmark_Bookmark>
     */
    public function findPublicBookmarksByListIdentifier($listIdentifier)
    {
        Tx_PtExtbase_Assertions_Assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1283117066'));
        $query = $this->createQuery();
        $query->setOrderings(array('name'=>'ASC'));
        $query->matching($query->logicalAnd(
            $query->logicalAnd($query->equals('listId', $listIdentifier), $query->equals('type', Tx_PtExtlist_Domain_Model_Bookmark_Bookmark::PTEXTLIST_BOOKMARK_PUBLIC)),
            $query->equals('pid', $this->bookmarkStoragePid)));
        $result = $query->execute();
        return $result;
    }
    
    
    
    /**
     * Returns collection of bookmarks for fe groups for all fe groups given user belongs to and a given list identifier
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $feUser
     * @param string $listIdentifier
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_PtExtlist_Domain_Model_Bookmark_Bookmark>
     */
    public function findGroupBookmarksByFeUserAndListIdentifier(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $feUser, $listIdentifier)
    {
        Tx_PtExtbase_Assertions_Assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1283117068'));
        $groupBookmarks = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $feUserGroups = $feUser->getUsergroup();
        foreach ($feUserGroups as $feUserGroup) { /* @var $feUserGroup \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup */
            $this->addObjectsToObjectStorageByArray($groupBookmarks, $this->findGroupBookmarksByFeGroupAndListIdentifier($feUserGroup, $listIdentifier));
        }
        return $groupBookmarks;
    }
    
    
    
    /**
     * Returns collection of bookmarks for fe groups for a given fe group and list identifier
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $feGroup
     * @param string $listIdentifier
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_PtExtlist_Domain_Model_Bookmark_Bookmark>
     */
    public function findGroupBookmarksByFeGroupAndListIdentifier(\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $feGroup, $listIdentifier)
    {
        Tx_PtExtbase_Assertions_Assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1283117067'));
        $query = $this->createQuery();
        $query->setOrderings(array('name'=>'ASC'));
        $query->matching($query->logicalAnd(
            $query->logicalAnd(
                $query->equals('feGroup', $feGroup->getUid()), $query->equals('listId', $listIdentifier)),
            $query->equals('type', Tx_PtExtlist_Domain_Model_Bookmark_Bookmark::PTEXTLIST_BOOKMARK_GROUP)));
        $result = $query->execute();
        return $result;
    }



    /**
     * Adds given elements of an array to given object storage
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $objectStorage
     * @param array $arrayToBeAdded
     */
    protected function addObjectsToObjectStorageByArray(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $objectStorage, $arrayToBeAdded)
    {
        foreach ($arrayToBeAdded as $key => $value) {
            $objectStorage->attach($value, $key);
        }
    }



    /**
     * Returns all group bookmarks for a given user and a given list of group uids for which bookmarks should be collected for a given list identifier.
     * 
     * Example: 
     *     User is in Groups (1,2,3,4)
     *     Groups to be shown (3,4)
     *     ==> all bookmarks for groups 3,4 are returned
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $feUser
     * @param string $groupIds Comma-separated list of group uids
     * @param string $listIdentifier
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark>
     */
    public function findBookmarksByFeUserGroupIdsAndListIdentifier(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $feUser, $groupIds, $listIdentifier)
    {
        Tx_PtExtbase_Assertions_Assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1283117069'));
        if (!is_array($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }
        
        $groupBookmarks = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $feUserGroups = $feUser->getUsergroups();
        foreach ($feUserGroups as $feUserGroup) { /* @var $feUserGroup \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup */
            if (in_array($feUserGroup->getUid(), $groupIds)) {
                $bookmarks = $this->findGroupBookmarksByFeGroupAndListIdentifier($feUserGroup, $listIdentifier);
                foreach ($bookmarks as $bookmark) {
                    $groupBookmarks->attach($bookmark);
                }
            }
        }
        
        return $groupBookmarks;
    }
}
