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
 * Class implements testcase for filterfactory
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll
 * @see Tx_PtExtlist_Domain_Model_Filter_FilterFactory
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_FilterFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    public function setUp()
    {
        $this->initDefaultConfigurationBuilderMock();
    }



    public function testSetup()
    {
        $this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_FilterFactory'));
    }



    /** @test */
    public function createInstanceByConfigurationReturnsExpectedInstance()
    {
        $dataBackendFactoryMock = $this->getDataBackendFactoryMockForListConfigurationAndListIdentifier($this->configurationBuilderMock->getSettings(), $this->configurationBuilderMock->getListIdentifier());

        $filterConfigurationMock = new Tx_PtExtlist_Tests_Domain_Configuration_Filters_Stubs_FilterboxConfigurationCollectionMock();
        $filterConfigurationMock->setup();
        $filterConfiguration = $filterConfigurationMock->getFilterConfigurationMock('filter1', 'test');

        $filterFactory = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Filter_FilterFactory'); /* @var $filterFactory Tx_PtExtlist_Domain_Model_Filter_FilterFactory */

        $filter = $filterFactory->createInstance($filterConfiguration);

        $this->assertEquals($filter->getFilterIdentifier(), 'filter1');
        $this->assertEquals($filter->getListIdentifier(), 'test');
    }



    /** @test */
    public function createNonFilterInterfaceImplementingClassThrowsException()
    {
        $dataBackendFactoryMock = $this->getDataBackendFactoryMockForListConfigurationAndListIdentifier($this->configurationBuilderMock->getSettings(), $this->configurationBuilderMock->getListIdentifier());

        $mockFilterConfiguration = $this->getMock(
            'Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig',
            array('getFilterIdentifier', 'getFilterClassName', 'getListIdentifier'), array(), '', false, false, false);

        $mockFilterConfiguration->expects($this->once())
            ->method('getFilterClassName')
            ->will($this->returnValue(__CLASS__));

        $filterFactory = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Filter_FilterFactory'); /* @var $filterFactory Tx_PtExtlist_Domain_Model_Filter_FilterFactory */

        try {
            $filter = $filterFactory->createInstance($mockFilterConfiguration);
        } catch (Exception $e) {
            return;
        }
        $this->fail('No Exception thrown on creating non-filterinterface implementing class');
    }
}
