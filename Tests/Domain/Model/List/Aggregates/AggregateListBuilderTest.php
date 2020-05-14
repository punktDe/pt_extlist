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
 * @see Aggregates_AggregateListBuilder
 */
class Tx_PtExtlist_Tests_Domain_Model_List_Aggregates_AggregateListBuilderTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /** @var  ListData */
    protected $testListData;



    /** @var  MySqlDataBackend_MySqlDataBackend */
    protected $dataBackendMock;



    /** @var  array */
    protected $testData;



    public function setUp()
    {
        $this->testData = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $this->initDefaultConfigurationBuilderMock();
        $this->builddataBackendMock();
    }



    public function testBuildAggregateDataRow()
    {
        $accessibleClassName = $this->buildAccessibleProxy('Aggregates_AggregateListBuilder');
        $aggregateListBuilder = new $accessibleClassName($this->configurationBuilderMock);
        $aggregateListBuilder->injectArrayAggregator(Aggregates_ArrayAggregatorFactory::createInstance($this->dataBackendMock));
        $aggregateListBuilder->injectDataBackend($this->dataBackendMock);

        $dataRow = $aggregateListBuilder->_call('buildAggregateDataRow');
        $this->assertTrue(is_a($dataRow['sumField1'], 'Cell'));
        $this->assertEquals(array_sum($this->testData) / 10, $dataRow['sumField1']->getValue());
    }



    public function testBuildAggregateList()
    {
        $this->markTestIncomplete('Refactor me!');
        $accessibleClassName = $this->buildAccessibleProxy('Aggregates_AggregateListBuilder');
        $aggregateListBuilder = new $accessibleClassName($this->configurationBuilderMock);
        $aggregateListBuilder->injectArrayAggregator(Aggregates_ArrayAggregatorFactory::createInstance($this->dataBackendMock));
        $aggregateListBuilder->injectRenderer(Tx_PtExtlist_Domain_Renderer_RendererFactory::getRenderer($this->getRendererConfiguration()));
        $aggregateListBuilder->injectDataBackend($this->dataBackendMock);

        $aggregateListBuilder->init();

        $list = $aggregateListBuilder->buildAggregateList();
        $this->assertTrue(is_a($list, 'ListData'));
    }



    protected function builddataBackendMock()
    {
        $this->testListData = new ListData();

        foreach ($this->testData as $data) {
            $row = new Row();
            $row->createAndAddCell($data / 10, 'field1');
            $row->createAndAddCell($data, 'field2');
            $row->createAndAddCell($data * 10, 'field3');
            $this->testListData->addRow($row);
        }

        $this->dataBackendMock = $this->getMock('MySqlDataBackend_MySqlDataBackend', ['getListData', 'getAggregatesByConfigCollection'], [], '', false);
        $this->dataBackendMock->expects($this->any())
                ->method('getListData')
                ->will($this->returnValue($this->testListData));
        $this->dataBackendMock->expects($this->any())
                ->method('getAggregatesByConfigCollection')
                ->will($this->returnValue(['avgField2' => 5]));
    }
}
