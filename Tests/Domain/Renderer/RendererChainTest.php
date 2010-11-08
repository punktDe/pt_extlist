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
 * Testcase for render chain
 *
 * @package pt_extlist
 * @subpackage Tests\Domain\Renderer
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Renderer_RendererChainTest extends Tx_PtExtlist_Tests_BaseTestcase {
	
	/**
	 * Holds an instance of renderer chain to be tested
	 *
	 * @var Tx_PtExtlist_Domain_Renderer_RendererChain
	 */
	protected $fixture;
	
	
	
	/**
	 * Sets up testcase and fixtures etc.
	 */
	public function setUp() {
		$this->fixture = new Tx_PtExtlist_Domain_Renderer_RendererChain();
	}
	
	

	/** @test */
	public function setupTest() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Renderer_RendererChain'));
		$rendererChain = new Tx_PtExtlist_Domain_Renderer_RendererChain();
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
        
        $rendererChain = new Tx_PtExtlist_Domain_Renderer_RendererChain();
        $rendererChain->addRenderer($firstRendererMock);
        $rendererChain->addRenderer($secondRendererMock);
        
        $rendererChain->renderList($listDataDummy);
	}
	
	
}



/**
 * Dummy class implementing a renderer
 */
require_once t3lib_extMgm::extPath('pt_extlist') . 'Classes/Domain/Renderer/RendererInterface.php';
class Tx_PtExtlist_Tests_Domain_Renderer_DummyRenderer implements Tx_PtExtlist_Domain_Renderer_RendererInterface {

	/**
	 * @see Tx_PtExtlist_Domain_Renderer_RendererInterface::renderCaptions()
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader
	 * @return Tx_PtExtlist_Domain_Model_List_Row
	 */
	public function renderCaptions(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader) {
		return $listHeader;
	}



	/**
	 * @see Tx_PtExtlist_Domain_Renderer_RendererInterface::renderList()
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $listData
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function renderList(Tx_PtExtlist_Domain_Model_List_ListData $listData) {
		return $listData;
	}
	
}

?>