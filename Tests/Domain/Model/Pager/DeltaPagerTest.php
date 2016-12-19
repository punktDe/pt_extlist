<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2015 Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * @see Tx_PtExtlist_Domain_Model_Pager_DeltaPager
 */
class Tx_PtExtlist_Tests_Domain_Model_Pager_DeltaPagerTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * Holds an instance of pager object
     *
     * @var Tx_PtExtlist_Domain_Model_Pager_DeltaPager
     */
    protected $pager;



    public function setup()
    {
        $this->initDefaultConfigurationBuilderMock();

        $pagerSettings = $this->configurationBuilderMock->getSettingsForConfigObject('pager');
        $pagerSettings = $pagerSettings['pagerConfigs']['default'];
        $pagerSettings['itemsPerPage'] = 1;
        $pagerSettings['fillItem'] = '...';
        $pagerSettings['delta'] = '2';
        $pagerSettings['fillItem'] = '...';
        $pagerSettings['fillItem'] = '...';

        $pagerConfiguration = Tx_PtExtlist_Domain_Configuration_Pager_PagerConfigFactory::getInstance($this->configurationBuilderMock, 'delta', $pagerSettings);

        $proxyClass = $this->buildAccessibleProxy(\Tx_PtExtlist_Domain_Model_Pager_DeltaPager::class);
        $this->pager = $this->objectManager->get($proxyClass, $pagerConfiguration);
    }



    public function testSetup()
    {
        $this->assertTrue(class_exists(\Tx_PtExtlist_Domain_Model_Pager_DeltaPager::class));
    }



    public function pagesDataProvider()
    {
        return [
            'twoPagesAreVisible' => [
                'pageCount' => 2, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 1,
                'expectedPages' => [1=>1, 2=>2]
            ],
            'threePagesAreVisible' => [
                'pageCount' => 3, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 1,
                'expectedPages' => [1=>1, 2=>2, 3=>3]
            ],
            'fourPagesAreVisibleAdFirstSelected' => [
                'pageCount' => 4, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 1,
                'expectedPages' => [1=>1, 2=>2, 3=>3, 4=>4]
            ],
            'fourPagesAreVisibleAndLastSelected' => [
                'pageCount' => 4, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 4,
                'expectedPages' => [1=>1, 2=>2, 3=>3, 4=>4]
            ],
            'threeFirstPagesAndLastAreVisibleAndFirstPageSelectedAndFivePages' => [
                'pageCount' => 5, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 1,
                'expectedPages' => [1=>1, 2=>2, 3=>3, 4=>4, 5=>5]
            ],
            'threeFirstPagesAndLastAreVisibleAndThirdPageSelected' => [
                'pageCount' => 9, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 3,
                'expectedPages' => [1=>1, 2=>2, 3=>3, 4 =>4,'ffi'=> '...',8=>8,9=>9]
            ],
            'firstAndTheLastThreeAreVisibleWhileLastSelectedAndLessThanTenPages' => [
                'pageCount' => 12, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 12,
                'expectedPages' => [1=>1,2=>2, 'ffi' => '...', 11 => 11, 12=>12]
            ],
            'threeFirstPagesAndLastAreVisibleAndFirstPageSelected' => [
                'pageCount' => 12, 'delta' => 2, 'firstItemDelta' => 2, 'lastItemDelta' => 1, 'currentPage' => 1,
                'expectedPages' => [1=>1, 2=>2, 3=>3, 'ffi'=> '...',12=>12]
            ],
            'threeFirstPagesAndLastAreVisibleAndSecondPageSelected' => [
                'pageCount' => 12, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 2,
                'expectedPages' => [1=>1, 2=>2, 3=>3, 'ffi'=> '...', 11=>11, 12=>12]
            ],
            'firstFourAndLastVisibleWhileThirdSelected' => [
                'pageCount' => 12, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 3,
                'expectedPages' => [1=>1, 2=>2, 3=>3, 4 => 4, 'ffi'=> '...',11=>11, 12=>12]
            ],
            'firstOneAndLastFourVisibleWhileThirdToLastSelected' => [
                'pageCount' => 12, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 10,
                'expectedPages' => [1=>1,2=>2, 'ffi' => '...', 9=>9, 10 => 10, 11 => 11, 12=>12]
            ],
            'firstFourAndLastVisibleWhileFourthSelected' => [
                'pageCount' => 12, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 6,
                'expectedPages' => [1=>1,2=>2, 'ffi' => '...',  5=>5, 6=>6,7=>7, 'bfi'=> '...',11=>11, 12=>12]
            ],
            'firstAndTheLastThreeAreVisibleWhileLastSelected' => [
                'pageCount' => 12, 'delta' => 1, 'firstItemDelta' => 2, 'lastItemDelta' => 2, 'currentPage' => 12,
                'expectedPages' => [1=>1, 2=>2, 'ffi' => '...', 11 => 11, 12=>12]
            ],
            'firstThirdAndLastVisibleFivePages' => [
                'pageCount' => 5, 'delta' => 0, 'firstItemDelta' => 1, 'lastItemDelta' => 1, 'currentPage' => 3,
                'expectedPages' => [1=>1, 2 => 2, 3 => 3, 4=>4, 5=>5]
            ],'firstThirdAndLastVisibleSevenPages' => [
                'pageCount' => 7, 'delta' => 0, 'firstItemDelta' => 1, 'lastItemDelta' => 1, 'currentPage' => 4,
                'expectedPages' => [1=>1, 'ffi' => '...', 4=>4, 'bfi'=> '...', 7=>7]
            ],'onePageFirstSelected' => [
                'pageCount' => 1, 'delta' => 4, 'firstItemDelta' => 4, 'lastItemDelta' => 1, 'currentPage' => 1,
                'expectedPages' => [1=>1]
            ]
        ];
    }



    /**
     * @dataProvider pagesDataProvider
     * @test
     * @param $pageCount
     * @param $delta
     * @param $firstItemDelta
     * @param $lastItemDelta
     * @param $currentPage
     * @param $expectedPages
     */
    public function getPages($pageCount, $delta, $firstItemDelta, $lastItemDelta, $currentPage, $expectedPages)
    {
        $this->pager->_set('totalItemCount', $pageCount);
        $this->pager->_set('delta', $delta);
        $this->pager->_set('firstItemDelta', $firstItemDelta);
        $this->pager->_set('lastItemDelta', $lastItemDelta);
        $this->pager->_set('currentPage', $currentPage);

        $this->assertEquals($expectedPages, $this->pager->getPages());
    }
}
