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
 * Testcase for data provider factory
 *
 * @package Tests
 * @subpackage Domain\Model\Filter\DataProvider
 * @author Daniel Lienert
 * @author Michael Knoll
 * @see Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderFactory
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_DataProviderFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    protected $defaultFilterSettings;



    public function setup()
    {
        $this->initDefaultConfigurationBuilderMock();
    }



    /** @test */
    public function classExists()
    {
        $this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderFactory'));
    }



    /** @test */
    public function createInstanceReturnsExpectedClass()
    {
        $filterSettings = array(
            'filterIdentifier' => 'test',
            'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
            'partialPath' => 'Filter/SelectFilter',
            'fieldIdentifier' => 'field1',
            'displayFields' => 'field1,field2',
            'filterField' => 'field3',
            'invert' => '0'
        );

        $dataProviderFactory = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderFactory');
        /* @var $ataProviderFactory Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderFactory */

        // We need to do this to initially create a configuration builder! TODO remove this, once we have proper DI!
        $dataBackendFactory = $this->getDataBackendFactoryMockForListConfigurationAndListIdentifier($this->configurationBuilderMock->getSettings(), $this->configurationBuilderMock->getListIdentifier());
        // Create singleton instance of dataBackendFactory for corresponding configuration
        $dataBackendFactory->getDataBackendInstanceByListIdentifier($this->configurationBuilderMock->getListIdentifier());

        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $filterSettings, 'test');

        $dataProviderInstance = $dataProviderFactory->createInstance($filterConfiguration);

        $this->assertTrue(is_a($dataProviderInstance, 'Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface'));
    }
}
