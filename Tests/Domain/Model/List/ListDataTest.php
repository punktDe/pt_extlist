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
 * Testcase for list data class
 *
 * @author Daniel Lienert
 * @package Tests
 * @subpackage Model\List
 */
class Tx_PtExtlist_Tests_Domain_Model_List_ListData_testcase extends Tx_Extbase_BaseTestcase {


	/**
	 * Representation of a list as data array
	 *
	 * @var array
	 */
	protected $testData = array(
		'rows' => array(
			0 => array(
				'columns' => array(
					'col0' => array(
						'value' => 'testValue1',
						'cssClass' => 'testCssClass1',
						'rowIndex' => 0,
						'columnIndex' => 0,
						'specialValues' => array('key1' => 'value1')
					),
					'col1' => array(
						'value' => 'testValue2',
						'cssClass' => 'testCssClass2',
						'rowIndex' => 0,
						'columnIndex' => 1,
						'specialValues' => array('key1' => 'value1')
					)
				),
				'specialValues' => array(),
			),
			1 => array(
				'columns' => array(
					'col0' => array(
						'value' => 'testValue3',
						'cssClass' => 'testCssClass3',
						'rowIndex' => 1,
						'columnIndex' => 0,
						'specialValues' => array('key1' => 'value1')
					),
					'col1' => array(
						'value' => 'testValue4',
						'cssClass' => 'testCssClass4',
						'rowIndex' => 1,
						'columnIndex' => 1,
						'specialValues' => array('key1' => 'value1')
					),
					'specialValues' => array(),
				)
			)
		),
	);


	/**
	 * @test
	 */
	public function classExists() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_List_ListData'));
	}


	/**
	 * @test
	 */
	public function populateListData() {
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		$this->populateListDataByObjects($listData);
	}


	/**
	 * @test
	 */
	public function getFirstRow() {
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		$this->populateListDataByObjects($listData);

		$row = $listData->getFirstRow();

		$this->assertEquals($listData->getRow(0), $row);
	}


	/**
	 * @test
	 */
	public function addRow() {
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		$row = $this->createRowFromTestData($this->testData['rows'][0]);

		$listData->addRow($row);
		$this->assertEquals($row, $listData->getRow(0));
	}


	/**
	 * @test
	 */
	public function getRow() {
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		$this->populateListDataByObjects($listData);

		$testRow = $this->createRowFromTestData($this->testData['rows'][1]);

		$this->assertEquals($testRow, $listData->getRow(1));
	}



	/**
	 * @test
	 */
	public function getCountTest() {
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		$this->populateListDataByObjects($listData);

		$this->assertEquals(count($this->testData['rows']), $listData->count());
	}


	public function convertRowToArray() {

	}



	/**
	 * Populates the listData the old way ..
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $listData
	 */
	protected function populateListDataByObjects(Tx_PtExtlist_Domain_Model_List_ListData $listData) {
		foreach($this->testData['rows'] as $testRow) {
			$listData->addRow($this->createRowFromTestData($testRow));
		}
	}


	/**
	 * @param array $rowData
	 * @return Tx_PtExtlist_Domain_Model_List_Row
	 */
	protected function createRowFromTestData(array $rowData) {
		$row = new Tx_PtExtlist_Domain_Model_List_Row();

		$row->setSpecialValues($rowData['specialValues']);

		foreach($rowData['columns'] as $key => $testCell) {
			$cell = new Tx_PtExtlist_Domain_Model_List_Cell($testCell['value']);
			$cell->setCSSClass($testCell['cssClass']);
			$cell->setColumnIndex($testCell['columnIndex']);
			$cell->setRowIndex($testCell['rowIndex']);
			$cell->addSpecialValue('key1', $testCell['specialValues']['key1']);

			$row->addCell($cell, $key);
		}

		return $row;
	}
}

?>