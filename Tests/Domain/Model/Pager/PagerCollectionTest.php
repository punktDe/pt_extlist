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

class Tx_PtExtlist_Tests_Domain_Model_Pager_PagerCollectionTest extends Tx_PtExtlist_Tests_BaseTestcase {

	public function setUp() {
		$this->initDefaultConfigurationBuilderMock();
	}



	public function testAddPager() {
		$collection = new Tx_PtExtlist_Domain_Model_Pager_PagerCollection($this->configurationBuilderMock);
		
		$pager = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager', array('setCurrentPage'), array(),'',false, false, true);

		$collection->addPager($pager);
	}


	
	/** @test */
	public function setPageByItemIndex() {
		$collection = new Tx_PtExtlist_Domain_Model_Pager_PagerCollection($this->configurationBuilderMock);
		$pager = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager', array('setCurrentPage','getItemCount','getLastPage'), array(),'', false,false);
		$pager->expects($this->any())->method('getItemCount')->will($this->returnValue(1000));
		$pager->expects($this->any())->method('getLastPage')->will($this->returnValue(200));
		$collection->addPager($pager);

		
		$collection->setItemsPerPage(5);
		
		$collection->setPageByRowIndex(0);
		$this->assertEquals(1,$collection->getCurrentPage());
		
		$collection->setPageByRowIndex(4);
		$this->assertEquals(1,$collection->getCurrentPage());
		
		$collection->setPageByRowIndex(5);
		$this->assertEquals(2,$collection->getCurrentPage());
	}



	/** @test */
	public function pagerCollectionReturnsFirstPageIfCurrentPageIsOutOfItemCount() {
		$collection = new Tx_PtExtlist_Domain_Model_Pager_PagerCollection($this->configurationBuilderMock);
		$pager = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager', array('setCurrentPage','getItemCount', 'getLastPage'), array(),'', false,false);
		$pager->expects($this->any())->method('getItemCount')->will($this->returnValue(10));
		$pager->expects($this->any())->method('getLastPage')->will($this->returnValue(2));
		$collection->addPager($pager);

		$collection->injectSessionData(array('page' => 2));
		$collection->setItemsPerPage(5);

		// We check whether we still get correct page, if we are "in bound"
		$this->assertEquals($collection->getCurrentPage(), 2);

		// We check whether pager is reset if we run "out of bound"3
		$collection->setCurrentPage(3);
		$this->assertEquals($collection->getCurrentPage(), 1);
	}

}
?>