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
 * Class implements testcase for configuration for pager
 *
 * @package Tests
 * @subpackage Domain\Configuration\Pager
 * @author Michael Knoll 
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Pager_PagerConfig_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * Holds an instance of pager configuration
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration
	 */
	protected $pagerConfiguration;
	
	
	
	public function setup() {
		
		$this->initDefaultConfigurationBuilderMock();
		$pagerSettings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');
		$pagerSettings = $pagerSettings['pagerConfigs']['default'];
		$pagerSettings['itemsPerPage'] = 10;
		$this->pagerConfiguration = Tx_PtExtlist_Domain_Configuration_Pager_PagerConfigFactory::getInstance($this->configurationBuilderMock, 'default', $pagerSettings);
	}
		
	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig'));
	}
	
	

	public function testGetPagerClassName() {
		$this->assertEquals($this->pagerConfiguration->getPagerClassName(), 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager');
	}
	
	
	
	public function testGetEnabled() {
		$this->assertTrue($this->pagerConfiguration->getEnabled());
	}
	
	public function testGetPagerIdentifier() {
		$this->assertEquals('default', $this->pagerConfiguration->getPagerIdentifier());
	}
	
	public function testGetTemplatePath() {
		$this->assertNotNull($this->pagerConfiguration->getTemplatePath());
	}
	
	public function testGetItemsPerPage() {
		$this->assertEquals(10, $this->pagerConfiguration->getItemsPerPage());
	}	
}
?>