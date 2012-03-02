<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Default rendering strategy for rendering column captions
 * 
 * @package Domain
 * @subpackage Renderer\Strategy
 * @author Michael Knoll
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Renderer_Default_CaptionRenderer implements t3lib_Singleton {

	/**
	 * Renders captions
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader
	 * @return Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader
	 */
	public function renderCaptions(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader) {
		Tx_PtExtbase_Assertions_Assert::isNotNull($listHeader, array(message => 'No header data available. 1280408235'));
		
		$renderedListHeader = new Tx_PtExtlist_Domain_Model_List_Header_ListHeader($listHeader->getListIdentifier());

		foreach($listHeader as $headerColumn) {

			if($headerColumn->getColumnConfig()->isAccessable() && $headerColumn->getIsVisible()) {
				$label = $this->renderColumnLabel($headerColumn);
			   $renderedListHeader->createAndAddCell($label, $headerColumn->getColumnIdentifier());
			}
		}

		return $renderedListHeader;
	}



	/**
	 * @param Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $headerColumn
	 * @return rendered|string
	 */
	public function renderColumnLabel(Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $headerColumn) {
		$label = $headerColumn->getLabel();

		$label = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($label);

		if(t3lib_div::isFirstPartOfStr($label, 'LLL:')) {
			$label = Tx_Extbase_Utility_Localization::translate($label, '');
		}

		return $label;
	}
	
}
?>