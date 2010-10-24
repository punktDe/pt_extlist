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
 * TODO insert comment!
 * 
 * @package Domain
 * @subpackage Renderer\Strategy
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 */
interface Tx_PtExtlist_Domain_Renderer_Strategy_CellRenderingStrategyInterface {

	/**
	 * Renders the cell content.
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfig 
	 * @param Tx_PtExtlist_Domain_Model_List_Row &$data The table data.
	 * @param int $columnIndex Current column index.
	 * @param int $rowIndex Current row index.
	 * 
	 * @return Tx_Pt_extlist_Domain_Model_List_Cell
	 */
	public function renderCell(Tx_PtExtlist_Domain_Configuration_ColumnConfigInterface $columnConfig, Tx_PtExtlist_Domain_Model_List_Row &$data, $columnIndex, $rowIndex);
}

?>