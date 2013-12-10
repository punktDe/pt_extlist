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
 * Testscase for renderer factory
 * 
 * @author Michael Knoll 
 * @package pt_extlist
 * @subpackage Tests\Domain\Renderer
 * @see Tx_PtExtlist_Domain_Renderer_RendererFactory
 */
class Tx_PtExtlist_Tests_Domain_Renderer_RendererFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * @var Tx_PtExtlist_Domain_Renderer_RendererFactory
	 */
	protected $rendererFactory;



	public function setUp() {
		$this->rendererFactory = $this->objectManager->get('Tx_PtExtlist_Domain_Renderer_RendererFactory');
	}



	/** @test */
	public function getRendererReturnsRendererForConfiguration() {
		$rendererConfiguration = $this->getMock('Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig', array(), array(), '', FALSE);
		$rendererConfiguration->expects($this->any())->method('getRendererClassName')->will($this->returnValue('Tx_PtExtlist_Tests_Domain_Renderer_DummyRenderer')); /* @var $rendererConfiguration Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig */
		$renderer = $this->rendererFactory->getRenderer($rendererConfiguration);
		
		$this->assertTrue(is_a($renderer, 'Tx_PtExtlist_Domain_Renderer_RendererInterface'));
	}
	
}