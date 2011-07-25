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
 * Testcase for default row renderer
 *
 * @package Tests
 * @subpackage Domain\Renderer\Default
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_Renderer_Default_RowRendererTest extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * Sets up testcase
	 */
	public function setUp() {
    	$this->initDefaultConfigurationBuilderMock();
	}

	
	
	/** @test */
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Renderer_Default_RowRenderer'));
    }
    
    
    
    /** @test */
    public function getRendererConfigurationReturnsConfigurationAfterInjection() {
    	$rendererConfigurationMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig', array(), array(),'', FALSE);
    	$rowRenderer = new Tx_PtExtlist_Domain_Renderer_Default_RowRenderer();
    	$rowRenderer->injectRendererConfiguration($rendererConfigurationMock);
    	$this->assertEquals($rendererConfigurationMock, $rowRenderer->getRendererConfiguration());
    }
	
    
    
    /** @test */
    public function renderRowRendersRowForGivenConfiguration() {
    	$row = new Tx_PtExtlist_Domain_Model_List_Row();
        $row->createAndAddCell('val1', 'field1');
        $row->createAndAddCell('val2', 'field2');
        $row->createAndAddCell('val3', 'field3');
        $row->createAndAddCell('val4', 'field4');
        
        $cellRenderer = $this->getMock('Tx_PtExtlist_Domain_Renderer_Default_CellRenderer', array(), array(), '', FALSE);
        $cellRenderer->expects($this->any())->method('renderCell')->will($this->returnValue(new Tx_PtExtlist_Domain_Model_List_Cell('test')));
        $rowRenderer = $this->getRowRenderer();
        $rowRenderer->injectCellRenderer($cellRenderer);
        
        $renderedRow = $rowRenderer->renderRow($row, 1);
        
        foreach($renderedRow as $cell) { /* @var $cell Tx_PtExtlist_Domain_Model_List_Cell */
        	$this->assertEquals($cell->getValue(), 'test');
        }
    } 
    
    
    
    /**
     * Returns a row renderer for testing
     *
     */
    protected function getRowRenderer() {
    	$rendererConfiguration = new Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig($this->configurationBuilderMock, array('rendererClassName' => 'Tx_PtExtlist_Tests_Domain_Renderer_DummyRenderer'));
    	$renderer = new Tx_PtExtlist_Domain_Renderer_Default_RowRenderer();
    	$renderer->injectRendererConfiguration($rendererConfiguration);
    	return $renderer;
    }
    
}

?>  