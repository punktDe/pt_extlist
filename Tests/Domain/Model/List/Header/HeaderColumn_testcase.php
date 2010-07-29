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
 * Testcase for header column class
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_Model_List_Header_HeaderColumn_testcase extends Tx_Extbase_BaseTestcase {
	
	/**
	 * @var Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock
	 */
	protected $configurationBuilderMock;
	
	public function setup() {
		$this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
	}
	
	public function testConfigurationAndStateMerge() {
		$columnConfiguration = $this->configurationBuilderMock->buildColumnsConfiguration()->pop();
		
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$headerColumn->injectColumnConfig($columnConfiguration);
	
	}
	
	public function testGetSortings() {
		$columnConfiguration = $this->configurationBuilderMock->buildColumnsConfiguration()->pop();
		
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$headerColumn->injectColumnConfig($columnConfiguration);
		
		$GLOBALS['trace'] = 1;	trace($headerColumn->getSorting() ,0,'Quick Trace in file ' . basename( __FILE__) . ' : ' . __CLASS__ . '->' . __FUNCTION__ . ' @ Line : ' . __LINE__ . ' @ Date : '   . date('H:i:s'));	$GLOBALS['trace'] = 0; // RY25 TODO Remove me
	}
	
}
?>