<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert , Michael Knoll 
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
 * Testcase for list data class
 * 
 * @author Michael Knoll 
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_Model_List_ListData_testcase extends Tx_Extbase_BaseTestcase {
	
	public function testSetup() {
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
	}
	
	public function testAddRow() {
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		$listData->addRow($row);
		// TODO check row ??
	}
	
}

?>