<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Michael Knoll <knoll@punkt.de>, punkt.de GmbH
 *
 *
 *  All rights reserved
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
 *
 *
 * @author Michael Knoll <knoll@punkt.de>
 * @package
 * @subpackage
 * @see Tx_PtExtlist_ExtlistContext_ExtlistContext
 */
class Tx_PtExtlist_Tests_Domain_ExtlistContext_ExtlistContextTest extends Tx_PtExtlist_Tests_BaseTestcase {

	/** @test */
	public function setFilterValueSetsFilterValueAsExpected() {
		$filterValue = 'testFilterValue';
		$filterboxFilterIdentifier = 'testfilterbox.testfilter';

		$filterMock = $this->getFilterMock($filterValue);

		$filterBoxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', array('getFilterByFullFiltername'), array(), '', FALSE);
		$filterBoxCollectionMock->expects($this->once())->method('getFilterByFullFiltername')->with($filterboxFilterIdentifier)->will($this->returnValue($filterMock));

		$dataBackendMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend', array('getFilterBoxCollection'), array(), '', FALSE);
		$dataBackendMock
				->expects($this->once())
				->method('getFilterBoxCollection')
				->will($this->returnValue($filterBoxCollectionMock)
		);

		$extlistContext = new Tx_PtExtlist_ExtlistContext_ExtlistContext();
		$extlistContext->_injectDataBackend($dataBackendMock);

		$extlistContext->setFilterValue('testfilterbox', 'testfilter', $filterValue, FALSE);
	}



	/** @test */
	public function setFilterValueSetsFilterValueAsExpectedRebuildsListCacheIfParameterIsSet() {
		$filterValue = 'testFilterValue';

		// Since we tested most of this in the setFilterValueSetsFilterValueAsExpected test, we only check, whether cache is rebuild
		$extlistContextMock = $this->getMock('Tx_PtExtlist_ExtlistContext_ExtlistContext', array('getFilterByFullFiltername', 'getList'), array(), '', FALSE);
		$extlistContextMock->expects($this->once())->method('getFilterByFullFiltername')->will($this->returnValue($this->getFilterMock($filterValue)));
		$extlistContextMock->expects($this->once())->method('getList')->with(TRUE);

		$extlistContextMock->setFilterValue('testfilterbox', 'testfilter', $filterValue);
	}



	/** @test */
	public function setSortingColumnSetsSortingColumnAsExpected() {
		// TODO test me!
	}



	private function getFilterMock($filterValue='') {
		$filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter', array('setFilterValue', 'initGenericFilterByTSConfig', 'init'), array(), '', FALSE);
		$filterMock->expects($this->once())->method('setFilterValue')->with($filterValue);
		$filterMock->expects($this->any())->method('initGenericFilterByTSConfig');
		$filterMock->expects($this->any())->method('init');
		return $filterMock;
	}

}