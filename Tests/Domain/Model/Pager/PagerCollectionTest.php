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

class Tx_PtExtlist_Tests_Domain_Model_Pager_PagerCollectionTest extends Tx_Extbase_BaseTestCase {

	public function setUp() {}
	
	public function testSetCurrentPage() {
		$pager = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager', array('setCurrentPage'), array(),'',false, false, true);
		
		// should be called twice
		$pager->expects($this->any())->method('setCurrentPage');
		
		$collection = new Tx_PtExtlist_Domain_Model_Pager_PagerCollection();
		$collection->addPager($pager);
		
		$collection->setCurrentPage(42);
		
		$this->assertEquals(42, $collection->getCurrentPage());
	}
	
	public function testAddPager() {
		$collection = new Tx_PtExtlist_Domain_Model_Pager_PagerCollection();
		
		$pager = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager', array('setCurrentPage'), array(),'',false, false, true);

		$collection->addPager($pager);
	}
}

?>