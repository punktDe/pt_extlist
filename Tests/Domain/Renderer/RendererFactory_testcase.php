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

class Tx_PtExtlist_Tests_Domain_Renderer_RendererFactory_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $rendererConfig;
	
	public function setUp() {
		$this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();		
		$this->rendererConfig = $this->configurationBuilderMock->buildRendererConfiguration();
	}
	
	public function testGetRenderer() {
		
		$renderer = Tx_PtExtlist_Domain_Renderer_RendererFactory::getRenderer($this->rendererConfig);
		
		$this->assertTrue(is_a($renderer, 'Tx_PtExtlist_Domain_Renderer_RendererInterface'));
//		$this->assertTrue(method_exists($renderer, 'render'));
//		$this->assertTrue(method_exists($renderer, 'renderCaptions'));
	}
	
	
}

?>