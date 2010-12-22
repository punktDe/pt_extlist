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


/**
 * 
 * TODO: Enter description here ...
 * @package ViewHelpers
 *
 */
class Tx_PtExtlist_ViewHelpers_HeaderViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * 
	 * Enter description here ...
	 * @param $header
	 * @param $captions
	 * @param $headerKey string
	 * @param $captionKey string
	 */
	public function render(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $headers, Tx_PtExtlist_Domain_Model_List_Row $captions, $headerKey='header', $captionKey="caption") {
		
		$output = '';
		if ($headers === NULL || $captions === NULL) {
			return '';
		}
		
		$output = '';
		foreach ($headers as $header) {

			$this->templateVariableContainer->add($captionKey, $captions->getItemById($header->getColumnIdentifier()));		
			$this->templateVariableContainer->add($headerKey, $header);
			$this->templateVariableContainer->add('sortable', $header->isSortable());
			$this->templateVariableContainer->add('sortingState', $header->getSortingState());
			
			$output .= $this->renderChildren();
			
			$this->templateVariableContainer->remove($captionKey);
			$this->templateVariableContainer->remove($headerKey);
			$this->templateVariableContainer->remove('sortable');
			$this->templateVariableContainer->remove('sortingState');
			
		}
		return $output;

	}

	
	
}

?>