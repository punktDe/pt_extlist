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
class Tx_PtExtlist_Tests_Domain_Model_List_IterationListData_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * @var Tx_PtExtlist_Domain_Model_List_IterationListData
	 */
	protected $fixture;



	public function setUp() {

		$this->initDefaultConfigurationBuilderMock();

		$dataSource = new Tx_PtExtlist_Tests_Performance_TestDataSource();
		$dataMapper = new Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper($this->configurationBuilderMock);

		$rendererConfiguration = $this->configurationBuilderMock->buildRendererChainConfiguration()->getItemById(100);
		$rowRenderer = new Tx_PtExtlist_Domain_Renderer_Default_RowRenderer();
		$rowRenderer->injectRendererConfiguration($rendererConfiguration);
		$rowRenderer->injectCellRenderer(new Tx_PtExtlist_Domain_Renderer_Default_CellRenderer($rendererConfiguration));

		$this->fixture = new Tx_PtExtlist_Domain_Model_List_IterationListData();
		$this->fixture->setDataSource($dataSource);
		$this->fixture->setDataMapper($dataMapper);
		$this->fixture->setRowRenderer($rowRenderer);
	}



	/**
	 * @test
	 */
	public function count() {
		$this->setUp();

		$count = $this->fixture->count();
		$this->assertEquals(10, $count);
	}



	/**
	 * @test
	 */
	public function isIteratable() {
		$this->assertInstanceOf('Iterator', $this->fixture);
	}



	/**
	 * @test
	 */
	public function validCountOfIterations() {
		$this->setUp();

		$counter = 0;

		foreach($this->fixture as $row) {
			$counter++;
		}

		$this->assertEquals(10, $counter, 'We should have 10 iterations');
	}


	/**
	 * @test
	 */
	public function rewindSetsKeyToZero() {
		$this->setUp();

		foreach($this->fixture as $row) {}

		$this->fixture->rewind();

		$this->assertEquals(0, $this->fixture->key());
	}


	/**
	 * @test
	 */
	public function validCountOfIterationsAfterRewind() {
		$this->setUp();

		foreach($this->fixture as $row) {}

		$counter = 0;

		$this->fixture->rewind();

		foreach($this->fixture as $row) {
			$counter++;
		}

		$this->assertEquals(10, $counter, 'We should have 10 iterations');
	}


	/**
	 * @test
	 */
	public function currentReturnsRowObject() {
		$this->setUp();

		$row = $this->fixture->current();

		$this->assertTrue(is_object($row));
		$this->assertInstanceOf('Tx_PtExtlist_Domain_Model_List_Row', $row);
	}



	/**
	 * @test
	 */
	public function nextReturnsRowObject() {
		$this->setUp();

		$row = $this->fixture->next();

		$this->assertTrue(is_object($row));
		$this->assertInstanceOf('Tx_PtExtlist_Domain_Model_List_Row', $row);
	}

}

?>