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
 * Type: Post-list Renderer
 * 
 * Custom renderer for a structured list
 * Adds information to the fields to show a structured table with rowSpans 
 * 
 * @package Domain
 * @subpackage Renderer\Custom
 * @author Daniel Lienert <lienert@punkt.de>

 */
class Tx_PtExtlist_Domain_Renderer_Custom_LTRStructuredRenderer extends Tx_PtExtlist_Domain_Renderer_AbstractRenderer {
	
	/**
	 * Renders list data
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $listData
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function renderList(Tx_PtExtlist_Domain_Model_List_ListData $listData) {
		
		$structuredColumnCount = $this->rendererConfiguration->getSettings('structuredColumnCount');
		
		$childCountMap = array();
		$firstRowMarker = array();
		$mainRowCount = 0;
		
		foreach($listData as $rowID => $row) {
			
			$cellIdent = '';			
			$columnIndex = 1;
			
			foreach($row as $cellId => $cell) {
				
				// row styling
				if($cellIdent == '' && $childCountMap[$cell->getValue()] == 0) {
					$listData->getItemById($rowID)->addSpecialValue('mainRowClass', 'mainRow');
					$mainRowCount++;
				}
				
				$rowClass = $mainRowCount % 2 == 0 ? 'odd' : 'even';
				$listData->getItemById($rowID)->addSpecialValue('oddEvenClass', $rowClass);
				
				
				$cellIdent .= $cell->getValue();
				
				$childCountMap[$cellIdent]++;
				
				if(!array_key_exists($cellIdent, $firstRowMarker)) {
					$firstRowMarker[$cellIdent] = $rowID;	
				}
				
				if($columnIndex <= $structuredColumnCount) {
					$listData->getItemById($firstRowMarker[$cellIdent])->getItemById($cellId)->addSpecialvalue('rowSpan',$childCountMap[$cellIdent]);
				} else {
					$listData->getItemById($rowID)->getItemById($cellId)->addSpecialvalue('rowSpan',1);
				}
				
				$columnIndex++;
			}			
		}

		return $listData;
	}
}
?>