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
 * Testcase for pager collection factory
 *
 * @see Tx_PtExtlist_Domain_Model_Pager_PagerCollectionFactory
 */
class Tx_PtExtlist_Tests_Domain_Model_Pager_PagerCollectionFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    protected $settings = [
                'listIdentifier' => 'Tx_PtExtlist_Tests_Domain_Model_Pager_PagerCollectioFactoryTest',
                'prototype' => [
                'pager' => [
                        'pagerClassName' => 'Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock',
                ],
                'backend' => [
                    'mysql' => [
                        'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend',
                        'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
                        'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',


                    ]
                ],
                'column' => [
                        'xy' => 'z',
                ],
                ],
                'listConfig' => [
                     'Tx_PtExtlist_Tests_Domain_Model_Pager_PagerCollectioFactoryTest' => [
                        
                        'backendConfig' => [
                                'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend',
                                'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
                                'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',

                                'repositoryClassName' => 'PunktDe_Extbase_Domain_Repository_FrontendUserGroupRepository'

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
                                 'isSortable' => '1',
                                 'access' => '1,2,3,4'
                             ]
                         ],
                        'columns' => [
                            10 => [
                                'columnIdentifier' => 'column1',
                                'fieldIdentifier' => 'field1',
                                'label' => 'Column 1',
                                'isSortable' => '0',
                                'access' => '1,2,3,4'
                            ],
                            20 => [
                                'columnIdentifier' => 'column2',
                                'fieldIdentifier' => 'field2',
                                'label' => 'Column 2',
                                'isSortable' => '1',
                                'sorting' => 'tstamp, title',
                                'access' => '1,2,3,4'
                            ]
                        ],
                        'renderer' => [
                            'rendererClassName' => 'Tx_PtExtlist_Domain_Renderer_DefaultRenderer',
                            'enabled' => 1,
                            'showCaptionsInBody' => 0,
                            'specialCell' => 'EXT:pt_extlist/Resources/Private/UserFunctions/class.tx_ptextlist_demolist_specialcell.php:tx_ptextlist_demolist_specialcell->processCell'
                        ],
                        'pager' => [
                            'itemsPerPage'   => '10',
                            'pagerConfigs' => [
                                'default' => [
                                    'templatePath' => 'EXT:pt_extlist/',
                                    'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager',
                                    'enabled' => '1'
                                ],
                            ],
                        ],
                     ]
                ]
    ];
    
    
        
    public function setup()
    {
        $this->initDefaultConfigurationBuilderMock($this->settings);
    }
    
    
    
    public function testGetInstance()
    {
        $pagerCollectionFactory = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Pager_PagerCollectionFactory');
        $pagerCollection = $pagerCollectionFactory->getInstance($this->configurationBuilderMock);
        
        $this->assertIsA($pagerCollection, 'Tx_PtExtlist_Domain_Model_Pager_PagerCollection'); /* @var $pagerCollection Tx_PtExtlist_Domain_Model_Pager_PagerCollection */
        
        $this->assertEquals(1, $pagerCollection->count());
        
        $pager = $pagerCollection->getItemById('default');
        $this->assertNotNull($pager);
    }
}
