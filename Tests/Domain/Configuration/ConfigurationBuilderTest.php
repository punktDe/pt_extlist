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
 * 
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @see Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
 */
class Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderTest extends \PunktDe\PtExtbase\Testing\Unit\AbstractBaseTestcase
{
    protected $settings = [
        'listIdentifier' => 'test',
        'abc' => '1',
        'prototype' => [
            'pager' => [
                'default' => [
                    'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager',
                    'pagerProperty' => 'pagerValue'
                ]
            ],
            'column' => [
                'xy' => 'z',
            ]
        ],
        'listConfig' => [
            'test' => [
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
                        10 => [
                            'testkey' => 'testvalue',
                            'filterIdentifier' => 'filter1',
                            'fieldIdentifier' => 'field1',
                            'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                            'partialPath' => 'Filter/StringFilter',
                        ]
                    ]
                ],
                'pager' => [
                    'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager'
                ],

                'aggregateData' => [
                    'sumField1' => [
                        'fieldIdentifier' => 'field1',
                        'method' => 'sum',
                    ],
                    'avgField2' => [
                        'fieldIdentifier' => 'field2',
                        'method' => 'avg',
                    ],
                ],

                'bookmarks' => [
                    'showPublicBookmarks' => '1',
                    'showUserBookmarks' => '1',
                    'showGroupBookmarks' => '1',
                    'bookmarksPid' => '1',
                    'groupIdsToShowBookmarksFor' => '4,5,6'
                ],


                'aggregateRows' => [
                    10 => [
                        'column2' => [
                            'aggregateDataIdentifier' => 'avgField2',
                        ]
                    ]
                ],

                'controller' => [
                    'Export' => [
                        'download' => [
                            'view' => 'export.exportConfigs.test'
                        ]
                    ]
                ],

                'rendererChain' => [
                    'rendererConfigs' => [
                        100 => [
                            'rendererClassName' => 'Tx_PtExtlist_Domain_Renderer_Default_Renderer'
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

        

    /** @test */
    public function constructReturnsInstanceAsExpected()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings);
    }
    
    

    /** @test */
    public function constructThrowsExceptionIfNoListIdentiferIsGiven()
    {
        try {
            $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder(['prototype' => []]);
        } catch (Exception $e) {
            return;
        }
        $this->fail('No Exceptions has been raised for misconfiguration');
    }
    
    
    public function testSetAndMergeGlobalAndLocalConfig()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $settings = $configurationBuilder->getSettings();
        $this->assertEquals($settings['abc'], 2);
        $this->assertEquals($settings['def'], 3);
    }
    
    
    
    public function testGetListIdentifier()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $this->assertEquals($configurationBuilder->getListIdentifier(), $this->settings['listIdentifier']);
    }
    
    
    
    public function testBuildFieldsConfiguration()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $fieldConfigCollection = $configurationBuilder->buildFieldsConfiguration();
        $this->assertTrue(is_a($fieldConfigCollection, 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection'));
    }


    public function testBuildcolumnSelectorConfiguration()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $columnSelectorConfig = $configurationBuilder->buildColumnSelectorConfiguration();
        $this->assertTrue(is_a($columnSelectorConfig, 'Tx_PtExtlist_Domain_Configuration_ColumnSelector_ColumnSelectorConfig'), 'The method returned ' . get_class($columnSelectorConfig));
    }

    
    public function testBuildExportConfiguration()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $exportConfig = $configurationBuilder->buildExportConfiguration();
        $this->assertTrue(is_a($exportConfig, 'Tx_PtExtlist_Domain_Configuration_Export_ExportConfig'));
    }
    
    
    public function testBuildAggregateDataConfiguration()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $aggregateDataConfigCollection = $configurationBuilder->buildAggregateDataConfig();
        $this->assertTrue(is_a($aggregateDataConfigCollection, 'Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection'));
    }
    
    
    public function testBuildAggregateRowsConfiguration()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $aggregateRowConfigCollection = $configurationBuilder->buildAggregateRowsConfig();
        $this->assertTrue(is_a($aggregateRowConfigCollection, 'Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfigCollection'));
    }
    
    
    public function testBuildColumnsConfiguration()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $columnConfigCollection = $configurationBuilder->buildColumnsConfiguration();
        $this->assertTrue(is_a($columnConfigCollection, 'Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection'));
    }
    
    
    public function testGetFilterboxIdentifier()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $filterboxConfiguration = $configurationBuilder->getFilterboxConfigurationByFilterboxIdentifier('testfilterbox');
        
        $this->assertEquals($filterboxConfiguration->getFilterboxIdentifier(), 'testfilterbox', 'Expected filterboxvalue was "testvalue" got "' . $filterboxConfiguration->getFilterboxIdentifier() . '" instead!');
    }
    
    
    public function testGetPagerSettings()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $pagerSettings = $configurationBuilder->getSettingsForConfigObject('pager');
        $pagerClassName = $pagerSettings['pagerClassName'];
        $this->assertEquals($pagerClassName, 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager', 'Expected pagerClassName was "Tx_PtExtlist_Domain_Model_Pager_DefaultPager" got "' . $pagerClassName . '" instead!');
    }
    
    
    public function testThrowExceptionOnEmptyFilterboxIdentifier()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        try {
            $configurationBuilder->getFilterboxConfigurationByFilterboxIdentifier('');
            $this->fail('No exception thrown on empty filterbox identifier');
        } catch (Exception $e) {
            return;
        }
    }
    
    
    public function testGetFilterSettings()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $this->assertEquals($configurationBuilder->getSettingsForConfigObject('filter'), $this->settings['listConfig']['test']['filters']);
    }
    
    
    
    public function testGetPrototypeSettingsForObject()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        
        $prototypeSettings = $configurationBuilder->getPrototypeSettingsForObject('pager.default');
        $this->assertTrue(is_array($prototypeSettings), 'The return value must be an array');
        $this->assertEquals($prototypeSettings, $this->settings['prototype']['pager']['default']);
        
        $prototypeSettings = $configurationBuilder->getPrototypeSettingsForObject('somthingThatDoesNotExist');
        $this->assertTrue(is_array($prototypeSettings), 'The return value must be an array');
    }
    
    
    
    public function testGetMergedSettingsWithPrototype()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        
        $settings = $configurationBuilder->getMergedSettingsWithPrototype($this->settings['listConfig']['test']['pager'], 'pager.default');
        $this->assertTrue(is_array($settings), 'The return value must be an array');

        $this->assertEquals($this->settings['prototype']['pager']['default']['pagerProperty'], $settings['pagerProperty']);
    }
    
    
    public function testGetBookmarksSettings()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $bookmarkSettings = $configurationBuilder->getSettingsForConfigObject('bookmarks');
        $this->assertEquals($this->settings['listConfig']['test']['bookmarks'], $bookmarkSettings);
    }
    
    
    
    public function testBuildBookmarksConfiguration()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $bookmarkConfig = $configurationBuilder->buildBookmarkConfiguration();
        $this->assertTrue(is_a($bookmarkConfig, 'Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig'));
    }
    
    
    public function testBuildRendererChainConfiguration()
    {
        $configurationBuilder = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder($this->settings, 'test');
        $rendererChainConfig = $configurationBuilder->buildRendererChainConfiguration();

        $this->assertNotNull($rendererChainConfig);
        $this->assertTrue(is_a($rendererChainConfig, 'Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfig'), 'Got ' . get_class($rendererChainConfig));
    }
}
