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
 * Class implements list data object containing rows for a list.
 * 
 * @author Michael Knoll
 * @author Daniel Lienert
 * @author Christoph Ehscheidt
 */
class Tx_PtExtlist_Domain_Model_List_ListData extends tx_pttools_objectCollection {
	
	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Model_List_Row';
	
	/**
	 * Adds a row to list data
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Row $row   Row to be added to list data
	 * @return void
	 */
	public function addRow(Tx_PtExtlist_Domain_Model_List_Row $row) {
		$this->addItem($row);
	}	
}
?>