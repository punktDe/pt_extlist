<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll,
 *           Christoph Ehscheidt, Joachim Mathes
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
 * Testcase for DatePicker Filter class
 *
 * @package Tests
 * @subpackage Domain\Model\Filter
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DateRangeFilterTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    protected $proxyClass;

    /**
     * @var Tx_PtExtlist_Domain_Model_Filter_DateRangeFilter
     */
    protected $proxy;



    public function setUp()
    {
        $this->initDefaultConfigurationBuilderMock();
        $this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DateRangeFilter');
        $this->proxy = new $this->proxyClass();
    }


    public function calculateTimestampBoundariesDataProvider()
    {
        return array(
            'sameDate' => array(
                'userDateFromValue' => '20101027',
                'userDateToValue'  => '20101027',
                'timestampBoundaries' => array(
                    'filterValueFromTimestamp' => 1288130400,
                    'filterValueToTimestamp' => 1288216799
                )
            ),
            'sameDateDefaultFormat' => array(
                'userDateFromValue' => '2010-10-27',
                'userDateToValue'  => '2010-10-27',
                'timestampBoundaries' => array(
                    'filterValueFromTimestamp' => 1288130400,
                    'filterValueToTimestamp' => 1288216799
                )
            ),
        );
    }


    /**
     * @test
     * @dataProvider calculateTimestampBoundariesDataProvider
     *
     * @param $userDateFromValue
     * @param $userDateToValue
     * @param $timestampBoundaries
     */
    public function calculateTimestampBoundaries($userDateFromValue, $userDateToValue, $timestampBoundaries)
    {
        $this->proxy->_set('filterValueFrom', $userDateFromValue);
        $this->proxy->_set('filterValueTo', $userDateToValue);

        $actualTimestampBoundaries = $this->proxy->_call('getCalculatedTimestampBoundaries'); /** @var Tx_PtExtlist_Domain_QueryObject_Criteria $criteria */

        $this->assertEquals($timestampBoundaries, $actualTimestampBoundaries);
    }
}
