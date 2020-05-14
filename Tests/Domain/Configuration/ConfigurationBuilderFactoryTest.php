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
 * @package pt_extlist
 * @subpackage Tests\Unit\Domain\Configuration
 * @author Michael Knoll
 * @see ConfigurationBuilderFactory
 */
class Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderFactoryTest extends \PunktDe\PtExtbase\Testing\Unit\AbstractBaseTestcase
{
    protected $settings = [
        'listIdentifier' => 'test',
        'abc' => '1',
        'prototype' => [
            'pager' => [
                'default' => [
                    'pagerClassName' => 'DefaultPager',
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
                    'pagerClassName' => 'DefaultPager'
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
    public function makeSureClassExists()
    {
        $configurationBuilderFactory = new ConfigurationBuilderFactory();
    }



    /** @test */
    public function configurationManagerCanBeInjected()
    {
        $configurationManagerMock = $this->getSimpleMock('\TYPO3\CMS\Extbase\Configuration\ConfigurationManager'); /* @var $configurationManagerMock \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */
        $configurationBuilderFactory = new ConfigurationBuilderFactory();
        $configurationBuilderFactory->injectConfigurationManager($configurationManagerMock);
    }



    /** @test */
    public function settingsCanBeSet()
    {
        $configurationBuilderFactory = new ConfigurationBuilderFactory();
        $configurationBuilderFactory->setSettings($this->settings);
    }



    /** @test */
    public function extbaseContextCanBeInjected()
    {
        $extbaseContextMock = $this->getSimpleMock('Tx_PtExtlist_Extbase_ExtbaseContext'); /* @var $extbaseContextMock Tx_PtExtlist_Extbase_ExtbaseContext */
        $configurationBuilderFactory = new ConfigurationBuilderFactory();
        $configurationBuilderFactory->injectExtbaseContext($extbaseContextMock);
    }



    /** @test */
    public function getInstanceThrowsExceptionIfNoListIdentifierIsGiven()
    {
        $extbaseContextMock = $this->getMock('Tx_PtExtlist_Extbase_ExtbaseContext', ['getCurrentListIdentifier'], [], '', false);
        $extbaseContextMock->expects($this->any())->method('getCurrentListIdentifier')->will($this->returnValue(null)); /* @var $extbaseContextMock Tx_PtExtlist_Extbase_ExtbaseContext */
        $configurationBuilderFactory = new ConfigurationBuilderFactory();
        $configurationBuilderFactory->injectExtbaseContext($extbaseContextMock);
        try {
            $configurationBuilderFactory->getInstance();
        } catch (Exception $e) {
            return;
        }
        $this->fail('Configuration Builder Factory was expected to throw an exception due to no list identifier given, but no exception has been thrown.');
    }



    /** @test */
    public function getInstanceReturnsExceptedConfigurationBuilderInstance()
    {
        $configurationBuilderFactory = new ConfigurationBuilderFactory();
        $configurationManagerMock = $this->getMock('\TYPO3\CMS\Extbase\Configuration\ConfigurationManager', ['getConfiguration'], [], '', false);
        $configurationManagerMock->expects($this->any())->method('getConfiguration')->will($this->returnValue($this->settings)); /* @var $configurationManagerMock \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */
        $configurationBuilderFactory->injectConfigurationManager($configurationManagerMock);
        $configurationBuilderInstancesContainer = new ConfigurationBuilderInstancesContainer();
        $configurationBuilderFactory->injectConfigurationBuilderInstancesContainer($configurationBuilderInstancesContainer);
        $configurationBuilder = $configurationBuilderFactory->getInstance('test');
        $this->assertTrue(is_a($configurationBuilder, 'ConfigurationBuilder'));
    }



    /** @test */
    public function getInstanceAsksInjectedExtbaseContextForListIdentifierIfNoneIsGivenInSettings()
    {
        $configurationBuilderFactory = new ConfigurationBuilderFactory();
        unset($this->settings['listIdentifer']);
        $configurationManagerMock = $this->getMock('\TYPO3\CMS\Extbase\Configuration\ConfigurationManager', ['getConfiguration'], [], '', false);
        $configurationManagerMock->expects($this->any())->method('getConfiguration')->will($this->returnValue($this->settings)); /* @var $configurationManagerMock \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */
        $extbaseContextMock = $this->getMock('Tx_PtExtlist_Extbase_ExtbaseContext', ['getCurrentListIdentifier'], [], '', false);
        $extbaseContextMock->expects($this->any())->method('getCurrentListIdentifier')->will($this->returnValue('test')); /* @var $extbaseContextMock Tx_PtExtlist_Extbase_ExtbaseContext */
        $configurationBuilderFactory->injectConfigurationManager($configurationManagerMock);
        $configurationBuilderFactory->injectExtbaseContext($extbaseContextMock);
        $configurationBuilderInstancesContainer = new ConfigurationBuilderInstancesContainer();
        $configurationBuilderFactory->injectConfigurationBuilderInstancesContainer($configurationBuilderInstancesContainer);
        $configurationBuilder = $configurationBuilderFactory->getInstance();
        $this->assertTrue(is_a($configurationBuilder, 'ConfigurationBuilder'));
    }



    /** @test */
    public function getInstanceReturnsSingletonInstanceForSameListIdentifier()
    {
        $configurationBuilderFactory = new ConfigurationBuilderFactory();
        $configurationManagerMock = $this->getMock('\TYPO3\CMS\Extbase\Configuration\ConfigurationManager', ['getConfiguration'], [], '', false);
        $configurationManagerMock->expects($this->any())->method('getConfiguration')->will($this->returnValue($this->settings)); /* @var $configurationManagerMock \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */
        $configurationBuilderFactory->injectConfigurationManager($configurationManagerMock);
        $configurationBuilderInstancesContainer = new ConfigurationBuilderInstancesContainer();
        $configurationBuilderFactory->injectConfigurationBuilderInstancesContainer($configurationBuilderInstancesContainer);
        $firstInstance = $configurationBuilderFactory->getInstance('test');
        $secondInstance = $configurationBuilderFactory->getInstance('test');
        $this->assertTrue($firstInstance === $secondInstance);
    }
}
