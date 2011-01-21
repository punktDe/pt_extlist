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
 * Testcase for render chain
 *
 * @package Tests
 * @subpackage Domain\Renderer
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_Renderer_RendererChainTest extends Tx_PtExtlist_Tests_BaseTestcase {
	
	/**
	 * Holds an instance of renderer chain to be tested
	 *
	 * @var Tx_PtExtlist_Domain_Renderer_RendererChain
	 */
	protected $fixture;
	
	
	
	/**
	 * Holds an instance of a renderer chain configuration
	 */
	protected $rendererChainConfigurationMock;
	
	
	
	/**
	 * Sets up testcase and fixtures etc.
	 */
	public function setUp() {
		$this->rendererChainConfigurationMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfig', array(), array(), '', FALSE);
		$this->rendererChainConfigurationMock->expects($this->any())->method('isEnabled')->will($this->returnValue(true));
		$this->fixture = new Tx_PtExtlist_Domain_Renderer_RendererChain($this->rendererChainConfigurationMock);
	}
	
	

	/** @test */
	public function setupTest() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Renderer_RendererChain'));
		$rendererChain = new Tx_PtExtlist_Domain_Renderer_RendererChain($this->rendererChainConfigurationMock);
		$this->assertTrue(is_a($rendererChain, 'Tx_PtExtlist_Domain_Renderer_RendererInterface'));
	}
	
	
	
	/** @test */
	public function addRendererAddsRendererToListOfRenderers() {
		$rendererToBeAdded = new Tx_PtExtlist_Tests_Domain_Renderer_DummyRenderer();
		$this->fixture->addRenderer(new Tx_PtExtlist_Tests_Domain_Renderer_DummyRenderer());
		$this->assertTrue(in_array($rendererToBeAdded, $this->fixture->getRenderers()));
	}
	
	
	
	/** @test */
	public function renderListCallsRenderListInAddedRenderers() {
		$listDataDummy = $this->getMock('Tx_PtExtlist_Domain_Model_List_ListData',array(),array(),'', FALSE);
		$firstRendererMock = $this->getMock('Tx_PtExtlist_Tests_Domain_Renderer_DummyRenderer', array('renderList'), array(), '', FALSE);
		$firstRendererMock->expects($this->once())->method('renderList')->with($listDataDummy)->will($this->returnValue($listDataDummy));
		$secondRendererMock = $this->getMock('Tx_PtExtlist_Tests_Domain_Renderer_DummyRenderer', array('renderList'), array(), '', FALSE);
        $secondRendererMock->expects($this->once())->method('renderList')->with($listDataDummy)->will($this->returnValue($listDataDummy));
        
        $rendererChain = new Tx_PtExtlist_Domain_Renderer_RendererChain($this->rendererChainConfigurationMock);
        $rendererChain->addRenderer($firstRendererMock);
        $rendererChain->addRenderer($secondRendererMock);
        
        $rendererChain->renderList($listDataDummy);
	}
	
	
	
	/** @test */
	public function renderCaptionsCallsRenderCaptionsInAddedRenderers() {
		$captionsDummy = $this->getMock('Tx_PtExtlist_Domain_Model_List_Header_ListHeader',array(),array(),'', FALSE);
        $firstRendererMock = $this->getMock('Tx_PtExtlist_Tests_Domain_Renderer_DummyRenderer', array('renderCaptions'), array(), '', FALSE);
        $firstRendererMock->expects($this->once())->method('renderCaptions')->with($captionsDummy)->will($this->returnValue($captionsDummy));
        $secondRendererMock = $this->getMock('Tx_PtExtlist_Tests_Domain_Renderer_DummyRenderer', array('renderCaptions'), array(), '', FALSE);
        $secondRendererMock->expects($this->once())->method('renderCaptions')->with($captionsDummy)->will($this->returnValue($captionsDummy));
        
        $rendererChain = new Tx_PtExtlist_Domain_Renderer_RendererChain($this->rendererChainConfigurationMock);
        $rendererChain->addRenderer($firstRendererMock);
        $rendererChain->addRenderer($secondRendererMock);
        
        $rendererChain->renderCaptions($captionsDummy);
	}
	
	
}

?>