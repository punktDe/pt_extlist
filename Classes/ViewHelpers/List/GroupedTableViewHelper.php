<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Simon Schaufelberger <schaufelberger@punkt.de>
 *
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
 * Grouped viewhelper
 * Groups a list for a special column like the ExtJS Ext.grid.GroupingView
 *
 * @author Simon Schaufelberger <schaufelberger@punkt.de>
 * @package ViewHelpers
 * @subpackage List
 */
class Tx_PtExtlist_ViewHelpers_List_GroupedTableViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $hideGroupedColumn = false;

	/**
	 * @var boolean
	 */
	protected $showGroupName = true;

	/**
	 * Collection of rows to be returned
	 * @var Tx_PtExtlist_Domain_Model_List_ListData
	 */
	protected $virtualListData;


	/**
	 *
	 * Enter description here ...
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $listData
	 * @param string $groupedcolumn Columnidentifier of column that should be grouped
	 */
	public function render(Tx_PtExtlist_Domain_Model_List_ListData $listData, $groupedcolumn) {
		$this->getGroupInformation($listData, $groupedcolumn);
	}


	/**
	 * Calculates the rowSpanCount, and the mainLineIndex, and sets it in every cell
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $listData
	 * @param string $groupedcolumn Columnidentifier of column that should be grouped
	 */
	protected function getGroupInformation(Tx_PtExtlist_Domain_Model_List_ListData $listData, $groupedcolumn) {

		/* holds a reference to the current group name */
		$currentGroup = '';

		/* sum of the colums for colspan */
		$mainRowIndex = 0;

		/* temporary list for later use
		 * @var Tx_PtExtlist_Domain_Model_List_ListData
		 */
		$tempListData = new Tx_PtExtlist_Domain_Model_List_ListData();

		/* has the counter of how many items are in the group */
		$childRows = array();

		/* loop over list so that we know how many items we have in each group */

		foreach($listData as $rowID => $row) {
			/* erste gruppe erstellen */
			// TODO: später soll da dann ein "Template" reingesteckt werden können
			$template = $row->getCell($groupedcolumn)->getValue();
			$groupCell = new Tx_PtExtlist_Domain_Model_List_Cell('Datum ('.$template.')');
			$groupCell->setRowIndex($mainRowIndex);
			$groupCell->addSpecialValue('colSpan', $row->count());

			$groupRow = new Tx_PtExtlist_Domain_Model_List_Row();
			$groupRow->addCell($groupCell, $groupedcolumn.'Group');


			/* gruppe füllen so lange, bis sich der Wert der gruppierten Spalte ändert */
			$groupingColumnValue = $row->getCell($groupedcolumn);
			if ($currentGroup != $groupingColumnValue->getValue()) {
				$currentGroup = $groupingColumnValue->getValue();
				$childRows[$currentGroup]++;

				// add group header row
				$tempListData->addRow($groupRow);
			}
			$tempListData->addRow($row);
			$mainRowIndex++;
		}

		$this->templateVariableContainer->add('groupedListData', $tempListData);
	}


	function dummy() {
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
	}
}
?>