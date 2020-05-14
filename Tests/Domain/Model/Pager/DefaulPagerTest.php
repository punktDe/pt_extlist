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
 * Testcase for default pager object
 *
 * @package Tests
 * @subpackage pt_extlist
 * @author Michael Knoll
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Tests_Domain_Model_Pager_DefaultPagerTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * Holds an instance of pager object
     *
     * @var DefaultPager
     */
    protected $pager;


    /**
     * @var Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig
     */
    protected $pagerConfiguration;


    public function setup()
    {
        $this->initDefaultConfigurationBuilderMock();

        $pagerSettings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');
        $pagerSettings = $pagerSettings['pagerConfigs']['default'];
        $pagerSettings['itemsPerPage'] = 10;

        $this->pagerConfiguration = Tx_PtExtlist_Domain_Configuration_Pager_PagerConfigFactory::getInstance($this->configurationBuilderMock, 'default', $pagerSettings);

        $accessiblePagerClass = $this->buildAccessibleProxy('DefaultPager');
        $this->pager = new $accessiblePagerClass($this->pagerConfiguration);
    }


    public function testSetup()
    {
        $this->assertTrue(class_exists('DefaultPager'));
    }


    public function testIsEnabled()
    {
        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');
        $settings['enabled'] = 0;
        $disabledPagerConfiguration = $this->getPagerConfigurationByArray($settings);
        $disabledPager = new DefaultPager($disabledPagerConfiguration);
        $this->assertFalse($disabledPager->isEnabled());

        $settings['enabled'] = 1;
        $enabledPagerConfiguration = $this->getPagerConfigurationByArray($settings);
        $enabledPager = new DefaultPager($enabledPagerConfiguration);
        $this->assertTrue($enabledPager->isEnabled());
    }


    public function isNeededDataProvider()
    {
        return [
            'same' => ['itemsPerPage' => 10, 'totalItemCount' => 10, 'isNeeded' => false],
            'moreItemsThanPageSize' => ['itemsPerPage' => 10, 'totalItemCount' => 20, 'isNeeded' => true],
            'lessItemsThanPageSize' => ['itemsPerPage' => 20, 'totalItemCount' => 10, 'isNeeded' => false],
        ];
    }


    /**
     * @test
     * @dataProvider isNeededDataProvider
     *
     * @param $itemsPerPage
     * @param $totalItemCount
     * @param $isNeeded
     */
    public function isNeeded($itemsPerPage, $totalItemCount, $isNeeded)
    {
        $this->pager->setItemsPerPage($itemsPerPage);
        $this->pager->setItemCount($totalItemCount);

        $this->assertEquals($isNeeded, $this->pager->isNeeded());
    }



    public function testCurrentPage()
    {
        $this->pager->setCurrentPage(10);
        $this->assertEquals($this->pager->getCurrentPage(), 10);
    }


    public function testItemCount()
    {
        $this->pager->setItemCount(10);
        $this->assertEquals($this->pager->getItemCount(), 10);
    }


    public function testGetShowFirstLink()
    {
        $this->assertFalse($this->pager->getShowFirstLink());

        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');

        $settings['showFirstLink'] = 1;
        $settings['itemsPerPage'] = 10;

        $this->pager = new DefaultPager($this->getPagerConfigurationByArray($settings));
        $this->assertTrue($this->pager->getShowFirstLink());
    }

    public function testGetShowLastLink()
    {
        $this->assertFalse($this->pager->getShowLastLink());

        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');

        $settings['showLastLink'] = 1;
        $settings['itemsPerPage'] = 10;

        $this->pager = new DefaultPager($this->getPagerConfigurationByArray($settings));
        $this->assertTrue($this->pager->getShowLastLink());
    }


    public function testGetShowPreviousLink()
    {
        $this->assertFalse($this->pager->getShowPreviousLink());

        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');

        $settings['showPreviousLink'] = 1;
        $settings['itemsPerPage'] = 10;

        $this->pager = new DefaultPager($this->getPagerConfigurationByArray($settings));
        $this->assertTrue($this->pager->getShowPreviousLink());
    }


    public function testGetShowNextLink()
    {
        $this->assertFalse($this->pager->getShowNextLink());

        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');

        $settings['showNextLink'] = 1;
        $settings['itemsPerPage'] = 10;

        $this->pager = new DefaultPager($this->getPagerConfigurationByArray($settings));
        $this->assertTrue($this->pager->getShowNextLink());
    }


    public function testGetPages()
    {
        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');
        $settings['itemsPerPage'] = 2;

        $this->pager = new DefaultPager($this->getPagerConfigurationByArray($settings));
        $this->pager->setItemCount(21);

        $this->assertEquals(count($this->pager->getPages()), 11);
    }


    public function testGetFirstItemIndex()
    {
        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');
        $settings['itemsPerPage'] = 10;

        $this->pager = new DefaultPager($this->getPagerConfigurationByArray($settings));
        $this->pager->setItemCount(100);
        $this->pager->setCurrentPage(2);

        $this->assertEquals($this->pager->getFirstItemIndex(), 11);
    }


    public function testGetLastItemIndex()
    {
        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');
        $settings['itemsPerPage'] = 10;

        $this->pager = new DefaultPager($this->getPagerConfigurationByArray($settings));
        $this->pager->setCurrentPage(3);
        $this->pager->setItemCount(100);

        $this->assertEquals($this->pager->getLastItemIndex(), 30);
    }


    public function testgetPagerConfiguration()
    {
        $this->assertNotNull($this->pager->getPagerConfiguration());
    }


    /** @test */
    public function getIsOnFirstPageReturnsTrueIfPagerIsOnFirstPage()
    {
        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');
        $settings['itemsPerPage'] = 10;
        $pager = new DefaultPager($this->getPagerConfigurationByArray($settings));
        $pager->setCurrentPage(1);

        $this->assertTrue($pager->getIsOnFirstPage());
    }


    /** @test */
    public function getIsOnFirstPageReturnsFalseIfPagerIsNotOnFirstPage()
    {
        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');
        $settings['itemsPerPage'] = 10;
        $pager = new DefaultPager($this->getPagerConfigurationByArray($settings));
        $pager->setCurrentPage(2);

        $this->assertFalse($pager->getIsOnFirstPage());
    }


    /** @test */
    public function getIsOnLastPageReturnsTrueIfPagerIsOnLastPage()
    {
        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');
        $settings['itemsPerPage'] = 10;
        $pager = new DefaultPager($this->getPagerConfigurationByArray($settings));
        $pager->setCurrentPage(10);
        $pager->setItemCount(100);

        $this->assertTrue($pager->getIsOnLastPage());
    }


    /** @test */
    public function getIsOnLastPageReturnsFalseIfPagerIsNotOnLastPage()
    {
        $settings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');
        $settings['itemsPerPage'] = 10;
        $pager = new DefaultPager($this->getPagerConfigurationByArray($settings));
        $pager->setCurrentPage(1);
        $pager->setItemCount(100);

        $this->assertFalse($pager->getIsOnLastPage());
    }


    /**
     * @return array
     */
    public function getPageCountDataProvider()
    {
        return [
            'no data -> no page' => ['totalItemCount' => 0, 'itemsPerPage' => 10, 'expectedPageCount' => 0],
            'itemsPerPage is set to zero -> we have 1 page ' => ['totalItemCount' => 10, 'itemsPerPage' => 0, 'expectedPageCount' => 1],
            'we have a pageCount and itemsPerPage -> divide' => ['totalItemCount' => 50, 'itemsPerPage' => 10, 'expectedPageCount' => 5]
        ];
    }


    /**
     * @test
     * @dataProvider getPageCountDataProvider
     *
     * @param $totalItemCount
     * @param $itemsPerPage
     * @param $expectedPageCount
     */
    public function getPageCount($totalItemCount, $itemsPerPage, $expectedPageCount)
    {
        $this->pager->setItemsPerPage($itemsPerPage);
        $this->pager->setItemCount($totalItemCount);

        $this->assertEquals($expectedPageCount, $this->pager->getPageCount());
    }


    /**
     * Returns a pager configuration object for a given settings array
     *
     * @param array $pagerConfigurationArray
     * @return Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration
     */
    protected function getPagerConfigurationByArray($pagerConfigurationArray)
    {
        $pagerSettingsAll = $this->configurationBuilderMock->getSettingsForConfigObject('pager');

        $pagerSettings = $pagerSettingsAll['pagerConfigs']['default'];
        $pagerSettings['itemsPerPage'] = 10;
        $pagerConfigurationArray = \TYPO3\CMS\Core\Utility\GeneralUtility::array_merge_recursive_overrule($pagerSettings, $pagerConfigurationArray);

        $configurationBuilderMock = $this->getMock('ConfigurationBuilder', ['getPagerSettings', 'getListIdentifier'], [[]], '', false);
        $configurationBuilderMock->expects($this->any())
            ->method('getListIdentifier')
            ->will($this->returnValue('test'));
        $configurationBuilderMock->expects($this->any())
            ->method('getPagerSettings')
            ->will($this->returnValue($pagerConfigurationArray));

        $pagerConfiguration = Tx_PtExtlist_Domain_Configuration_Pager_PagerConfigFactory::getInstance($configurationBuilderMock, 'default', $pagerConfigurationArray);
        return $pagerConfiguration;
    }


    /**
     * Returns an array with settings required by pager configuration
     *
     * @return array
     */
    protected function getPagerBaseSettings()
    {
        return [
            'pagerClassName' => 'DefaultPager',
            'enabled' => '1'
        ];
    }
}
