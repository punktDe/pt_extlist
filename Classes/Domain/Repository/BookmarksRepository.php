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
 * Class implements bookmarks repository
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Repository_BookmarksRepository extends Tx_Extbase_Persistence_Repository {
	
    /**
     * Returns collection of bookmarks for given feUser and list identifier
     *
     * @param Tx_Extbase_Domain_Model_FrontendUser $feUser
     * @param string $listIdentifier
     * @return Tx_Extbase_Persistence_ObjectStorage<Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark>
     */	
	public function findBookmarksByFeUserAndListIdentifier(Tx_Extbase_Domain_Model_FrontendUser $feUser, $listIdentifier) {
		$feUserUid = $feUser->getUid();
		tx_pttools_assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier must not be empty! 1283117065'));
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
}
 
?>