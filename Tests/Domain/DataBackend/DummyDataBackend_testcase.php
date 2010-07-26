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
 * Testcase for pt_list dummy data backend object. 
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Test_Domain_DataBackend_DummyDataBackend_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $dataBackend;
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
  
		$this->dataBackend = new Tx_PtExtlist_Domain_DataBackend_DummyDataBackend($this->configurationBuilderMock);
	}
	 
	public function testPagerUpdate() {
		$pager = $this->getMock('Tx_PtExtlist_Domain_Model_Pager_DefaultPager',array('setItemCount'));
		$pager->expects($this->once())
				->method('setItemCount');
		
		$dataSource = $this->getMock('Tx_PtExtlist_Domain_DataBackend_DataSource_DummyDataSource',array('execute'));
		$dataSource->expects($this->any())
					->method('execute')
					->will($this->returnValue(array(1,2,3,4,5,6,7,8)));

		$mapper = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',array('getMappedListData'));
					
					
		$this->dataBackend->injectPager($pager);
		$this->dataBackend->injectDataSource($dataSource);
		$this->dataBackend->injectDataMapper($mapper);

		$this->dataBackend->getListData();
	}
}

?>