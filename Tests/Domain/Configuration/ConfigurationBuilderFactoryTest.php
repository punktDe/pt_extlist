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
 * @see Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory
 */
class Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderFactoryTest extends Tx_PtExtbase_Tests_Unit_AbstractBaseTestcase
{
    protected $settings = array(
        'listIdentifier' => 'test',
        'abc' => '1',
        'prototype' => array(
            'pager' => array(
                'default' => array(
                    'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager',
                    'pagerProperty' => 'pagerValue'
                )
            ),
            'column' => array(
                'xy' => 'z',
            )
        ),
        'listConfig' => array(
            'test' => array(
                'abc' => '2',
                'def' => '3',
                'fields' => array(
                    'field1' => array(
                        'table' => 'tableName1',
                        'field' => 'fieldName1',
                        'isSortable' => '0',
                        'access' => '1,2,3,4'
                    ),
                    'field2' => array(
                        'table' => 'tableName2',
                        'field' => 'fieldName2',
                        'isSortable' => '0',
                        'access' => '1,2,3,4'
                    )
                ),
                'columns' => array(
                    10 => array(
                        'columnIdentifier' => 'column1',
                        'fieldIdentifier' => 'field1',
                        'label' => 'Column 1'
                    ),
                    20 => array(
                        'columnIdentifier' => 'column2',
                        'fieldIdentifier' => 'field2',
                        'label' => 'Column 2'
                    )
                ),
                'filters' => array(
                    'testfilterbox' => array(
                        10 => array(
                            'testkey' => 'testvalue',
                            'filterIdentifier' => 'filter1',
                            'fieldIdentifier' => 'field1',
                            'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                            'partialPath' => 'Filter/StringFilter',
                        )
                    )
                ),
                'pager' => array(
                    'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager'
                ),

                'aggregateData' => array(
                    'sumField1' => array(
                        'fieldIdentifier' => 'field1',
                        'method' => 'sum',
                    ),
                    'avgField2' => array(
                        'fieldIdentifier' => 'field2',
                        'method' => 'avg',
                    ),
                ),

                'bookmarks' => array(
                    'showPublicBookmarks' => '1',
                    'showUserBookmarks' => '1',
                    'showGroupBookmarks' => '1',
                    'bookmarksPid' => '1',
                    'groupIdsToShowBookmarksFor' => '4,5,6'
                ),


                'aggregateRows' => array(
                    10 => array(
                        'column2' => array(
                            'aggregateDataIdentifier' => 'avgField2',
                        )
                    )
                ),

                'controller' => array(
                    'Export' => array(
                        'download' => array(
                            'view' => 'export.exportConfigs.test'
                        )
                    )
                ),

                'rendererChain' => array(
                    'rendererConfigs' => array(
                        100 => array(
                            'rendererClassName' => 'Tx_PtExtlist_Domain_Renderer_Default_Renderer'
                        )
                    )
                ),

                'export' => array(
                    'exportConfigs' => array(
                        'test' => array(
                            'downloadType' => 'D',
                            'fileName' => 'testfile',
                            'fileExtension' => 'ext',
                            'addDateToFilename' => 1,
                            'pager' => array('enabled' => 0),
                            'viewClassName' => 'Tx_PtExtlist_View_Export_CsvListView',
                        )
                    )
                )

            )
        )
    );



    /** @test */
    public function makeSureClassExists()
    {
        $configurationBuilderFactory = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory();
    }



    /** @test */
    public function configurationManagerCanBeInjected()
    {
        $configurationManagerMock = $this->getSimpleMock('\TYPO3\CMS\Extbase\Configuration\ConfigurationManager'); /* @var $configurationManagerMock \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */
        $configurationBuilderFactory = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory();
        $configurationBuilderFactory->injectConfigurationManager($configurationManagerMock);
    }



    /** @test */
    public function settingsCanBeSet()
    {
        $configurationBuilderFactory = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory();
        $configurationBuilderFactory->setSettings($this->settings);
    }



