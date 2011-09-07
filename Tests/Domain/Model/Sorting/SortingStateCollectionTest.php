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
 * Testcase for sorting state collection
 *
 * @package pt_extlist
 * @subpackage Tests\Domain\Model\Sorting
 * @author Michael Knoll
 */
class Tx_PtExtlist_Tests_Domain_Model_Sorting_SortingStateCollectionTest extends Tx_PtExtlist_Tests_BaseTestcase {
     
	/** @test */
	public function classExists() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection'));
	}



    /** @test */
    public function getIstanceBySessionArrayReturnsCorrectSortingStateCollection() {
        $dummyArray = array(
            array('fieldName' => 'test1', 'direction' => Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC),
            array('fieldName' => 'test2', 'direction' => Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC)
        );
        
        $fieldConfigurationMock1 = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array('getIdentifier'), array(), '', FALSE);
        $fieldConfigurationMock1->expects($this->any())->method('getIdentifier')->will($this->returnValue('field1'));
        $fieldConfigurationMock2 = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array('getIdentifier'), array(), '', FALSE);
        $fieldConfigurationMock2->expects($this->any())->method('getIdentifier')->will($this->returnValue('field2'));
        $fieldsConfigurationMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection', array('getFieldConfigByIdentifier'), array(), '', FALSE);
        $fieldsConfigurationMock->expects($this->at(0))->method('getFieldConfigByIdentifier')->will($this->returnValue($fieldConfigurationMock1));
        $fieldsConfigurationMock->expects($this->at(1))->method('getFieldConfigByIdentifier')->will($this->returnValue($fieldConfigurationMock2));
        $configurationBuilderMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder',array('buildFieldsConfiguration'), array(), '', FALSE);
        $configurationBuilderMock->expects($this->any())->method('buildFieldsConfiguration')->will($this->returnValue($fieldsConfigurationMock));

        $sortingStateCollection = Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection::getIstanceBySessionArray($configurationBuilderMock, $dummyArray);

        $this->assertTrue(is_a($sortingStateCollection, 'Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection'));
        $this->assertEquals($sortingStateCollection->count(), 2);
    }



    /** @test */
    public function addSortingStateAddsSortingStateToCollection() {
        $sortingStateCollectionMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection'), array('dummy'), array(), '', FALSE);
        $fieldConfigurationMock = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array('getIdentifier'), array(), '', FALSE);
        $fieldConfigurationMock->expects($this->any())->method('getIdentifier')->will($this->returnValue('field1'));
        $sortingStateMock = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_SortingState', array('getField', 'getDirection'), array(), '', FALSE);
        $sortingStateMock->expects($this->any())->method('getField')->will($this->returnValue($fieldConfigurationMock));
        $sortingStateMock->expects($this->any())->method('getDirection')->will($this->returnValue(Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC));

        $sortingStateCollectionMock->addSortingState($sortingStateMock);

        $sortingStatesArray = $sortingStateCollectionMock->_get('itemsArr');
        $this->assertEquals($sortingStatesArray[0], $sortingStateMock);
    }



    /** @test */
    public function addSortingByFieldAndDirectionAddsCorrectSortingState() {
        $sortingStateCollectionMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection'), array('dummy'), array(), '', FALSE);
        $fieldConfigurationMock = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array('getIdentifier'), array(), '', FALSE);
        $fieldConfigurationMock->expects($this->any())->method('getIdentifier')->will($this->returnValue('field1'));
        $sortingDirection = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC;

        $sortingStateCollectionMock->addSortingByFieldAndDirection($fieldConfigurationMock, $sortingDirection);

        $sortingStatesArray = $sortingStateCollectionMock->_get('itemsArr');

        $this->assertEquals($sortingStatesArray[0]->getField(), $fieldConfigurationMock);
        $this->assertEquals($sortingStatesArray[0]->getDirection(), $sortingDirection);
    }



    /** @test */
    public function getSortedFieldsReturnsCorrectArrayOfSortedFields() {
        $fieldConfigurationMock1 = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array('getIdentifier'), array(), '', FALSE);
        $fieldConfigurationMock2 = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array('getIdentifier'), array(), '', FALSE);
        $sortingStateMock1 = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_SortingState', array('getField', 'getDirection'), array(), '', FALSE);
        $sortingStateMock1->expects($this->any())->method('getField')->will($this->returnValue($fieldConfigurationMock1));
        $sortingStateMock1->expects($this->any())->method('getDirection')->will($this->returnValue(1));
        $sortingStateMock2 = $this->getMock('Tx_PtExtlist_Domain_Model_Sorting_SortingState', array('getField', 'getDirection'), array(), '', FALSE);
        $sortingStateMock2->expects($this->any())->method('getField')->will($this->returnValue($fieldConfigurationMock2));
        $sortingStateMock2->expects($this->any())->method('getDirection')->will($this->returnValue(2));
        $sortingStateCollectionMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection'), array('dummy'), array(), '', FALSE);
        $sortingStateCollectionMock->_set('itemsArr', array('test1' => $sortingStateMock1, 'test2' => $sortingStateMock2));

        $sortedFields = $sortingStateCollectionMock->getSortedFields();

        $this->assertEquals($sortedFields, array($fieldConfigurationMock1, $fieldConfigurationMock2));
    }



    /** @test */
    public function getSortingsQueryReturnsCorrectQueryObjectForSortings() {
        $fieldConfigurationMock1 = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array('getIdentifier'), array(), '', FALSE);
        $fieldConfigurationMock1->expects($this->any())->method('getIdentifier')->will($this->returnValue('test1'));
        $sortingDirection1 = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC;
        $sortingState1 = new Tx_PtExtlist_Domain_Model_Sorting_SortingState($fieldConfigurationMock1, $sortingDirection1);

        $fieldConfigurationMock2 = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array('getIdentifier'), array(), '', FALSE);
        $fieldConfigurationMock2->expects($this->any())->method('getIdentifier')->will($this->returnValue('test2'));
        $sortingDirection2 = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC;
        $sortingState2 = new Tx_PtExtlist_Domain_Model_Sorting_SortingState($fieldConfigurationMock2, $sortingDirection2);

        $sortingStateCollection = new Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection();
        $sortingStateCollection->addSortingState($sortingState1);
        $sortingStateCollection->addSortingState($sortingState2);

        $sortingsQuery = $sortingStateCollection->getSortingsQuery();

        $this->assertEquals($sortingsQuery->getSortings(), array('test1' => $sortingDirection1, 'test2' => $sortingDirection2));
    }
	
}
?>