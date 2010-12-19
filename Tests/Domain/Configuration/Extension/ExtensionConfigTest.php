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
 * Class implements testcase for configuration for export
 *
 * @package Tests
 * @subpackage Domain\Configuration\Extension
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Extension_ExtensionConfig_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
		
	public function testPi1Mode() {
		$extensionConfig = $this->getAccessibleMock('Tx_PtExtlist_Domain_Configuration_Extension_ExtensionConfig', array('determinePluginName'), array($this->configurationBuilderMock));
		$extensionConfig->expects($this->once())
    	   ->method('determinePluginName')
    	   ->will($this->returnValue('Pi1'));
    	   
		$extensionConfig->_call('init');
		
		$this->assertTrue($extensionConfig->useSession(), 'UseSession should be true');
		$this->assertFalse($extensionConfig->isCached());
		$this->assertEquals('Pi1',$extensionConfig->getPluginName());
	}
	
	public function testCachedMode() {
		$extensionConfig = $this->getAccessibleMock('Tx_PtExtlist_Domain_Configuration_Extension_ExtensionConfig', array('determinePluginName'), array($this->configurationBuilderMock));
		$extensionConfig->expects($this->once())
    	   ->method('determinePluginName')
    	   ->will($this->returnValue('Cached'));
    	   
		$extensionConfig->_call('init');
		
		$this->assertFalse($extensionConfig->useSession(), 'UseSession should be false');
		$this->assertTrue($extensionConfig->isCached());
		$this->assertEquals('Cached',$extensionConfig->getPluginName());
	}	
}
?>