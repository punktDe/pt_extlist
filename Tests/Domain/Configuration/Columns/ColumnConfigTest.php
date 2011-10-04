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
 * Class implementing tests for column config
 *
 * @package Tests
 * @subpackage Domain\Configuration\Columns
 * @author Daniel Lienert <linert@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Columns_ColumnConfig_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * @var Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock
	 */
	protected $configurationBuilderMock;



	/**
	 * Holds a dummy configuration for a column config object
	 * @var array
	 */
	protected $columnSettings = array();


	
	/**
	 * Holds an instance of column configuration object
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig
	 */
	protected $columnConfig = null; 


	
	/**
	 * @return void
	 * @author Daniel Lienert 
	 */
	public function setup() {
		
		$this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
		$allColumnSettings = $this->configurationBuilderMock->getSettingsForConfigObject('columns');
		$this->columnSettings = $allColumnSettings[30];
		
		$this->columnConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->configurationBuilderMock, $this->columnSettings);
	}
	
	
	
	public function testGetFieldIdentifier() {
		$this->assertEquals($this->columnConfig->getFieldIdentifier()->getFieldConfigByIdentifier($this->columnSettings['fieldIdentifier'])->getIdentifier(), $this->columnSettings['fieldIdentifier']);
	}
	
	
	
	public function testGetColumnIdentifier() {
		$this->assertEquals($this->columnConfig->getColumnIdentifier(), $this->columnSettings['columnIdentifier']);
	}
	
	
	
	public function testGetLabelWhenLabelGiven() {
		$this->assertEquals($this->columnConfig->getLabel(), $this->columnSettings['label']);
	}


	
	public function testGetAccessGroups() {
		$this->assertEquals($this->columnConfig->getAccessGroups(), t3lib_div::trimExplode(',',$this->columnSettings['accessGroups']));
	}
	


    /** @test */
	public function getLabelReturnsEmptyStringIfNoLabelIsGiven() {
		unset($this->columnSettings['label']);
		$this->columnConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->configurationBuilderMock, $this->columnSettings);
		$this->assertEquals($this->columnConfig->getLabel(), '');
	}
	


	public function testNoColumnIdentifierGivenException() {
		try {
			new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->configurationBuilderMock, array('fieldIdentifier' => 'test'));
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
	
	
	public function testNoFieldIdentifierGivenException() {
		try {
			new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->configurationBuilderMock, array('columnIdentifier' => 'test'));
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}



	public function testSetAccessable() {
		$this->columnConfig->setAccessable(true);
		$this->assertTrue($this->columnConfig->isAccessable());
	}
	
	
	
	public function testIsAccessableDefault() {
		$this->assertFalse($this->columnConfig->isAccessable());
	}
	


	public function testGetContainsArrayData() {
		$pluginSettings = $this->configurationBuilderMock->getPluginSettings();
		$pluginSettings['listConfig']['test']['fields']['field4']['expandGroupRows'] = 1;
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($pluginSettings);
		
		$allColumnSettings = $configurationBuilderMock->getSettingsForConfigObject('columns');
		
		$columnSettings = $allColumnSettings[40];
		$columnConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($configurationBuilderMock, $columnSettings);
		
		$this->assertTrue($columnConfig->getContainsArrayData());
	}
	


	public function testGetRenderTemplate() {
		$this->assertEquals($this->columnSettings['renderTemplate'], $this->columnConfig->getRenderTemplate());
	}



	public function testGetCellCSSClass() {
		$this->assertEquals($this->columnSettings['cellCSSClass'], $this->columnConfig->getCellCSSClass());
	}



    /** @test */
    public function getHeaderThCssClassReturnsValueFromSettings() {
        $columnSettings = $this->columnSettings;
        $columnSettings['headerThCssClass'] = 'testCssClass';
        $columnConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->configurationBuilderMock, $columnSettings);
        $this->assertEquals($columnConfig->getHeaderThCssClass(), 'testCssClass');
    }

}
?>