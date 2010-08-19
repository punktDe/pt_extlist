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
 * Class implements base view for all pt_extlist views. 
 * 
 * Class type for base view can be changed by changing pt_extlist AbstractController::resolveView()
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_View_BaseView extends Tx_Fluid_View_TemplateView {

	/**
     * Figures out which partial to use.
     * 
     * We overwrite this method to make sure that we can use something like this in our partial:
     * 
     * partialPath = EXT:pt_extlist/Resources/Private/Partials/Filter/StringFilter.html
     *
     * @param string $partialName The name of the partial
     * @return string the full path which should be used. The path definitely exists.
     * @throws Tx_Fluid_View_Exception_InvalidTemplateResourceException
     * @author Sebastian KurfÃ¼rst <sebastian@typo3.org>
     */
	protected function resolvePartialPathAndFilename($partialName) {
		if (file_exists($partialName)) { // partial is given as absolute path (rather unusual :-) )
			return $partialName;
		} elseif (file_exists(t3lib_div::getFileAbsFileName($partialName))) { // partial is given as EXT:pt_extlist/Resources/Private/Partials/Filter/StringFilter.html
			return t3lib_div::getFileAbsFileName($partialName);
		} else { // partial is given in the "ExtBase" way
			return parent::resolvePartialPathAndFilename($partialName);
		}
	}
	
}