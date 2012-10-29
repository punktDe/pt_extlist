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
 * Class implements testcase for list default configuration
 *
 * @package Tests
 * @subpackage Domain\Configuration\List
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_Configuration_List_ListDefaultConfig_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * Holds an instance of list default configuration
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_List_ListDefaultConfig
	 */
	protected $listDefaultConfiguration;
	
	
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
		$this->listDefaultConfiguration = $this->configurationBuilderMock->buildListDefaultConfig();
	}
		
	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Configuration_List_ListDefaultConfig'));
	}


	/**
	 * @test
	 */
	public function getSortingColumn() {
		$this->assertEquals($this->listDefaultConfiguration->getSortingColumn(), 'column3');
	}


	/**
	 * @test
	 */
	public function getSortingColumnDirectionASC() {
		$this->assertEquals($this->listDefaultConfiguration->getSortingDirection(), 1);
	}

	/**
	 * @test
	 */
	public function getSortingColumnDirectionDESC() {
		
		$overwriteSettings['listConfig']['test']['default']['sortingColumn'] = 'column3 DESC';
		
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance(NULL,$overwriteSettings);
		
		$listDefaultConfiguration = $configurationBuilderMock->buildListDefaultConfig();
		$this->assertEquals($listDefaultConfiguration->getSortingDirection(), -1);
		$this->assertEquals($listDefaultConfiguration->getSortingColumn(), 'column3');
	}
}
 
?>