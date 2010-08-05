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

class Tx_PtExtlist_Tests_Domain_Renderer_Strategy_DefaultCellRenderingStrategy_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $cellRenderer;
	
	public function setUp() {

		$this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
		
		$this->renderSettings = array(
				'rendererClassName' => 'Tx_PtExtlist_Domain_Renderer_DefaultRenderer',
				'enabled' => 1,
				'showCaptionsInBody' => 0,
		);
		
		$columnConfig = Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollectionFactory::getColumnConfigCollection($this->configurationBuilderMock);
		$rendererConfig = Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfigFactory::getRendererConfiguration($this->renderSettings,$columnConfig,$this->configurationBuilderMock->buildFieldsConfiguration());
		
		$this->cellRenderer = new Tx_PtExtlist_Domain_Renderer_Strategy_DefaultCellRenderingStrategy($rendererConfig);
	}
	
	public function testRenderCell() {
		
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		
		$row->addCell('field1', 'val1');
		$row->addCell('field2', 'val2');
		$row->addCell('field3', 'val3');
		
		// see ConfigurationBuilderMock for column definition
		$cellContent = $this->cellRenderer->renderCell('field1', '10', $row);
		$this->assertEquals('val1', $cellContent); 
		
	}
	
	
	
}

?>