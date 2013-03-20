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
 * Testcase for list factory
 * 
 * @package Tests
 * @subpackage Domain\Model\List
 * @author Michael Knoll 
 * @author Christoph Ehscheidt 
 */
class Tx_PtExtlist_Tests_Domain_Model_List_ListFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setUp() {
		$this->initDefaultConfigurationBuilderMock();
	}

	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_List_ListFactory'));
	}


	/**
	 * @test
	 */
	public function createList() {
		$overwriteSettings['listConfig']['test']['useIterationListData'] = 0;
		$this->initDefaultConfigurationBuilderMock($overwriteSettings);

		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		$listHeader = new Tx_PtExtlist_Domain_Model_List_Header_ListHeader($this->configurationBuilderMock->getListIdentifier());
		
		$backendMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_DummyDataBackend', array('getListData','getListHeader'), array($this->configurationBuilderMock));
		$backendMock->expects($this->any())
		    ->method('getListData')
		    ->will($this->returnValue($listData));
		$backendMock->expects($this->any())
		    ->method('getListHeader')
		    ->will($this->returnValue($listHeader));
		    
		$list = Tx_PtExtlist_Domain_Model_List_ListFactory::createList($backendMock, $this->configurationBuilderMock);
		
		$this->assertEquals($listData, $list->getListData());
		$this->assertNotNull($list->getListHeader());
	}




	/**
	 * @test
	 */
	public function createIterationList() {
		$overwriteSettings['listConfig']['test']['useIterationListData'] = 1;
		$this->initDefaultConfigurationBuilderMock($overwriteSettings);

		$iterationListData = new Tx_PtExtlist_Domain_Model_List_IterationListData();
		$listHeader = new Tx_PtExtlist_Domain_Model_List_Header_ListHeader($this->configurationBuilderMock->getListIdentifier());
		$aggregateListData = new Tx_PtExtlist_Domain_Model_List_ListData();

		$backendMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_DummyDataBackend', array('getIterationListData','getListHeader', 'getAggregateListData'), array($this->configurationBuilderMock));
		$backendMock->expects($this->any())
			->method('getIterationListData')
			->will($this->returnValue($iterationListData));
		$backendMock->expects($this->any())
			->method('getListHeader')
			->will($this->returnValue($listHeader));
		$backendMock->expects($this->any())
			->method('getAggregateListData')
			->will($this->returnValue($aggregateListData));

		$list = Tx_PtExtlist_Domain_Model_List_ListFactory::createList($backendMock, $this->configurationBuilderMock);

		$this->assertEquals($iterationListData, $list->getIterationListData());
		$this->assertNotNull($list->getListHeader());
	}
}

?>