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

/**
 * Testcase for list factory
 * 
 * @package Test
 * @subpackage Domain\Model\List
 * @author Michael Knoll <knoll@punkt.de>
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Model_List_ListFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setUp() {
		$this->initDefaultConfigurationBuilderMock();
	}

	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_List_ListFactory'));
	}
	
	
	public function testCreateList() {
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		$backendMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_DummyDataBackend', array('getListData'), array($this->configurationBuilderMock));
		$backendMock->expects($this->any())
		    ->method('getListData')
		    ->will($this->returnValue($listData));
		
		$list = Tx_PtExtlist_Domain_Model_List_ListFactory::createList($backendMock, $this->configurationBuilderMock);
		
		$this->assertEquals($listData, $list->getListData());
		$this->assertNotNull($list->getListHeader());
	}
	
}

?>