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
 * Testcase for list factory
 * 
 * @package Tests
 * @subpackage Domain\Model\List
 * @author Michael Knoll 
 * @author Christoph Ehscheidt 
 */
class Tx_PtExtlist_Tests_Domain_Model_List_ListFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    public function setUp()
    {
        $this->initDefaultConfigurationBuilderMock();
    }

    
    
    public function testSetup()
    {
        $this->assertTrue(class_exists('ListFactory'));
    }



    /**
     * @test
     */
    public function createList()
    {
        $overwriteSettings['listConfig']['test']['useIterationListData'] = 0;
        $this->initDefaultConfigurationBuilderMock($overwriteSettings);

        $listData = new ListData();
        $listHeader = new ListHeader($this->configurationBuilderMock->getListIdentifier());
        
        $backendMock = $this->getMock('DummyDataBackend', ['getListData', 'getListHeader'], [$this->configurationBuilderMock]);
        $backendMock->expects($this->any())
            ->method('getListData')
            ->will($this->returnValue($listData));
        $backendMock->expects($this->any())
            ->method('getListHeader')
            ->will($this->returnValue($listHeader));
            
        $list = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')
                    ->get('ListFactory')
                    ->createList($backendMock, $this->configurationBuilderMock);
        
        $this->assertEquals($listData, $list->getListData());
        $this->assertNotNull($list->getListHeader());
    }



    /**
     * @test
     */
    public function createIterationList()
    {
        $overwriteSettings['listConfig']['test']['useIterationListData'] = 1;
        $this->initDefaultConfigurationBuilderMock($overwriteSettings);

        $iterationListData = new IterationListData();
        $listHeader = new ListHeader($this->configurationBuilderMock->getListIdentifier());
        $aggregateListData = new ListData();

        $backendMock = $this->getMock('DummyDataBackend', ['getIterationListData', 'getListHeader', 'getAggregateListData'], [$this->configurationBuilderMock]);
        $backendMock->expects($this->any())
            ->method('getIterationListData')
            ->will($this->returnValue($iterationListData));
        $backendMock->expects($this->any())
            ->method('getListHeader')
            ->will($this->returnValue($listHeader));
        $backendMock->expects($this->any())
            ->method('getAggregateListData')
            ->will($this->returnValue($aggregateListData));

        $list = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')
                        ->get('ListFactory')
                        ->createList($backendMock, $this->configurationBuilderMock);

        $this->assertEquals($iterationListData, $list->getIterationListData());
        $this->assertNotNull($list->getListHeader());
    }
}
