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
 * Class implements testcase for list configuration
 *
 * @package Tests
 * @subpackage Domain\Configuration\List
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_Configuration_List_ListConfig_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * Holds an instance of list configuration
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_List_ListConfig
	 */
	protected $listConfiguration;
	
	
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
		$this->listConfiguration = $this->configurationBuilderMock->buildListConfiguration();
	}
		
	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Configuration_List_ListConfig'));
	}
	
	public function testGetHeaderPartial() {
		$this->assertEquals($this->listConfiguration->getHeaderPartial(), 'List/ListHeader');
	}

	public function testGetBodyPartial() {
		$this->assertEquals($this->listConfiguration->getBodyPartial(), 'List/ListBody');
	}
	
	public function testGetAggregateRowsPartial() {
		$this->assertEquals($this->listConfiguration->getAggregateRowsPartial(), 'List/AggregateRows');
	}

	/**
	 * @test
	 */
	public function getUseIterationListData() {
		$this->assertTrue($this->listConfiguration->getUseIterationListData());
	}
	
}
 
?>