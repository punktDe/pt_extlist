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
 * Testcase for pt_extlist data backend factory 
 * 
 * @author Michael Knoll 
 * @author Daniel Lienert 
 * @package Tests
 * @subpackage Domain\DataBackend
 * @see DataBackendFactory
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_DataBackendFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    public function setUp()
    {
        $this->initDefaultConfigurationBuilderMock();
    }
    
    

    /** @test */
    public function getDataBackendByConfigurationBuilderReturnsInstanceOfDataBackendInterface()
    {
        $listIdentifier = 'testBackend1';
        $dataBackendFactory = $this->getDataBackendFactoryMock($this->getConfigForFirstDbe(), $listIdentifier);

        $dataBackend = $dataBackendFactory->getDataBackendInstanceByListIdentifier($listIdentifier);
        $this->assertTrue(is_a($dataBackend, 'DataBackendInterface'));
        $this->assertEquals($listIdentifier, $dataBackend->getConfigurationBuilder()->getListIdentifier());
    }



    /** @test */
    public function getDataBackendInstanceByConfigurationBuilderReturnsListIdentifierBasedSingletonInstances()
    {
        $dataBackendFactory1 = $this->getDataBackendFactoryMock($this->getConfigForFirstDbe());
        $dataBackendFactory2 = $this->getDataBackendFactoryMock($this->getConfigForSecondDbe());

        $dataBackendForTest1 = $dataBackendFactory1->getDataBackendInstanceByListIdentifier('testBackend1');
        $dataBackendForTest2 = $dataBackendFactory2->getDataBackendInstanceByListIdentifier('testBackend2');
        
        $this->assertTrue($dataBackendForTest1 != $dataBackendForTest2);
        
        $duplicatedDataBackendForTest1 = $dataBackendFactory1->getDataBackendInstanceByListIdentifier('testBackend1');
            
        $this->assertTrue($dataBackendForTest1 === $duplicatedDataBackendForTest1);
    }



    protected function getConfigForFirstDbe()
    {
        return [
            'listIdentifier' => 'testBackend1',
            'abc' => '1',
            'prototype' => [],
            'listConfig' => [
                'testBackend1' => [
                    'backendConfig' => [
                        'dataBackendClass' => 'Typo3DataBackend_Typo3DataBackend',
                        'dataMapperClass' => 'Mapper_ArrayMapper',
                        'dataSourceClass' => 'DataSource_MySqlDataSource',
                        'queryInterpreterClass' => 'MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
                        'dataSource' => [
                            'testKey' => 'testValue',
                        ]
                    ],
                    'abc' => '2',
                    'def' => '3',
                    'fields' => [
                        'field1' => [
                            'table' => 'tableName1',
                            'field' => 'fieldName1',
                            'isSortable' => '0',
                            'access' => '1,2,3,4'
                        ],
                        'field2' => [
                            'table' => 'tableName2',
                            'field' => 'fieldName2',
                            'isSortable' => '0',
                            'access' => '1,2,3,4'
                        ]
                    ],
                    'columns' => [
                        10 => [
                            'columnIdentifier' => 'column1',
                            'fieldIdentifier' => 'field1',
                            'label' => 'Column 1'
                        ],
                        20 => [
                            'columnIdentifier' => 'column2',
                            'fieldIdentifier' => 'field2',
                            'label' => 'Column 2'
                        ]
                    ],
                    'filters' => [
                        'testfilterbox' => [
                            '10' => [
                                'filterIdentifier' => 'filter1',
                                'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                'fieldIdentifier' => 'field1',
                                'partialPath' => 'partialPath'
                            ],
                            '20' => [
                                'filterIdentifier' => 'filter2',
                                'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                'fieldIdentifier' => 'field2',
                                'partialPath' => 'partialPath'
                            ]
                        ]
                    ],
                    'pager' => [
                        'itemsPerPage' => '10',
                        'pagerConfigs' => [
                            'default' => [
                                'templatePath' => 'EXT:pt_extlist/',
                                'pagerClassName' => 'DefaultPager',
                                'enabled' => '1'
                            ],
                        ],
                    ],
                ]
            ]
        ];
    }



    protected function getConfigForSecondDbe()
    {
        return [
            'listIdentifier' => 'testBackend2',
            'abc' => '1',
            'prototype' => [],
            'listConfig' => [
                'testBackend2' => [
                    'backendConfig' => [
                        'dataBackendClass' => 'Typo3DataBackend_Typo3DataBackend',
                        'dataMapperClass' => 'Mapper_ArrayMapper',
                        'dataSourceClass' => 'DataSource_MySqlDataSource',
                        'queryInterpreterClass' => 'MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
                        'dataSource' => [
                            'testKey' => 'testValue',
                        ]
                    ],
                    'abc' => '2',
                    'def' => '3',
                    'fields' => [
                        'field1' => [
                            'table' => 'tableName1',
                            'field' => 'fieldName1',
                            'isSortable' => '0',
                            'access' => '1,2,3,4'
                        ],
                        'field2' => [
                            'table' => 'tableName2',
                            'field' => 'fieldName2',
                            'isSortable' => '0',
                            'access' => '1,2,3,4'
                        ]
                    ],
                    'columns' => [
                        10 => [
                            'columnIdentifier' => 'column1',
                            'fieldIdentifier' => 'field1',
                            'label' => 'Column 1'
                        ],
                        20 => [
                            'columnIdentifier' => 'column2',
                            'fieldIdentifier' => 'field2',
                            'label' => 'Column 2'
                        ]
                    ],
                    'filters' => [
                        'testfilterbox' => [
                            '10' => [
                                'filterIdentifier' => 'filter1',
                                'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                'fieldIdentifier' => 'field1',
                                'partialPath' => 'partialPath'
                            ],
                            '20' => [
                                'filterIdentifier' => 'filter2',
                                'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                'fieldIdentifier' => 'field1',
                                'partialPath' => 'partialPath'
                            ]
                        ]
                    ],
                    'pager' => [
                        'itemsPerPage' => '10',
                        'pagerConfigs' => [
                            'default' => [
                                'templatePath' => 'EXT:pt_extlist/',
                                'pagerClassName' => 'DefaultPager',
                                'enabled' => '1'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
