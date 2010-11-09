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

	/**
	 * Holds an instance of cell renderer to be tested
	 *
	 * @var Tx_PtExtlist_Domain_Renderer_Strategy_DefaultCellRenderingStrategy
	 */
	protected $cellRenderer;
	
	
	
	public function setUp() {
		$this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
		$settings = $this->configurationBuilderMock->getSettings();
		/**
		 * Change some 
		 */
		$settings['prototype'] = array();
		$settings['listIdentifier'] = 'testCellRenderer';
		$settings['listConfig']['testCellRenderer']['columns'][40] = array( 
                            'columnIdentifier' => 'column4',
                            'fieldIdentifier' => 'field3',
                            'label' => 'Column 3',  
                            'renderObj' => array(
								'_typoScriptNodeValue' => 'COA',
								'10' => array(
									'_typoScriptNodeValue' => 'COA',
									'value' => 'test'
								)	
							)
	                    );
	    $settings['listConfig']['testCellRenderer']['columns'][50] = array( 
                            'columnIdentifier' => 'column5',
                            'fieldIdentifier' => 'field5',
                            'label' => 'Column 5',  
                            'renderUserFunctions' => array(
								'_typoScriptNodeValue' => 'COA',
								'10' => array(
									'_typoScriptNodeValue' => 'EXT:pt_extlist/Tests/Domain/Renderer/Strategy/RenderUserFunc.php->render',
									'config' => 'blabla'
								)	
							)
	                    );
	    $this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($settings);
        $this->cellRenderer = new Tx_PtExtlist_Domain_Renderer_Default_DefaultCellRenderingStrategy($this->getRendererConfiguration());
	}
	
	
	
	/** @test */
	public function renderCellReturnsRenderedCell() {
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		
		$row->createAndAddCell('val1', 'field1');
		$row->createAndAddCell('val2', 'field2');
		$row->createAndAddCell('val3', 'field3');
		
		// see ConfigurationBuilderMock for column definition
		$columnConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->configurationBuilderMock, array('columnIdentifier' => 'column1', 'fieldIdentifier' => 'field1'));
		$cellContent = $this->cellRenderer->renderCell($columnConfig, $row);
		$this->assertEquals('val1', $cellContent->getValue()); 
		
	}
	
	
	
	/*
	public function testSpecialValueRendering() {
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		
		$row->createAndAddCell('val1', 'field1');
		$row->createAndAddCell('val2', 'field2');
		$row->createAndAddCell('val3', 'field3');
		
		// see ConfigurationBuilderMock for column definition
		$columnConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig($this->configurationBuilderMock, array('columnIdentifier' => 'column1', 'fieldIdentifier' => 'field1'));
		$cellContent = $this->cellRenderer->renderCell($columnConfig, $row, '10');
		$this->assertTrue(is_array($cellContent->getSpecialValues()));
	}
	
 	public function testTSRendering() {
		$cObjMock = $this->getMock('tslib_cObj', array('cObjGet'));
		$cObjMock->expects($this->once())->method('cObjGet');
		
		$this->cellRenderer->_set('cObj', $cObjMock);

		
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		$row->addCell('field1', 'val1');
		$row->addCell('field2', 'val2');
		$row->addCell('field3', 'val3');
		$row->addCell('field4', 'val4');
		
		$cellContent = $this->cellRenderer->renderCell('field3', '40', $row);
		
	}
	
	public function testUserFuncRendering() {
		$rendererConfig = Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfigFactory::getRendererConfiguration($this->configurationBuilderMock);
		$rendererClass =  $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Renderer_Strategy_DefaultCellRenderingStrategy');
		
		$this->cellRenderer = $this->getMock($rendererClass,array('renderWithUserFunc'),array($rendererConfig));
		$this->cellRenderer->expects($this->once())->method('renderWithUserFunc');
		
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		$row->addCell('field1', 'val1');
		$row->addCell('field2', 'val2');
		$row->addCell('field3', 'val3');
		$row->addCell('field4', 'val4');
		
		$cellContent = $this->cellRenderer->renderCell('field3', '50', $row);
	}
	*/
	
	
	
	public function testCreateArrayDataFieldSet() {
		$array = array('field1' => 'test1',
					   'field2' => array ('value1', 'value2', 'value3'),
					   'field3' => 'test3',
						);
						
	    $accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Renderer_Default_DefaultCellRenderingStrategy');
		$defaultCellRenderer = new $accessibleClassName($this->getRendererConfiguration());
		
		$outArray = $defaultCellRenderer->_call('createArrayDataFieldSet', $array);
		$this->assertEquals($outArray[1]['field2'], 'value2');
		$this->assertEquals($outArray[1]['field3'], 'test3');
	}
	
	
}

?>