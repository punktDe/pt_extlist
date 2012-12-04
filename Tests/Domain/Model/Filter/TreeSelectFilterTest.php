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
				'treeNodeRepository' => 'Tx_PtExtbase_Tree_NodeRepository',
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
		$this->accessibleFilterProxy->_set('filterValues', array(5));
		$this->accessibleFilterProxy->_call('initFilter');
		$this->assertEquals(array(5), $this->accessibleFilterProxy->_get('filterValues'));
	}


	/**
	 * @test
	 */
	public function filterTransformsFilterValuesMultiple() {
		$this->accessibleFilterProxy->_set('filterValues', array(5,4,8));
		$this->accessibleFilterProxy->_set('multiple', true);
		$this->accessibleFilterProxy->_call('initFilter');
		$this->assertEquals(array(5,4,8), $this->accessibleFilterProxy->_get('filterValues'));
	}



	/**
	 * @test
	 */
	public function getSubTreeUIDs() {

		$tree = $this->createDemoTree();
		$this->accessibleFilterProxy->_set('tree', $tree);

		$resultUIds = $this->accessibleFilterProxy->_call('getSubTreeUIDs', 2);

		$this->assertEquals(array(3,4), $resultUIds);
	}


	public function getFilterNodeUidTestDataProvider() {
		return array(
			'singleValueLeafNode' => array('values' => array(6), 'expected' => array(6)),
			'multipleValueLeafNodes' => array('values' => array(6,4), 'expected' => array(6,4)),
			'singleValueBranchNode' => array('values' => array(5), 'expected' => array(5,6)),
			'multiValueBranchNodes' => array('values' => array(5,2), 'expected' => array(5,6,2,3,4)),
			'handleDuplicates' => array('values' => array(2,3), 'expected' => array(2,3,4)),
		);
	}


	/**
	 * @test
	 * @dataProvider getFilterNodeUidTestDataProvider
	 *
	 * @param $values
	 * @param $expected
	 */
	public function getFilterNodeUIds($values, $expected) {
		$tree = $this->createDemoTree();

		$this->accessibleFilterProxy->_set('tree', $tree);
		$this->accessibleFilterProxy->_set('filterValues', $values);

		$actual = $this->accessibleFilterProxy->_call('getFilterNodeUIds');

		$this->assertEquals(sort($expected), sort($actual));
	}



	/**
	 * @return Tx_PtExtbase_Tree_Tree
	 *
	 * A tree like
	 * . node1
	 * .. node2
	 * ... node3
	 * ... node4
	 * .. node5
	 * ... node6
	 */
	protected function createDemoTree() {
		$node1 = Tx_PtExtbase_Tests_Unit_Tree_NodeMock::createNode('1', 0, 0, 1, '1');
		$node2 = Tx_PtExtbase_Tests_Unit_Tree_NodeMock::createNode('2', 0, 0, 1, '2');
		$node3 = Tx_PtExtbase_Tests_Unit_Tree_NodeMock::createNode('3', 0, 0, 1, '3');
		$node4 = Tx_PtExtbase_Tests_Unit_Tree_NodeMock::createNode('4', 0, 0, 1, '4');
		$node5 = Tx_PtExtbase_Tests_Unit_Tree_NodeMock::createNode('5', 0, 0, 1, '5');
		$node6 = Tx_PtExtbase_Tests_Unit_Tree_NodeMock::createNode('6', 0, 0, 1, '6');

		$node1->addChild($node2); $node2->setParent($node1);
		$node1->addChild($node5); $node5->setParent($node1);
		$node2->addChild($node3); $node3->setParent($node2);
		$node2->addChild($node4); $node4->setParent($node2);
		$node5->addChild($node6); $node6->setParent($node5);

		return Tx_PtExtbase_Tree_Tree::getInstanceByRootNode($node1);
	}



}

?>