<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de
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

class  Tx_PtExtlist_Test_Domain_DataBackend_DataSource_AbstractDataSource_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $mockObject;
	
	public function setup() {
		$this->mockObject = new Tx_PtExtlist_Tests_Domain_DataBackend_DataSource_DataSourceMock();
	}
	
	public function testRegisterObserver() {
		$this->assertTrue(method_exists($this->mockObject,'registerObserver'));
	}
	
	public function testNotify() {
		$pager = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager',array('updateItemCount'));
		$pager->expects($this->once())
				->method('updateItemCount')
				->with(111);
		
		$this->mockObject->registerObserver($pager);
		
		$this->mockObject->update(111);
	}
	
}

?>