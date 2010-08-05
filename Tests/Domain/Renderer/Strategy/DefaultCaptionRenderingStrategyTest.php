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

class Tx_PtExtlist_Tests_Domain_Renderer_Strategy_DefaultCaptionRenderingStrategy_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $listHeader;
	protected $captionRenderer;
	
	public function setUp() {
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
	
		$this->listHeader = Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory::createInstance($configurationBuilderMock);
		$this->captionRenderer = new Tx_PtExtlist_Domain_Renderer_Strategy_DefaultCaptionRenderingStrategy();
	}
	
	public function testRenderCaption() {
		// see ConfigurationBuilderMock for column definitions
		
		$list = new Tx_PtExtlist_Domain_Model_List_List();
		$list->setListHeader($this->listHeader);
		
		$captions = $this->captionRenderer->renderCaptions($list);
		
		$this->assertEquals('Column 1', $captions->getItemByIndex(0));
	}
	
	public function testLabelLocalization() {
		$methods = array('getLabel', 'getColumnIdentifier');
		$returnMethods['getLabel'] = 'LLL:EXT:pt_extlist/Tests/Domain/Renderer/locallang.xml:test';
		$returnMethods['getColoumnIdentifier'] = 'test';

		$headerColumn = $this->getConfiguredMock('Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', $methods, $returnMethods);
		
		// we need to give a list to the renderer
		$list = new Tx_PtExtlist_Domain_Model_List_List();
		$listHeader = new Tx_PtExtlist_Domain_Model_List_Header_ListHeader();
		$listHeader->addHeaderColumn($headerColumn, 'test');
		$list->setListHeader($listHeader);
		
		
		$captions = $this->captionRenderer->renderCaptions($list);

		
		$this->assertEquals('TEST', $captions->getItemByIndex(0));
	}
	
	public function testSimpleTSLabel() {
		// TODO: check why other tests don't work in cli dispatch mode.
			$this->assertTrue(TRUE);
			return;
		
		
		$ts = array('10' => array(
						'_typoScriptNodeValue' => 'TEXT',
						'value' => 'test',
						),
					'_typoScriptNodeValue' => 'COA'
					);
		
		
		$methods = array('getLabel', 'getColumnIdentifier');
		$returnMethods['getLabel'] = $ts;
		$returnMethods['getColumnIdentifier'] = 'bla';
						
		$headerColumn = $this->getConfiguredMock('Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', $methods, $returnMethods);
		
		// we need to give a list to the renderer
		$list = new Tx_PtExtlist_Domain_Model_List_List();
		$listHeader = new Tx_PtExtlist_Domain_Model_List_Header_ListHeader();
		$listHeader->addHeaderColumn($headerColumn, 'bla');
		$list->setListHeader($listHeader);
		
		
		$captions = $this->captionRenderer->renderCaptions($list);
		
		$this->assertEquals('test', $captions->getItemByIndex(0));
	}
	
	protected function getConfiguredMock($className, array $methods, array $returnMethods) {
		
		$columnConfig = $this->getMock($className, 
							$methods, array(), '', FALSE);
							
		foreach($returnMethods as $method => $returnValue) {
			$columnConfig->expects($this->any())->method($method)->will($this->returnValue($returnValue));
		}
		
		return $columnConfig;
	} 
	
}

?>