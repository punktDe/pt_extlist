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
 * Class implements configuration for bookmarks
 *
 * @package Domain
 * @subpackage Configuration\Bookmarks
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Bookmark_BookmarkConfgTest
 */
class Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration
{
    /**
     * Holds comma separated list of pids to search for bookmarks
     *
     * @var string
     */
    protected $bookmarkPid;
    
    
    
    /**
     * If true, user bookmarks should be displayed
     *
     * @var boolean
     */
    protected $showPrivateBookmarks;
    
    
    
    /**
     * If true, group bookmarks should be displayed
     *
     * @var boolean
     */
    protected $showGroupBookmarks;
    
    
    
    /**
     * If true, public bookmarks should be displayed
     *
     * @var boolean
     */
    protected $showPublicBookmarks;


    /**
     * Holds comma-seperated list of fe group ids to show bookmarks for
     *
     * @var string
     */
    protected $groupIdsToShowBookmarksFor;



    /**
     * If true, users are allowed to create public bookmarks
     *
     * @var boolean
     */
    protected $createPublicBookmarks;



    /**
     * If true, users are allowed to create private bookmarks
     *
     * @var boolean
     */
    protected $createPrivateBookmarks;



    /**
     * If true, users are allowed to create group bookmarks
     *
     * @var boolean
     */
    protected $createGroupBookmarks;



    /**
     * @var boolean
     */
    protected $userCanDeleteAll;



    /**
     * Initializes properties from given settings
     *
     */
    protected function init()
    {
        $this->setValueIfExistsAndNotNothing('bookmarkPid');
        $this->setValueIfExistsAndNotNothing('groupIdsToShowBookmarksFor');
        
        $this->setBooleanIfExistsAndNotNothing('showPrivateBookmarks');
        $this->setBooleanIfExistsAndNotNothing('showGroupBookmarks');
        $this->setBooleanIfExistsAndNotNothing('showPublicBookmarks');
        $this->setBooleanIfExistsAndNotNothing('createPublicBookmarks');
        $this->setBooleanIfExistsAndNotNothing('createPrivateBookmarks');
        $this->setBooleanIfExistsAndNotNothing('createGroupBookmarks');
        $this->setBooleanIfExistsAndNotNothing('userCanDeleteAll');
    }



    /**
     * @return boolean
     */
    public function getUserCanDeleteAll()
    {
        return $this->userCanDeleteAll;
    }



    /**
     * @return boolean
     */
    public function getCreateGroupBookmarks()
    {
        return $this->createGroupBookmarks;
    }



    /**
     * @return boolean
     */
    public function getCreatePrivateBookmarks()
    {
        return $this->createPrivateBookmarks;
    }



    /**
     * @return boolean
     */
    public function getCreatePublicBookmarks()
    {
        return $this->createPublicBookmarks;
    }
    
    
    
    /**
     * @return string
     */
    public function getBookmarkPid()
    {
        return $this->bookmarkPid;
    }
    

    
    /**
     * Returns comma-separated list of fe groups to show bookmarks for
     * 
     * @return string
     */
    public function getGroupIdsToShowBookmarksFor()
    {
        return $this->groupIdsToShowBookmarksFor;
    }

    
    
    /**
     * Returns true if group bookmarks should be shown
     * 
     * @return bool
     */
    public function getShowGroupBookmarks()
    {
        return $this->showGroupBookmarks;
    }
    
    
    
    /**
     * Returns true if public bookmarks should be shown
     * 
     * @return bool
     */
    public function getShowPublicBookmarks()
    {
        return $this->showPublicBookmarks;
    }
    
    
    
    /**
     * Returns TRUE if private bookmarks should be shown
     * 
     * @return bool
     */
    public function getShowPrivateBookmarks()
    {
        return $this->showPrivateBookmarks;
    }
}
