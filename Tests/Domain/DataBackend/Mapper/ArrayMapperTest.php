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
 * Testcase for array mapper object.
 *
 * @author Michael Knoll
 * @package Typo3
 * @subpackage pt_extlist
 * @see Mapper_ArrayMapper
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_ArrayMapperTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    protected $arrayData;



    protected $fieldSettings;



    protected $wrongFieldSettings;



    public function setup()
    {
        $this->initDefaultConfigurationBuilderMock();

        $this->arrayData = [
            ['field1' => 'v1_1', 't1_f2' => 'v1_2', 't1_f3' => 'v1_3', 'field2' => 'v1_4', 'field3' => 'v1_5', 'field4' => 'v1_6'],
            ['field1' => 'v2_1', 't1_f2' => 'v2_2', 't1_f3' => 'v2_3', 'field2' => 'v2_4', 'field3' => 'v2_5', 'field4' => 'v1_6'],
            ['field1' => 'v3_1', 't1_f2' => 'v3_2', 't1_f3' => 'v3_3', 'field2' => 'v3_4', 'field3' => 'v3_5', 'field4' => 'v1_6'],
            ['field1' => 'v4_1', 't1_f2' => 'v4_2', 't1_f3' => 'v4_3', 'field2' => 'v4_4', 'field3' => 'v4_5', 'field4' => 'v1_6']
        ];

        $this->fieldSettings = [
            'field1' => [
                'table' => 't1',
                'field' => 'f1'
            ],
            'field2' => [
                'table' => 't2',
                'field' => 'f1'
            ],
        ];

        $this->wrongFieldSettings = [
            'fieldx' => [
                'table' => 't5',
                'field' => 'f1'
            ],
            'fieldy' => [
                'table' => 't2',
                'field' => 'f1'
            ]
        ];
    }



    public function testSetUp()
    {
        $arrayMapper = new Mapper_ArrayMapper($this->configurationBuilderMock);
    }



    public function testSetMappingConfiguration()
    {
        $arrayMapper = new Mapper_ArrayMapper($this->configurationBuilderMock);
        $fieldConfigCollection = new FieldConfigCollection();
        $arrayMapper->_injectMapperConfiguration($fieldConfigCollection);
    }



    /** @test */
    public function getMappedListDataWithoutConfigurationReturnsExpectedListData()
    {
        $this->initDefaultConfigurationBuilderMock();
        $arrayMapper = new Mapper_ArrayMapper($this->configurationBuilderMock);
        $arrayMapper->init();
        $mappedListData = $arrayMapper->getMappedListData($this->arrayData);
        $this->assertEquals($mappedListData[0]['field1']->getValue(), 'v1_1');
    }



    /** @test */
    public function getMappedListDataWithMappingConfigurationReturnsExpectedMappedList()
    {
        $arrayMapper = new Mapper_ArrayMapper($this->configurationBuilderMock);
        /**
         * TODO think about better way to test this without need of dependent object!
         */
        $fieldConfigCollection = FieldConfigCollectionFactory::getInstance($this->configurationBuilderMock);
        $arrayMapper->_injectMapperConfiguration($fieldConfigCollection);
        $arrayMapper->init();
        $mappedListData = $arrayMapper->getMappedListData($this->arrayData);
        $this->assertEquals($mappedListData[0][$fieldConfigCollection->getItemById('field1')->getIdentifier()]->getValue(), 'v1_1');
        $this->assertEquals($mappedListData[3][$fieldConfigCollection->getItemById('field2')->getIdentifier()]->getValue(), 'v4_4');
    }



    /** @test */
    public function testThrowExceptionOnNonExistingFieldName()
    {
        $arrayMapper = new Mapper_ArrayMapper($this->configurationBuilderMock);
        $fieldConfigCollection = FieldConfigCollectionFactory::getInstance($this->configurationBuilderMock);
        $arrayMapper->_injectMapperConfiguration($fieldConfigCollection);
        $arrayMapper->init();

        $wrongdata = $this->arrayData;
        unset($wrongdata[0]['field4']);

        try {
            $mappedListData = $arrayMapper->getMappedListData($wrongdata);
        } catch (Exception $e) {
            return;
        }
        $this->fail();
    }
}
