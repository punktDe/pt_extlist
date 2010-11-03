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

class Tx_PtExtlist_Tests_Domain_Configuration_Columns_ColumnConfigCollection_testcase extends Tx_Extbase_BaseTestcase {

	/**
	 * @var Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock
	 */
	protected $configurationBuilderMock;
	
	public function setup() {
		$this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
	}
		
	public function testExceptionOnNonCorrectItemAdded() {
		$columnConfigCollection = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection($this->configurationBuilderMock);
		try {
		    $columnConfigCollection->addColumnConfig('test');
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
	
	
	public function testExceptionOnGettingNonAddedItem() {
		$columnConfigCollection = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection($this->configurationBuilderMock);
        try {
            $columnConfigCollection->getColumnConfigByIdentifier(30);
        } catch(Exception $e) {
            return;
        }
        $this->fail();
	}
	
	
	
	public function testAddGetCorrectItems() {
		
		$columnSettings = $this->configurationBuilderMock->getColumnSettings();
		
		$columnConfigCollection = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection();
		$columnConfigCollection->addColumnConfig(10,new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->configurationBuilderMock, $columnSettings[10]));
		$columnConfigCollection->addColumnConfig(20,new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->configurationBuilderMock, $columnSettings[20]));
		$columnConfig10 = $columnConfigCollection->getColumnConfigByIdentifier(10);
		$this->assertEquals($columnConfig10->getColumnIdentifier(), 'column1');
		$columnConfig20 = $columnConfigCollection->getColumnConfigByIdentifier(20);
		$this->assertEquals($columnConfig20->getColumnIdentifier(), 'column2');
	}
	
}
?>