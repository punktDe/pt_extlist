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
 * Testcase for Sorter
 *
 * @package pt_extlist
 * @subpackage Tests\Domain\Model\Sorting
 * @author Michael Knoll
 * @see Sorter
 */
class Tx_PtExtlist_Tests_Domain_Model_Sorting_SorterTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /** @test */
    public function classExists()
    {
        $this->assertTrue(class_exists('Sorter'));
    }
    
    
    
    /** @test */
    public function registerSortingObserversAcceptsSortingObserver()
    {
        $sortingObserver = $this->getMock('SortingObserverInterface');
        $sorter = new Sorter();
        $sorter->registerSortingObserver($sortingObserver);
    }
    
    
    
    /** @test */
    public function injectSorterConfigurationAcceptsSorterConfiguration()
    {
        $sortingConfiguration = $this->getMock('Tx_PtExtlist_Domain_Configuration_Sorting_SorterConfig', [], [], '', false);
        $sorter = new Sorter();
        $sorter->_injectSorterConfig($sortingConfiguration);
    }



    /** @test */
    public function getSortingStateCollectionReturnsSortingStatesOfRegisteredObservers()
    {
        $sortingStateMock1 = $this->buildSortingStateMock('test1', 1);
        $sortingStateMock2 = $this->buildSortingStateMock('test2', 1);
        $sortingStateCollectionMock1 = new SortingStateCollection();
        $sortingStateCollectionMock1->addSortingState($sortingStateMock1);
        $sortingStateCollectionMock1->addSortingState($sortingStateMock2);

        $sortingStateMock3 = $this->buildSortingStateMock('test3', 0);
        $sortingStateMock4 = $this->buildSortingStateMock('test4', 0);
        $sortingStateCollectionMock2 = new SortingStateCollection();
        $sortingStateCollectionMock2->addSortingState($sortingStateMock3);
        $sortingStateCollectionMock2->addSortingState($sortingStateMock4);

        $sortingObserverMock1 = $this->getMock('SortingObserverInterface', ['getSortingStateCollection', 'registerSorter', 'resetSorting', 'resetToDefaultSorting'], [], '', false); /* @var $sortingObserverMock SortingObserverInterface */
        $sortingObserverMock1->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue($sortingStateCollectionMock1));

        $sortingObserverMock2 = $this->getMock('SortingObserverInterface', ['getSortingStateCollection', 'registerSorter', 'resetSorting', 'resetToDefaultSorting'], [], '', false); /* @var $sortingObserverMock SortingObserverInterface */
        $sortingObserverMock2->expects($this->any())->method('getSortingStateCollection')->will($this->returnValue($sortingStateCollectionMock2));

        $sorter = new Sorter();
        $sorter->registerSortingObserver($sortingObserverMock1);
        $sorter->registerSortingObserver($sortingObserverMock2);

        $sortingStateCollection = $sorter->getSortingStateCollection();

        $this->assertEquals($sortingStateCollection->count(), 4);
        $this->assertEquals($sortingStateCollection->getItemByIndex(0), $sortingStateMock1);
        $this->assertEquals($sortingStateCollection->getItemByIndex(1), $sortingStateMock2);
        $this->assertEquals($sortingStateCollection->getItemByIndex(2), $sortingStateMock3);
        $this->assertEquals($sortingStateCollection->getItemByIndex(3), $sortingStateMock4);
    }



    /** @test */
    public function resetResetsAllRegisteredSortingObservers()
    {
        $sortingObserverMock1 = $this->getMock('SortingObserverInterface', ['getSortingStateCollection', 'registerSorter', 'resetSorting', 'resetToDefaultSorting'], [], '', false); /* @var $sortingObserverMock SortingObserverInterface */
        $sortingObserverMock1->expects($this->once())->method('resetSorting');
        $sortingObserverMock2 = $this->getMock('SortingObserverInterface', ['getSortingStateCollection', 'registerSorter', 'resetSorting', 'resetToDefaultSorting'], [], '', false); /* @var $sortingObserverMock SortingObserverInterface */
        $sortingObserverMock2->expects($this->once())->method('resetSorting');

        $sorter = new Sorter();
        $sorter->registerSortingObserver($sortingObserverMock1);
        $sorter->registerSortingObserver($sortingObserverMock2);

        $sorter->reset();
    }



    /**
     * Builds a sorting state mock for given field identifier and given sorting direction
     *
     * @param $fieldIdentifier
     * @param $sortingDirection
     * @return SortingState
     */
    protected function buildSortingStateMock($fieldIdentifier, $sortingDirection)
    {
        $fieldConfigMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Data_FieldConfig', ['getIdentifier'], [], '', false);
        $fieldConfigMock->expects($this->any())->method('getIdentifier')->will($this->returnValue($fieldIdentifier));
        $sortingStateMock = $this->getMock('SortingState', [], [], '', false);
        $sortingStateMock->expects($this->any())->method('getField')->will($this->returnValue($fieldConfigMock));
        $sortingStateMock->expects($this->any())->method('getDirection')->will($this->returnValue($sortingDirection));
        return $sortingStateMock;
    }
}
