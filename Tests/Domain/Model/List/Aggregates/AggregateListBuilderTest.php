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
 * Testcase for Aggregate list builder
 * 
 * @author Daniel Lienert 
 * @package Tests
 * @subpackage Domain\List\Aggregates
 */
class Tx_PtExtlist_Tests_Domain_Model_List_Aggregates_AggregateListBuilder_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	protected $testListData;
	
	protected $dataBackendMock;
	
	protected $testData;
	
	public function setUp() {
		$this->testData = array(1,2,3,4,5,6,7,8,9,10);	
		
		$this->initDefaultConfigurationBuilderMock();
		$this->builddataBackendMock();
	}

	
	public function testBuildAggregateDataRow() {
		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_List_Aggregates_AggregateListBuilder');
    	$aggregateListBuilder = new $accessibleClassName($this->configurationBuilderMock);
    	$aggregateListBuilder->injectArrayAggregator(Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregatorFactory::createInstance($this->dataBackendMock));
    	$aggregateListBuilder->injectDataBackend($this->dataBackendMock);
    	
    	$dataRow = $aggregateListBuilder->_call('buildAggregateDataRow');
    	$this->assertTrue(is_a($dataRow['sumField1'], 'Tx_PtExtlist_Domain_Model_List_Cell'));
    	$this->assertEquals(array_sum($this->testData)/10, $dataRow['sumField1']->getValue());
	}
	
	public function testBuildAggregateList() {
		$this->markTestIncomplete('Refactor me!');
		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_List_Aggregates_AggregateListBuilder');
    	$aggregateListBuilder = new $accessibleClassName($this->configurationBuilderMock);
        $aggregateListBuilder->injectArrayAggregator(Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregatorFactory::createInstance($this->dataBackendMock));
        $aggregateListBuilder->injectRenderer(Tx_PtExtlist_Domain_Renderer_RendererFactory::getRenderer($this->getRendererConfiguration()));
    	$aggregateListBuilder->injectDataBackend($this->dataBackendMock);
    	
        $aggregateListBuilder->init();
    	    	
    	$list = $aggregateListBuilder->buildAggregateList();
    	$this->assertTrue(is_a($list, 'Tx_PtExtlist_Domain_Model_List_ListData'));
	}
	
	
	
	protected function builddataBackendMock() {
		
		$this->testListData = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		foreach($this->testData as $data) {
			$row = new Tx_PtExtlist_Domain_Model_List_Row();
			$row->createAndAddCell($data/10, 'field1');
			$row->createAndAddCell($data, 'field2');
			$row->createAndAddCell($data*10, 'field3');
			$this->testListData->addRow($row);	
		}
		
		$this->dataBackendMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend', array('getListData','getAggregatesByConfigCollection'), array(), '', FALSE);
        $this->dataBackendMock->expects($this->any())
            ->method('getListData')
            ->will($this->returnValue($this->testListData));
        $this->dataBackendMock->expects($this->any())
            ->method('getAggregatesByConfigCollection')
            ->will($this->returnValue(array('avgField2' => 5)));
	}
	
	
	

}
?>