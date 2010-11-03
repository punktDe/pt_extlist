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
 * Testcase for header column class
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_Model_List_Header_HeaderColumn_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	public function testConfigurationAndStateMerge() {
		$columnsConfiguration = $this->configurationBuilderMock->buildColumnsConfiguration();
		
		$sessesionPersistanceManager = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array('persistToSession'));
        $sessesionPersistanceManager->expects($this->once())
            ->method('persistToSession');
		
		
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$headerColumn->injectColumnConfig($columnsConfiguration[20]);
		
		$headerColumn->injectSessionPersistenceManager($sessesionPersistanceManager);
		
		$this->assertEquals(0,$headerColumn->getSortingState());

		$headerColumn->injectSessionData(array('sortingState' => 1));
		$headerColumn->injectGPVars(array('sortingState' => -1));
		
		$headerColumn->init();
		
		$this->assertEquals(-1,$headerColumn->getSortingState());
	}
	
	
	
	public function testGetSortingImage() {
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$this->assertTrue(method_exists($headerColumn, 'getSortingImage'));
	}
	
	
	
	public function testGetSortings() {	
		$sessesionPersistanceManager = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array('persistToSession'));
        $sessesionPersistanceManager->expects($this->any())
            ->method('persistToSession');
		
		
		$columnsConfiguration = $this->configurationBuilderMock->buildColumnsConfiguration();
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$headerColumn->injectColumnConfig($columnsConfiguration[20]);
		$headerColumn->injectSessionPersistenceManager($sessesionPersistanceManager);
		
		$headerColumn->injectSessionData(array('sortingState' => Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC));
		$headerColumn->init();
		$sorting = $headerColumn->getSorting();
		$this->assertEquals(Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC, $sorting['tstamp'], 'Sorting has to be Ascending here');
		
		$headerColumn->injectSessionData(array('sortingState' => Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC));
		$headerColumn->init();
		$sorting = $headerColumn->getSorting();
		$this->assertEquals(Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC, $sorting['tstamp'], 'Sorting has to be descending here');
		
		// test with forced direction
		$headerColumn->injectColumnConfig($columnsConfiguration[30]);
		$headerColumn->injectSessionData(array('sortingState' => Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC));
		$headerColumn->init();
		$sorting = $headerColumn->getSorting();
		$this->assertEquals(Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC, $sorting['tstamp'], 'Sorting for tstamp is forced to desc, but is ascending here');
	}
	
	
	public function testGetSortingsWithoutSortingDefinitions() {	
		$sessesionPersistanceManager = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array('persistToSession'));
        $sessesionPersistanceManager->expects($this->any())
            ->method('persistToSession');
		
		
		$columnsConfiguration = $this->configurationBuilderMock->buildColumnsConfiguration();
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$headerColumn->injectColumnConfig($columnsConfiguration[40]);
		$headerColumn->injectSessionPersistenceManager($sessesionPersistanceManager);
		
		$headerColumn->injectGPVars(array('sortingState' => Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC));
		$headerColumn->init();
		$sorting = $headerColumn->getSorting();
		$this->assertEquals(Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC, $sorting['field4'], 'Sorting has to be Ascending here');
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
		$sessesionPersistanceManager = $this->getMock('Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager', array('persistToSession'));
        $sessesionPersistanceManager->expects($this->any())
            ->method('persistToSession');
		
		$columnsConfiguration = $this->configurationBuilderMock->buildColumnsConfiguration();
		
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		$headerColumn->injectColumnConfig($columnsConfiguration[20]);
		
		$headerColumn->injectSessionPersistenceManager($sessesionPersistanceManager);
		$headerColumn->init();
		
		$queryObject = $headerColumn->getSortingQuery();
		$this->assertTrue(is_a($queryObject, 'Tx_PtExtlist_Domain_QueryObject_Query'));
	}
	
	
	
}
?>