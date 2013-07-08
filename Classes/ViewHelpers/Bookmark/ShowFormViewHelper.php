<?php

class Tx_PtExtlist_ViewHelpers_Bookmark_ShowFormViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {


	/**
	 * @param Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig $bookmarkConfig
	 * @return boolean
	 */
	public function render(Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig $bookmarkConfig) {
		return ($bookmarkConfig->getCreatePublicBookmarks() || $bookmarkConfig->getCreatePrivateBookmarks() || $bookmarkConfig->getCreateGroupBookmarks());
	}
}