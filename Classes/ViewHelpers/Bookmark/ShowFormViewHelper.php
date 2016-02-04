<?php

class Tx_PtExtlist_ViewHelpers_Bookmark_ShowFormViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @param Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig $bookmarkConfig
     * @param integer $userLoggedIn
     * @return boolean
     */
    public function render(Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig $bookmarkConfig, $userLoggedIn)
    {
        return ($bookmarkConfig->getCreatePublicBookmarks() || ($bookmarkConfig->getCreatePrivateBookmarks() && $userLoggedIn) || $bookmarkConfig->getCreateGroupBookmarks());
    }
}
