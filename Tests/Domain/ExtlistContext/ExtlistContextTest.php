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
 * Testcase for extlist context class.
 *
 * TODO write tests for all methods and refactor current test to mock tested methods from the same class to get smaller tests.
 *
 * @author Michael Knoll <knoll@punkt.de>
 * @package ExtlistContext
 * @see Tx_PtExtlist_ExtlistContext_ExtlistContext
 */
class Tx_PtExtlist_Tests_ExtlistContext_ExtlistContextTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /** @test */
    public function setFilterValueSetsFilterValueAsExpected()
    {
        $filterValue = 'testFilterValue';
        $filterboxFilterIdentifier = 'testfilterbox.testfilter';

        $filterMock = $this->getFilterMock($filterValue);

        $filterBoxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', array('getFilterByFullFiltername'), array(), '', false);
        $filterBoxCollectionMock->expects($this->once())->method('getFilterByFullFiltername')->with($filterboxFilterIdentifier)->will($this->returnValue($filterMock));

        $dataBackendMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend', array('getFilterBoxCollection'), array(), '', false);
        $dataBackendMock
                ->expects($this->once())
                ->method('getFilterBoxCollection')
                ->will($this->returnValue($filterBoxCollectionMock)
        );

        $extlistContext = new Tx_PtExtlist_ExtlistContext_ExtlistContext();
        $extlistContext->_injectDataBackend($dataBackendMock);

        $extlistContext->setFilterValue('testfilterbox', 'testfilter', $filterValue, false);
    }



    /** @test */
    public function setFilterValueSetsFilterValueAsExpectedRebuildsListCacheIfParameterIsSet()
    {
        $filterValue = 'testFilterValue';

        // Since we tested most of this in the setFilterValueSetsFilterValueAsExpected test, we only check, whether cache is rebuild
        $extlistContextMock = $this->getMock('Tx_PtExtlist_ExtlistContext_ExtlistContext', array('getFilterByFullFiltername', 'getList'), array(), '', false);
        $extlistContextMock->expects($this->once())->method('getFilterByFullFiltername')->will($this->returnValue($this->getFilterMock($filterValue)));
        $extlistContextMock->expects($this->once())->method('getList')->with(true);

        $extlistContextMock->setFilterValue('testfilterbox', 'testfilter', $filterValue);
    }



    /** @test */
    public function setSortingColumnSetsSortingColumnAsExpected()
    {
        $sortingDirection = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC;
        $columnIdentifier = 'columnIdentifier';

        $headerColumnMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_SortingObserverInterface', array('setSorting', 'registerSorter', 'getSortingStateCollection', 'resetSorting', 'resetToDefaultSorting'), array(), '', false);
        $headerColumnMock->expects($this->once())->method('setSorting')->with($sortingDirection);

        $headerMock = $this->getMock('Tx_PtExtlist_Domain_Model_List_Header_ListHeader', array('hasItem', 'getHeaderColumn'), array(), '', false);
        $headerMock->expects($this->any())->method('hasItem')->with($columnIdentifier)->will($this->returnValue(true));
        $headerMock->expects($this->any())->method('getHeaderColumn')->with($columnIdentifier)->will($this->returnValue($headerColumnMock));

        $listMock = $this->getMock('Tx_PtExtlist_Domain_Model_List_List', array('getListHeader'), array(), '', false);
        $listMock->expects($this->any())->method('getListHeader')->will($this->returnValue($headerMock));

        $sorterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_Sorter', array('removeAllSortingObservers', 'registerSortingObserver'), array(), '', false);
        $sorterMock->expects($this->once())->method('removeAllSortingObservers');

        $dataBackendMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend', array('getSorter'), array(), '', false);
        $dataBackendMock->expects($this->any())->method('getSorter')->will($this->returnValue($sorterMock));

        $extlistContextMock = $this->getMock('Tx_PtExtlist_ExtlistContext_ExtlistContext', array('getList'), array(), '', false);
        $extlistContextMock->expects($this->any())->method('getList')->will($this->returnValue($listMock)); /* @var Tx_PtExtlist_ExtlistContext_ExtlistContext $extlistContextMock */

        $extlistContextMock->_injectDataBackend($dataBackendMock);
        $extlistContextMock->setSortingColumn($columnIdentifier, $sortingDirection, false);

        // TODO write tests to check whether list cache is rebuild
    }



    private function getFilterMock($filterValue='')
    {
        $filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter', array('setFilterValue', 'initGenericFilterByTSConfig', 'init'), array(), '', false);
        $filterMock->expects($this->once())->method('setFilterValue')->with($filterValue);
        $filterMock->expects($this->any())->method('initGenericFilterByTSConfig');
        $filterMock->expects($this->any())->method('init');
        return $filterMock;
    }
}