    /** @test */
    public function extbaseContextCanBeInjected()
    {
        $extbaseContextMock = $this->getSimpleMock('Tx_PtExtlist_Extbase_ExtbaseContext'); /* @var $extbaseContextMock Tx_PtExtlist_Extbase_ExtbaseContext */
        $configurationBuilderFactory = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory();
        $configurationBuilderFactory->injectExtbaseContext($extbaseContextMock);
    }



    /** @test */
    public function getInstanceThrowsExceptionIfNoListIdentifierIsGiven()
    {
        $extbaseContextMock = $this->getMock('Tx_PtExtlist_Extbase_ExtbaseContext', array('getCurrentListIdentifier'), array(), '', false);
        $extbaseContextMock->expects($this->any())->method('getCurrentListIdentifier')->will($this->returnValue(null)); /* @var $extbaseContextMock Tx_PtExtlist_Extbase_ExtbaseContext */
        $configurationBuilderFactory = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory();
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
        $configurationBuilderFactory = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory();
        $configurationManagerMock = $this->getMock('\TYPO3\CMS\Extbase\Configuration\ConfigurationManager', array('getConfiguration'), array(), '', false);
        $configurationManagerMock->expects($this->any())->method('getConfiguration')->will($this->returnValue($this->settings)); /* @var $configurationManagerMock \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */
        $configurationBuilderFactory->injectConfigurationManager($configurationManagerMock);
        $configurationBuilderInstancesContainer = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderInstancesContainer();
        $configurationBuilderFactory->injectConfigurationBuilderInstancesContainer($configurationBuilderInstancesContainer);
        $configurationBuilder = $configurationBuilderFactory->getInstance('test');
        $this->assertTrue(is_a($configurationBuilder, 'Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder'));
    }



    /** @test */
    public function getInstanceAsksInjectedExtbaseContextForListIdentifierIfNoneIsGivenInSettings()
    {
        $configurationBuilderFactory = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory();
        unset($this->settings['listIdentifer']);
        $configurationManagerMock = $this->getMock('\TYPO3\CMS\Extbase\Configuration\ConfigurationManager', array('getConfiguration'), array(), '', false);
        $configurationManagerMock->expects($this->any())->method('getConfiguration')->will($this->returnValue($this->settings)); /* @var $configurationManagerMock \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */
        $extbaseContextMock = $this->getMock('Tx_PtExtlist_Extbase_ExtbaseContext', array('getCurrentListIdentifier'), array(), '', false);
        $extbaseContextMock->expects($this->any())->method('getCurrentListIdentifier')->will($this->returnValue('test')); /* @var $extbaseContextMock Tx_PtExtlist_Extbase_ExtbaseContext */
        $configurationBuilderFactory->injectConfigurationManager($configurationManagerMock);
        $configurationBuilderFactory->injectExtbaseContext($extbaseContextMock);
        $configurationBuilderInstancesContainer = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderInstancesContainer();
        $configurationBuilderFactory->injectConfigurationBuilderInstancesContainer($configurationBuilderInstancesContainer);
        $configurationBuilder = $configurationBuilderFactory->getInstance();
        $this->assertTrue(is_a($configurationBuilder, 'Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder'));
    }



    /** @test */
    public function getInstanceReturnsSingletonInstanceForSameListIdentifier()
    {
        $configurationBuilderFactory = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory();
        $configurationManagerMock = $this->getMock('\TYPO3\CMS\Extbase\Configuration\ConfigurationManager', array('getConfiguration'), array(), '', false);
        $configurationManagerMock->expects($this->any())->method('getConfiguration')->will($this->returnValue($this->settings)); /* @var $configurationManagerMock \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */
        $configurationBuilderFactory->injectConfigurationManager($configurationManagerMock);
        $configurationBuilderInstancesContainer = new Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderInstancesContainer();
        $configurationBuilderFactory->injectConfigurationBuilderInstancesContainer($configurationBuilderInstancesContainer);
        $firstInstance = $configurationBuilderFactory->getInstance('test');
        $secondInstance = $configurationBuilderFactory->getInstance('test');
        $this->assertTrue($firstInstance === $secondInstance);
    }
}
