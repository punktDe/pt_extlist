<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Christiane Helmchen, David Vogt, Michael Knoll
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
 *
 * @author David Vogt
 * @package Domain
 * @subpackage Model\Bookmarks
 * @entity
 * @see Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkStrategyTest
 */
class Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategy implements Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategyInterface
{
    /**
     * @param Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark
     * @param array $sessionData
     * @return array merged SessionData
     */
    public function mergeSessionAndBookmark(Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark, array $sessionData)
    {
        $contentArray = unserialize($bookmark->getContent());
        $listIdentifier = $bookmark->getListId();

        if (array_key_exists($listIdentifier, $contentArray)) {
            $sessionData[$listIdentifier] = array();

            if (array_key_exists('headerColumns', $contentArray[$listIdentifier])) {
                $sessionData[$listIdentifier]['headerColumns'] = $contentArray[$listIdentifier]['headerColumns'];
            }

            if (array_key_exists('filters', $contentArray[$listIdentifier])) {
                $sessionData[$listIdentifier]['filters'] = $contentArray[$listIdentifier]['filters'];
            }
        }

        return $sessionData;
    }


    /**
     * @param Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @param array $sessionData
     * @return void
     */
    public function addContentToBookmark(Tx_PtExtlist_Domain_Model_Bookmark_Bookmark $bookmark, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, array $sessionData)
    {
        $listIdentifier = $configurationBuilder->getListIdentifier();
        $bookmarkContentArray = array();

        if (array_key_exists($listIdentifier, $sessionData)) {
            if (array_key_exists('headerColumns', $sessionData[$listIdentifier])) {
                $bookmarkContentArray[$listIdentifier]['headerColumns'] = $sessionData[$listIdentifier]['headerColumns'];
            }

            if (array_key_exists('filters', $sessionData[$listIdentifier])) {
                $bookmarkContentArray[$listIdentifier]['filters'] = $sessionData[$listIdentifier]['filters'];
            }
        }

        $bookmark->setContent(serialize($bookmarkContentArray));
    }
}
