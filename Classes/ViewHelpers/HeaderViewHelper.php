<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de>
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

class Tx_PtExtlist_ViewHelpers_HeaderViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	
	
	/**
	 * 
	 * Enter description here ...
	 * @param $header
	 * @param $captions
	 * @param $key string
	 */
	public function render(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $headers, Tx_PtExtlist_Domain_Model_List_Row $captions, $key='header') {
		$output = '';
		if ($headers === NULL || $captions === NULL) {
			return '';
		}
		
		$output = '';
		foreach ($headers as $header) {
			$this->templateVariableContainer->add('label', $captions->getItemById($header->getColumnIdentifier()));		
			$this->templateVariableContainer->add('sortingImg', $this->renderSorting($header));
			$this->templateVariableContainer->add('state', $header->getSortingState());
			$this->templateVariableContainer->add('column', $header->getColumnIdentifier());
			$this->templateVariableContainer->add('sortable', $header->isSortable());
			
			$output .= $this->renderChildren();
			
			$this->templateVariableContainer->remove('label');
			$this->templateVariableContainer->remove('state');
			$this->templateVariableContainer->remove('column');
			$this->templateVariableContainer->remove('sortable');
			$this->templateVariableContainer->remove('sortingImg');
			
		}
		return $output;

	}
	
	protected function renderSorting(Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header) {

		if(!$header->isSortable()) {
			return 'typo3conf/ext/pt_extlist/Resources/Public/List/icon_table_sort_default.png';
		} elseif($header->getSortingState() == Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC) {
			return 'typo3conf/ext/pt_extlist/Resources/Resources/Public/List/icon_table_sort_asc.png';
		} else {
			return 'typo3conf/ext/pt_extlist/Resources/Resources/Public/List/icon_table_sort_desc.png';
		}

	}
	
	
}

?>