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
 * Class implementing bookmarks controller
 *
 * @package pt_extlist
 * @subpackage Controller
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Controller_BookmarksController extends Tx_PtExtlist_Controller_AbstractController {
    
    /**
     * Holds an instance of fe user repository
     *
     * @var Tx_Extbase_Domain_Repository_FrontendUserRepository
     */
    protected $feUserRepository = null;
    
    
    
    /**
     * Holds an instance of bookmarks repository
     *
     * @var Tx_PtExtlist_Domain_Repository_BookmarksRepository
     */
    protected $bookmarksRepository = null;
    
    
    
    /**
     * Holds an instance of currently logged in fe user
     *
     * @var Tx_Extbase_Domain_Model_FrontendUser
     */
    protected $feUser = null;
    

    
    /**
     * Constructor for bookmarks controller
     * 
     * @return void
     */
    public function __construct() {
    	parent::__construct();
    }
    
    
    
    /**
     * Injects the settings of the extension.
     *
     * @param array $settings Settings container of the current extension
     * @return void
     */
    public function injectSettings(array $settings) {
        parent::injectSettings($settings);
        // TODO we create feUserRepository here to be able to set one manually when testing
        $this->feUserRepository  = t3lib_div::makeInstance('Tx_Extbase_Domain_Repository_FrontendUserRepository'); /* @var $feUserRepository Tx_Extbase_Domain_Repository_FrontendUserRepository */   
    	$this->bookmarksRepository = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository');
    	$this->bookmarksRepository->setBookmarksStoragePid($this->settings['bookmarks']['bookmarksPid']);
    }
    
    
    
    /**
     * Initializes controller
     * 
     * @return void
     */
    protected function initializeAction() {
        $feUserUid = $GLOBALS['TSFE']->fe_user->user['uid'];
        if ($feUserUid > 0) {
            $this->feUser = $this->feUserRepository->findByUid(intval($feUserUid));
        } 
    }
    
    
    
    /**
     * Renders show action for bookmark controller
     * 
     * @return string The rendered HTML source for this action
     */
    public function showAction() {
    	if ($this->showPublicBookmarks()) {
	    	$publicBookmarks = $this->bookmarksRepository->findPublicBookmarksByListIdentifier($this->listIdentifier);
	    	$this->view->assign('publicBookmarks', $publicBookmarks);
    	}
    	if ($this->showUserBookmarks() && $this->feUser != null) {
    	    $userBookmarks = $this->bookmarksRepository->findBookmarksByFeUserAndListIdentifier($this->feUser, $this->listIdentifier);
    	    $this->view->assign('userBookmarks', $userBookmarks);
    	}
    	if ($this->showGroupBookmarks() && $this->feUser != null && count($this->feUser->getUsergroups()) > 0) {
    		$groupBookmarks = $this->bookmarksRepository->findBookmarksByFeUserGroupIdsAndListIdentifier($this->feUser, $this->getGroupIdsToShowBookmarksFor(), $this->listIdentifier);
    		$this->view->assign('groupBookmarks', $groupBookmarks);
    	}
    }
    
    
    
    /**
     * Action for creating a new bookmark
     */
    public function createAction() {
    	
    }
    
    
    
    /**
     * Action for updating a bookmark
     */
    public function updateAction() {
    	
    }
    
    
    
    /**
     * Action for deleting a bookmark
     */
    public function deleteAction() {
    	
    }
    
    
    
    /**
     * Action for editing a bookmark
     */
    public function editAction() {
    	
    }
    
    
    
    protected function showUserBookmarks() {
    	// TODO read out settings here!
    	return true;
    }
    
    
    
    protected function showGroupBookmarks() {
    	// TODO read out settings here!
    	return true;
    }
    
    
    
    protected function showPublicBookmarks() {
    	// TODO read out settings here!
    	return true;
    }
    
    
    
    protected function getGroupIdsToShowBookmarksFor() {
    	// TODO read out settings here!
    	return '1,2,3,4,5';
    }
    
}
?>