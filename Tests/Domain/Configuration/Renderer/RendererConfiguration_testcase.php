<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de
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

class Tx_PtExtlist_Tests_Domain_Configuration_Renderer_RendererConfiguration_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $settings = array('bla' => 'blub', 'enabled'=>'0', 'showCaptionsInBody' => '1');
	
	protected $config;
	
	public function setup() {
		$this->config = new Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration($this->settings);
	}
	
	public function testGetSettings() {
		
		$this->assertTrue(method_exists($this->config, 'getSettings'));
		
		$settings = $this->config->getSettings();
		$this->assertEquals($this->settings, $settings);
		
	}
	
	public function testGetColumnConfiguration() {
		
		$colConf = new Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection();
		$this->config->setColumnConfigCollection($colConf);
		$this->assertTrue(method_exists($this->config, 'getColumnConfigCollection'));
		$this->assertEquals($this->config->getColumnConfigCollection(), $colConf);
	}
	
	public function testIsEnabled() {
		$this->assertFalse($this->config->isEnabled());
	}
	
	public function testShowCaptionsInBody() {
		$this->assertTrue($this->config->showCaptionsInBody());
	}
	
}

?>