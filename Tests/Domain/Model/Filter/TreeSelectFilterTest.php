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
 * Testcase for group filter
 *
 * @package Tests
 * @subpackage pt_extlist
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_TreeSelectFilter_testcase extends Tx_PtExtlist_Tests_BaseTestcase {


	/**
	 * @var Tx_PtExtlist_Domain_Model_Filter_TreeSelectFilter
	 */
	protected $accessibleFilterProxy;


	public function setup() {
		$this->initDefaultConfigurationBuilderMock();

		$accessibleFilterProxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_TreeSelectFilter');
		$this->accessibleFilterProxy = new $accessibleFilterProxyClass();
	}


	public function tearDown() {
		unset($this->accessibleFilterProxy);
	}

	
	/**
	 * @test
	 */
    public function classExist() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_TreeSelectFilter'));
    	$selectFilter = new Tx_PtExtlist_Domain_Model_Filter_TreeSelectFilter();
    	$this->assertTrue(is_a($selectFilter, 'Tx_PtExtlist_Domain_Model_Filter_FilterInterface'));
    }


	/**
	 * @test
	 */
	public function getMultiple() {
		$treeSelectFilter = new Tx_PtExtlist_Domain_Model_Filter_TreeSelectFilter();
		$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
			$this->configurationBuilderMock,
			array(
				'filterIdentifier' => 'test',
				'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_TreeSelectFilter',
				'partialPath' => 'Filter/SelectFilter',
				'fieldIdentifier' => 'field1',
				'filterField' => 'field2',
				'displayFields' => 'field1',
				'treeNodeRepository' => __CLASS__,
				'multiple' => 1
			), 'test');

		$treeSelectFilter->injectFilterConfig($filterConfiguration);

		$treeSelectFilter->init();
		$this->assertEquals($treeSelectFilter->getMultiple(), 1);
	}



	/**
	 * @test
	 */
	public function filterChecksTreeNodeRepository() {
		$treeSelectFilter = new Tx_PtExtlist_Domain_Model_Filter_TreeSelectFilter();
		$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
			$this->configurationBuilderMock,
			array(
				'filterIdentifier' => 'test',
				'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_TreeSelectFilter',
				'partialPath' => 'Filter/SelectFilter',
				'fieldIdentifier' => 'field1',
				'filterField' => 'field2',
				'displayFields' => 'field1',
				'treeNodeRepository' => 'foo',
				'multiple' => 1
			), 'test');

		$treeSelectFilter->injectFilterConfig($filterConfiguration);

		try {
			$treeSelectFilter->init();
		} catch (Exception $e) {
			return;
		}

		$this->fail('No Exception was thrown on non existant treeNodeRepository className');
	}


	/**
	 * @test
	 */
	public function filterTransformsFilterValuesSingle() {
		$this->accessibleFilterProxy->_set('filterValues', '5');
		$this->accessibleFilterProxy->initFilter();
		$this->assertEquals('5', $this->accessibleFilterProxy->_get('filterValues'));
	}


	/**
	 * @test
	 */
	public function filterTransformsFilterValuesMultiple() {
		$this->accessibleFilterProxy->_set('filterValues', '5,4,8');
		$this->accessibleFilterProxy->_set('multiple', true);
		$this->accessibleFilterProxy->initFilter();
		$this->assertEquals(array(5,4,8), $this->accessibleFilterProxy->_get('filterValues'));
	}


	/**
	 * @test
	 */
	public function getSelectionMultiple() {

		$options = array(
			1 => array(
				'rowCount' => '3378',
				'value' => 'Test Value 1',
				'selected' => TRUE),
			4 => array(
				'rowCount' => '4711',
				'value' => 'Test Value 2',
				'selected' => TRUE),
		);

		$this->accessibleFilterProxy->_set('options', $options);
		$this->accessibleFilterProxy->_set('multiple', 1);
		$result = $this->accessibleFilterProxy->_call('getSelection');

		$this->assertEquals('1,4', $result);
	}



	/**
	 * @test
	 */
	public function getSelectionSingle() {

		$options = array(
			1 => array(
				'rowCount' => '3378',
				'value' => 'Test Value 1',
				'selected' => FALSE),
			4 => array(
				'rowCount' => '4711',
				'value' => 'Test Value 2',
				'selected' => TRUE),
		);

		$this->accessibleFilterProxy->_set('options', $options);
		$this->accessibleFilterProxy->_set('multiple', 0);
		$result = $this->accessibleFilterProxy->_call('getSelection');

		$this->assertEquals('4', $result);
	}





}

?>