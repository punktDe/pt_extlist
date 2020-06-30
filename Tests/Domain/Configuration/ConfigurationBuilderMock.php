<?php
namespace PunktDe\PtExtlist\Tests\Domain\Configuration;

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

use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * Class implementing a mock for configuration builder
 *
 * @package Tests
 * @subpackage Domain\Configuration
 * @author Michael Knoll 
 * @author Daniel Lienert 
 */
class ConfigurationBuilderMock extends ConfigurationBuilder
{
    /**
     * Returns array of settings for current plugin configuration
     *
     * @return array
     */
    public function getPluginSettings()
    {
        return $this->origSettings;
    }
    
    
    
    /**
     * Returns a singleton instance of this class
     * @param array $settings The current settings for this extension.
     * @param array $overwriteSettings Overwrite the default settings
     * @return ConfigurationBuilder   Singleton instance of this class
     */
    public static function getInstance($settings = null, $overwriteSettings = null)
    {
        if (is_array($settings) && count($settings)) {
            $configurationBuilderMock = new ConfigurationBuilderMock($settings);
        } else {
            $settings = [
                'listIdentifier' => 'test',

                'abc' => '1',
                'prototype' => [

                    'backend' => [
                        'mysql' => [
                            'dataBackendClass' => 'MySqlDataBackend_MySqlDataBackend',
                            'dataMapperClass' => 'Mapper_ArrayMapper',
                            'dataSourceClass' => 'DataSource_MySqlDataSource',
                            'queryInterpreterClass' => 'MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
                        ]
                    ],
                    'column' => [
                        'xy' => 'z',
                    ],
                ],
                'listConfig' => [
                    'test' => [

                        'default' => [
                            'sortingColumn' => 'column3',
                        ],


                        'headerPartial' => 'List/ListHeader',
                        'bodyPartial' => 'List/ListBody',
                        'aggregateRowsPartial' => 'List/AggregateRows',
                        'useIterationListData' => 1,

                        'backendConfig' => [
                            'dataBackendClass' => 'Typo3DataBackend_Typo3DataBackend',
                            'dataMapperClass' => 'Mapper_ArrayMapper',
                            'dataSourceClass' => 'DataSource_Typo3DataSource',
                            'queryInterpreterClass' => 'MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',


                            'dataSource' => [
                                'testKey' => 'testValue',
                                'username' => 'user',
                                'password' => 'pass',
                                'host' => 'localhost',
                                'port' => 3306,
                                'databaseName' => 'typo3',
                            ],

                            'baseFromClause' => 'companies',
                            'baseGroupByClause' => 'company',
                            'baseWhereClause' => 'employees > 0'
                        ],

                        'abc' => '2',
                        'def' => '3',

                        'controller' => [
                            'Export' => [
                                'download' => [
                                    'view' => 'export.exportConfigs.test'
                                ]
                            ]
                        ],

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
                                'isSortable' => '1',
                                'access' => '1,2,3,4'
                            ],
                            'field3' => [
                                'special' => 'special',
                                'isSortable' => '1',
                                'access' => '1,2,3,4'
                            ],
                            'field4' => [
                                'table' => 'tableName4',
                                'field' => 'fieldName4',
                            ]
                        ],
                        'columns' => [
                            10 => [
                                'columnIdentifier' => 'column1',
                                'fieldIdentifier' => 'field1',
                                'label' => 'Column 1',
                                'isSortable' => '0',

                                'excelExport' => [
                                    'wrap' => 0,
                                    'vertical' => 'top',
                                ],
                            ],
                            20 => [
                                'columnIdentifier' => 'column2',
                                'fieldIdentifier' => 'field2',
                                'label' => 'Column 2',
                                'isSortable' => '1',
                                'isVisible' => '0',
                                'sorting' => 'field1, field2',
                                'showInHeader' => 0,

                            ],
                            30 => [
                                'columnIdentifier' => 'column3',
                                'fieldIdentifier' => 'field3',
                                'label' => 'Column 3',
                                'isSortable' => '1',
                                'sorting' => 'field1 asc, field2 !DeSc',
                                'accessGroups' => '1,2,3,4',
                                'cellCSSClass' => 'class',
                            ],
                            40 => [
                                'columnIdentifier' => 'column4',
                                'fieldIdentifier' => 'field4',
                                'label' => 'Column 4',
                                //'renderTemplate' => 'typo3conf/ext/pt_extlist/Configuration/TypoScript/Demolist/Demolist_Typo3_02.hierarchicStructure.html',
                            ],

                            // We use this column for testing sortingFields setup
                            50 => [
                                'columnIdentifier' => 'column5',
                                'fieldIdentifier' => 'field4',
                                'label' => 'Column 5',
                                'sortingFields' => [
                                    10 => [
                                        'field' => 'field1',
                                        'direction' => 'desc',
                                        'forceDirection' => 1,
                                        'label' => 'Sorting label 1'
                                    ],
                                    20 => [
                                        'field' => 'field2',
                                        'direction' => 'asc',
                                        'forceDirection' => 0,
                                        'label' => 'Sorting label 2'
                                    ],
                                    30 => [
                                        'field' => 'field3',
                                        'direction' => 'desc',
                                        'forceDirection' => 0,
                                        'label' => 'Sorting label 3'
                                    ]
                                ]
                            ],

                            // This column configuration tests objectMapper
                            60 => [
                                'columnIdentifier' => 'column6',
                                'fieldIdentifier' => 'field4',
                                'label' => 'Column 6',
                                'objectMapper' => [
                                    'class' => 'Bookmark',
                                    'mapping' => [
                                        'label' => 'name'
                                    ]
                                ]
                            ]

                        ],

                        'rendererChain' => [
                            'enabled' => 1,
                            'rendererConfigs' => [
                                100 => [
                                    'rendererClassName' => 'Tx_PtExtlist_Tests_Domain_Renderer_DummyRenderer',
                                ]
                            ]
                        ],

                        'filters' => [
                            'testfilterbox' => [
                                'filterConfigs' => [
                                    '10' => [
                                        'filterIdentifier' => 'filter1',
                                        'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                        'fieldIdentifier' => 'field1',
                                        'partialPath' => 'Filter/StringFilter',
                                        'defaultValue' => 'default',
                                    ],
                                    '20' => [
                                        'filterIdentifier' => 'filter2',
                                        'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                        'fieldIdentifier' => 'field1',
                                        'partialPath' => 'Filter/StringFilter',
                                        'accessGroups' => '1,2,3'
                                    ]
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


                        'aggregateData' => [
                            'sumField1' => [
                                'fieldIdentifier' => 'field1',
                                'method' => 'sum',
                                'scope' => 'page',
                            ],
                            'avgField2' => [
                                'fieldIdentifier' => 'field2',
                                'method' => 'avg',
                            ],
                        ],


                        'aggregateRows' => [
                            10 => [
                                'column2' => [
                                    'aggregateDataIdentifier' => 'sumField1',
                                ]
                            ]
                        ],

                        'export' => [
                            'exportConfigs' => [
                                'test' => [
                                    'downloadType' => 'D',
                                    'fileName' => 'testfile',
                                    'fileExtension' => 'ext',
                                    'addDateToFilename' => 1,
                                    'pager' => ['enabled' => 0],
                                    'viewClassName' => 'Tx_PtExtlist_View_Export_CsvListView',
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            if (is_array($overwriteSettings)) {
                ArrayUtility::mergeRecursiveWithOverrule($settings, $overwriteSettings);
            }

            $configurationBuilderMock = new ConfigurationBuilderMock($settings);
            $configurationBuilderMock->settings = $configurationBuilderMock->origSettings['listConfig'][$configurationBuilderMock->origSettings['listIdentifier']];
        }
        return $configurationBuilderMock;
    }
}
