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
 * Testcase for header column class
 * 
 * @author Daniel Lienert 
 * @package Tests
 * @subpackage Model\List\Header
 */
class Tx_PtExtlist_Tests_Domain_Model_List_Header_HeaderColumn_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}



	public function testConfigurationAndStateMerge() {
		$columnsConfiguration = $this->configurationBuilderMock->buildColumnsConfiguration();
        $sorterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_Sorter', array('getSortingStateCollection'), array(), '', FALSE);
        $sorterMock->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue(null));

		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$headerColumn->injectColumnConfig($columnsConfiguration[20]);
        $headerColumn->registerSorter($sorterMock);

		$this->assertEquals(0,$headerColumn->getSortingDirection());

		$headerColumn->injectGPVars(array('sortingState' => -1));

		$headerColumn->init();

		$this->assertEquals(-1,$headerColumn->getSortingDirection());
	}
	
	
	
	public function testGetSortingImage() {
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$this->assertTrue(method_exists($headerColumn, 'getSortingImage'));
	}
	
	
	
	public function testGetSortings() {	
		$columnsConfiguration = $this->configurationBuilderMock->buildColumnsConfiguration();
        $sorterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_Sorter', array('getSortingStateCollection'), array(), '', FALSE);
        $sorterMock->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue(null));
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$headerColumn->injectColumnConfig($columnsConfiguration[20]);
        $headerColumn->injectGPVars(array('sortingState' => 1));
        $headerColumn->registerSorter($sorterMock);

		$headerColumn->init();
		$sorting = $headerColumn->getSortingStateCollection();
		$this->assertEquals(Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC, $sorting[0]->getDirection(), 'Sorting has to be Ascending here');
		
		$headerColumn->init();
		$sorting = $headerColumn->getSortingStateCollection();
		$this->assertEquals(Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC, $sorting[1]->getDirection(), 'Sorting has to be descending here');
		
		// test with forced direction
		$headerColumn->injectColumnConfig($columnsConfiguration[30]);
        $headerColumn->injectGPVars(array('sortingState' => -1));
		$headerColumn->init();
		$sorting = $headerColumn->getSortingStateCollection();
		$this->assertEquals(Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC, $sorting[1]->getDirection(), 'Sorting for tstamp is forced to desc, but is ascending here');
	}
	


	public function testGetSortingsWithoutSortingDefinitions() {	
		$columnsConfiguration = $this->configurationBuilderMock->buildColumnsConfiguration();
        $sorterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_Sorter', array('getSortingStateCollection'), array(), '', FALSE);
        $sorterMock->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue(null));
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$headerColumn->injectColumnConfig($columnsConfiguration[40]);
        $headerColumn->registerSorter($sorterMock);

		$headerColumn->injectGPVars(array('sortingState' => Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC));
		$headerColumn->init();
		$sorting = $headerColumn->getSortingStateCollection();
		$this->assertEquals(Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC, $sorting[0]->getDirection(), 'Sorting has to be Ascending here');
	}



	public function testIsSortable() {
		$columnsConfiguration = $this->configurationBuilderMock->buildColumnsConfiguration();
		
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$headerColumn->injectColumnConfig($columnsConfiguration[10]);
		
		$this->assertFalse($headerColumn->isSortable());
		
		$headerColumn->injectColumnConfig($columnsConfiguration[20]);
		
		$this->assertTrue($headerColumn->isSortable());
		
	}



	public function testGetSortingQuery() {
		$columnsConfiguration = $this->configurationBuilderMock->buildColumnsConfiguration();
        $sorterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_Sorter', array('getSortingStateCollection'), array(), '', FALSE);
        $sorterMock->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue(null));

		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$headerColumn->injectColumnConfig($columnsConfiguration[20]);
        $headerColumn->registerSorter($sorterMock);
		$headerColumn->init();
		
		$queryObject = $headerColumn->getSortingStateCollection();
		$this->assertTrue(is_a($queryObject, 'Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection'));
	}

}
?>