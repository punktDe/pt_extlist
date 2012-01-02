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
 * Testcase for default renderer
 * 
 * @author Michael Knoll 
 * @package Tests
 * @subpackage Domain\Renderer\Default
 */
class Tx_PtExtlist_Tests_Domain_Renderer_Default_RendererTest extends Tx_PtExtlist_Tests_BaseTestcase {
	
	/**
	 * Holds an instance of the renderer to be tested
	 * 
	 * @var Tx_PtExtlist_Domain_Renderer_ConfigurableRendererInterface
	 */
	protected $renderer;
	
	
	
    /**
     * Set up testcase
     */	
	public function setUp() {
		$this->initDefaultConfigurationBuilderMock();
		$rendererConfiguration = new Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig($this->configurationBuilderMock, array('rendererClassName' => 'Tx_PtExtlist_Domain_Renderer_Default_Renderer', 'enabled' => '1'));
		$this->renderer = Tx_PtExtlist_Domain_Renderer_RendererFactory::getRenderer($rendererConfiguration);
	}
	
	
	
	/** @test */
	public function renderListReturnsRenderedListForGivenConfiguration() {
		
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		$row->createAndAddCell('val1', 'field1');
		$row->createAndAddCell('val2', 'field2');
		$row->createAndAddCell('val3', 'field3');
		$row->createAndAddCell('val4', 'field4');
		$listData->addRow($row);
		
		$renderedList = $this->renderer->renderList($listData);
		
		$this->assertTrue(is_a($renderedList, 'Tx_PtExtlist_Domain_Model_List_ListData'));
		
		$this->assertEquals((string)$renderedList->getItemByIndex(0)->getCell('column1'),'val1');
		$this->assertEquals((string)$renderedList->getItemByIndex(0)->getCell('column4'),'val4');
	}
	
	
	
	/** @test */
	public function renderCaptionRendersCaptionForGivenConfiguration() {
		$listHeader = Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory::createInstance($this->configurationBuilderMock);
		
		$captions = $this->renderer->renderCaptions($listHeader);
		
		$this->assertTrue(is_a($captions, 'Tx_PtExtlist_Domain_Model_List_Row'));
		
		$this->assertEquals((string)$captions->getItemById('column1'), 'Column 1');
		$this->assertEquals((string)$captions->getItemById('column4'), 'Column 4');
	}
	
}

?>