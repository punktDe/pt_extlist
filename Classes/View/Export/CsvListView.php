<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
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
 * Implements a view for rendering CSV values
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package View
 * @subpackage Export
 */
class Tx_PtExtlist_View_Export_CsvListView extends Tx_PtExtlist_View_Export_AbstractExportView {

	/**
	 * Overwriting the render method to generate a CSV output
	 *
	 * @return  void (never returns)
	 */
	public function render() {

		$templateVariableContainer = $this->baseRenderingContext->getTemplateVariableContainer();

		ob_clean();

		$this->sendHeader($this->getFilenameFromTs());
		$out = fopen('php://output', 'w');

		// Headers
		if ($templateVariableContainer->exists('listCaptions')) {
			$row = array();
			foreach ($templateVariableContainer['listCaptions'] as $caption) { /* @var $header Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn */
				$row[] = $caption;
			}
			fputcsv($out, $row, ";"); // todo - delimiter configurabe by ts
		}

		// Rows
		foreach ($templateVariableContainer['listData'] as $listRow) { /* @var $row Tx_PtExtlist_Domain_Model_List_Row */
			$row = array();
			foreach ($listRow as $listCell) { /* @var $listCell Tx_PtExtlist_Domain_Model_List_Cell */
				$row[] = $listCell->getValue();
			}
			$row = tx_pttools_div::iconvArray($row, 'UTF-8', 'ISO-8859-1');     // TODO: make encoding configurable via TS
			fputcsv($out, $row, ";");
		}

		fclose($out);

		exit();
	}
}