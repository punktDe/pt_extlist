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
 */
class Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration {
	
	/**
	 * Holds comma seperated list of pids to search for bookmarks
	 *
	 * @var string
	 */
	protected $bookmarksPid;
	
	
	
	/**
	 * If true, user bookmarks should be displayed
	 *
	 * @var bool
	 */
    protected $showUserBookmarks;
    
    
    
    /**
     * If true, group bookmarks should be displayed
     *
     * @var bool
     */
    protected $showGroupBookmarks;
    
    
    
    /**
     * If true, public bookmarks should be displayed
     *
     * @var bool
     */
    protected $showPublicBookmarks;

    
    
    /**
     * Comma seperated list of fe users that are allowd to edit bookmarks
     *
     * @var string
     */
    protected $feUsersAllowedToEdit;
    
    
    
    /**
     * Comma seperated list of fe groups that are allowed to edit bookmarks
     *
     * @var string
     */
    protected $feGroupsAllowedToEdit;
    
    
    
    /**
     * Comma seperated list of fe users that are allowed to edit public bookmarks
     *
     * @var string
     */
    protected $feUsersAllowedToEditPublic;
    
    
    
    /**
     * Comma seperated list of fe groups that are allowed to edit public bookmarks
     *
     * @var unknown_type
     */
    protected $feGroupsAllowedToEditPublic;
    
    
    
    /**
     * Holds comma-seperated list of fe group ids to show bookmarks for 
     *
     * @var string
     */
    protected $groupIdsToShowBookmarksFor;
   
    
    
    /**
     * Initializes properties from given settings
     *
     */
    protected function init() {
    	
    	$this->setValueIfExistsAndNotNothing('bookmarksPid');
    	$this->setValueIfExistsAndNotNothing('feUsersAllowedToEdit');
    	$this->setValueIfExistsAndNotNothing('feGroupsAllowedToEdit');
    	$this->setValueIfExistsAndNotNothing('feUsersAllowedToEditPublic');
    	$this->setValueIfExistsAndNotNothing('feGroupsAllowedToEditPublic');
    	$this->setValueIfExistsAndNotNothing('groupIdsToShowBookmarksFor');
    	
    	$this->setBooleanIfExistsAndNotNothing('showUserBookmarks');
    	$this->setBooleanIfExistsAndNotNothing('showGroupBookmarks');
    	$this->setBooleanIfExistsAndNotNothing('showPublicBookmarks');
    }
    
    
    
	/**
	 * Returns comma seperated list of pids to search for bookmarks in
	 * 
	 * @return string
	 */
	public function getBookmarksPid() {
		return $this->bookmarksPid;
	}
	
	
	
	/**
	 * Returns comma seperated list of fe groups to be allowed editing bookmarks
	 * 
	 * @return string
	 */
	public function getFeGroupsAllowedToEdit() {
		return $this->feGroupsAllowedToEdit;
	}
	
	
	
	/**
	 * Returns comma-seperated list of fe users to be allowed editing bookmarks
	 * 
	 * @return string
	 */
	public function getFeUsersAllowedToEdit() {
		return $this->feUsersAllowedToEdit;
	}
	
	
	
	/**
	 * Returns comma-seperated list of fe users that are allowed editing public bookmarks
	 *
	 * @return string
	 */
	public function getFeUsersAllowedToEditPublic() {
		return $this->feUsersAllowedToEditPublic;
	}
	
	
	
	/**
	 * Returns comma-seperated list of fe groups that are allowed editing public bookmarks
	 *
	 * @return string
	 */
	public function getFeGroupsAllowedToEditPublic() {
		return $this->feGroupsAllowedToEditPublic;
	}
	
	
	
	/**
	 * Returns comma-seperated list of fe groups to show bookmarks for
	 * 
	 * @return string
	 */
	public function getGroupIdsToShowBookmarksFor() {
		return $this->groupIdsToShowBookmarksFor;
	}

	
	
	/**
	 * Returns true if group bookmarks should be shown
	 * 
	 * @return bool
	 */
	public function getShowGroupBookmarks() {
		return $this->showGroupBookmarks;
	}
	
	
	
	/**
	 * Returns true if public bookmarks should be shown
	 * 
	 * @return bool
	 */
	public function getShowPublicBookmarks() {
		return $this->showPublicBookmarks;
	}
	
	
	
	/**
	 * Returns TRUE if user bookmarks should be shown
	 * 
	 * @return bool
	 */
	public function getShowUserBookmarks() {
		return $this->showUserBookmarks;
	}

}
?>