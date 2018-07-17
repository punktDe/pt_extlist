<?php
namespace PunktDe\PtExtlist\ViewHelpers\Bookmark;

class ShowFormViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('bookmarkConfig', \Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig::class, 'bookmark config', true);
        $this->registerArgument('userLoggedIn', 'integer', 'user logged in', true);
        parent::initializeArguments();
    }

    /**
     * @return boolean
     */
    public function render()
    {
        /** @var \Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig $bookmarkConfig */
        $bookmarkConfig = $this->arguments['bookmarkConfig'];
        return ($bookmarkConfig->getCreatePublicBookmarks() || ($bookmarkConfig->getCreatePrivateBookmarks() && $this->arguments['userLoggedIn']) || $bookmarkConfig->getCreateGroupBookmarks());
    }
}
