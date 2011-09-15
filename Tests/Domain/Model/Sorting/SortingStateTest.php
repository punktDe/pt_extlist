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
 * Testcase for sorting state
 *
 * @package pt_extlist
 * @subpackage Tests\Domain\Model\Sorting
 * @author Michael Knoll
 */
class Tx_PtExtlist_Tests_Domain_Model_Sorting_SortingStateTest extends Tx_PtExtlist_Tests_BaseTestcase {
     
	/** @test */
	public function classExists() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Sorting_SortingState'));
	}



    /** @test */
    public function getInstanceBySessionArrayReturnsCorrectSortingState() {
        $fieldName = 'testFieldName';
        $sortingDirection = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC;
        $dummySessionArray = array('fieldName' => $fieldName, 'direction' => $sortingDirection);

        $fieldConfigurationMock = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array(), array(), '', FALSE);
        $fieldsConfigurationMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection', array('getFieldConfigByIdentifier'), array(), '', FALSE);
        $fieldsConfigurationMock->expects($this->once())->method('getFieldConfigByIdentifier')->will($this->returnValue($fieldConfigurationMock));
        $configurationBuilderMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder',array('buildFieldsConfiguration'), array(), '', FALSE);
        $configurationBuilderMock->expects($this->once())->method('buildFieldsConfiguration')->will($this->returnValue($fieldsConfigurationMock));

        $sortingState = Tx_PtExtlist_Domain_Model_Sorting_SortingState::getInstanceBySessionArray($configurationBuilderMock, $dummySessionArray);

        $this->assertEquals($sortingState->getField(), $fieldConfigurationMock);
        $this->assertEquals($sortingState->getDirection(), $sortingDirection);
    }



    /** @test */
    public function getSessionPersistableArrayReturnsCorrectArray() {
        $sortingDirection = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC;
        $fieldIdentifier = 'testFieldIdentifier';
        $fieldConfigurationMock = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array('getIdentifier'), array(), '', FALSE);
        $fieldConfigurationMock->expects($this->once())->method('getIdentifier')->will($this->returnValue($fieldIdentifier));
        $sortingState = new Tx_PtExtlist_Domain_Model_Sorting_SortingState($fieldConfigurationMock, $sortingDirection);
        $sessionPersistedArray = $sortingState->getSessionPersistableArray();
        $this->assertEquals($sessionPersistedArray['fieldName'], $fieldIdentifier);
        $this->assertEquals($sessionPersistedArray['direction'], $sortingDirection);
    }



    /** @test */
    public function getFieldAndGetDirectionReturnsCorrectFieldForSortingState() {
        $fieldConfigurationMock = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array('getIdentifier'), array(), '', FALSE);
        $sortingDirection = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC;
        $sortingState = new Tx_PtExtlist_Domain_Model_Sorting_SortingState($fieldConfigurationMock, $sortingDirection);
        $this->assertEquals($sortingState->getDirection(), $sortingDirection);
        $this->assertEquals($sortingState->getField(), $fieldConfigurationMock);
    }



    /** @test */
    public function getSortingQueryReturnsCorrectQueryObjectForSortingState() {
        $fieldConfigurationMock = $this->getMock(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig, array('getIdentifier'), array(), '', FALSE);
        $fieldConfigurationMock->expects($this->any())->method('getIdentifier')->will($this->returnValue('test'));
        $sortingDirection = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC;
        $sortingState = new Tx_PtExtlist_Domain_Model_Sorting_SortingState($fieldConfigurationMock, $sortingDirection);
        $sortingQuery = $sortingState->getSortingQuery();
        $this->assertEquals($sortingQuery->getSortings(), array('test' => Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC));
    }

}
?>