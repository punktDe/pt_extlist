<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * @author Daniel Lienert <lienert@punkt.de>
 * @package pt_extlist
 * @subpackage \Tests\Domain\List\Aggregates
 */
class Tx_PtExtlist_Tests_Domain_Model_List_Aggregates_AggregateListBuilder_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	protected $testListData;
	
	protected $testData;
	
	public function setUp() {
		$this->testData = array(1,2,3,4,5,6,7,8,9,10);	
		
		$this->initDefaultConfigurationBuilderMock();
		$this->buildTestListData();
	}

	
	public function testBuildAggregateDataRow() {
		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_List_Aggregates_AggregateListBuilder');
    	$aggregateListBuilder = new $accessibleClassName($this->configurationBuilderMock);
    	$aggregateListBuilder->injectArrayAggregator(Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregatorFactory::createInstance($this->testListData));
    	
    	$dataRow = $aggregateListBuilder->_call('buildAggregateDataRow');
    	$this->assertTrue(is_a($dataRow['sumField1'], 'Tx_PtExtlist_Domain_Model_List_Cell'));
    	$this->assertEquals(array_sum($this->testData)/10, $dataRow['sumField1']->getValue());
	}
	
	public function testBuildAggregateList() {
		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_List_Aggregates_AggregateListBuilder');
    	$aggregateListBuilder = new $accessibleClassName($this->configurationBuilderMock);
        $aggregateListBuilder->injectArrayAggregator(Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregatorFactory::createInstance($this->testListData));
        $aggregateListBuilder->injectRenderer(Tx_PtExtlist_Domain_Renderer_RendererFactory::getRenderer($this->configurationBuilderMock));
    	$aggregateListBuilder->init();
    	    	
    	$list = $aggregateListBuilder->buildAggregateList();
    	$this->assertTrue(is_a($list, 'Tx_PtExtlist_Domain_Model_List_ListData'));
	}
	
	
	
	protected function buildTestListData() {
		
		$this->testListData = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		foreach($this->testData as $data) {
			$row = new Tx_PtExtlist_Domain_Model_List_Row();
			$row->createAndAddCell($data/10, 'field1');
			$row->createAndAddCell($data, 'field2');
			$row->createAndAddCell($data*10, 'field3');
			$this->testListData->addRow($row);	
		}
	}
	
	
	

}
?>