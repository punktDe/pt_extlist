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

require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pt_extlist').'Tests/Domain/Model/Filter/Fixtures/TestDataProvider.php';

/**
 * Testcase for group filter
 *
 * @package Tests
 * @subpackage pt_extlist
 * @author Michael Knoll
 * @see Tx_PtExtlist_Domain_Model_Filter_SelectFilter
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_SelectFilterTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    public function setup()
    {
        $this->initDefaultConfigurationBuilderMock();
    }
    
    
    
    public function testSetup()
    {
        $this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Filter_SelectFilter'));
        $selectFilter = new Tx_PtExtlist_Domain_Model_Filter_SelectFilter();
        $this->assertTrue(is_a($selectFilter, 'Tx_PtExtlist_Domain_Model_Filter_FilterInterface'));
    }


    /** @test */
    public function testGetMultiple()
    {
        $selectFilter = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Filter_SelectFilter'); /* @var $selectFilter Tx_PtExtlist_Domain_Model_Filter_SelectFilter */
        $selectFilter->_injectGpVarsAdapter(new PunktDe_PtExtbase_State_GpVars_GpVarsAdapter('pt_extlist'));
        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
            $this->configurationBuilderMock,
            [
                  'filterIdentifier' => 'test',
                  'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
                  'partialPath' => 'Filter/SelectFilter',
                  'fieldIdentifier' => 'field1',
                  'filterField' => 'field2',
                  'displayFields' => 'field1',
                  'excludeFilters' => 'filterbox1.filter1',
                  'multiple' => 1
            ],
            'test'
        );
        $selectFilter->_injectFilterConfig($filterConfiguration);
        $sessionManagerMock = $this->getMock('PunktDe_PtExtbase_State_Session_SessionPersistenceManager', [], [], '', false);

        $dataBackendMock = new Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend($this->configurationBuilderMock);
        $dataBackendMock->_injectFieldConfigurationCollection($this->configurationBuilderMock->buildFieldsConfiguration());
        $selectFilter->_injectDataBackend($dataBackendMock);

        $selectFilter->init();
        $this->assertEquals(1, $selectFilter->getMultiple());
    }



    /**
     * @return array
     */
    public function displayValueDataProvider()
    {
        return [
            'none' => ['values' => [], 'selectedValues' => [], 'expected' => ''],
            'one' => ['values' => ['key' => 'test', 'otherKey' => 'test2'], 'selectedValues' => ['key'], 'expected' => 'test'],
            'multiple' => ['values' => ['key1' => 'val1', 'key2' => 'val2', 'key3' => 'val3'],  'selectedValues' => ['key1', 'key2'], 'expected' => 'val1, val2'],
        ];
    }



    /**
     * @test
     * @dataProvider displayValueDataProvider
     * @param $values
     * @param $selectedValues
     * @param $selectedValues
     * @param $expected
     */
    public function displayValue($values, $selectedValues, $expected)
    {
        $abstractOptionsFilter = $this->buildAcccessibleSelectFilterWithTestDataProvider($values, $selectedValues);

        $abstractOptionsFilter->_set('filterValues', $selectedValues);
        $this->assertEquals($expected, $abstractOptionsFilter->getDisplayValue());
    }






    /**
     * @param null $testData
     * @return Tx_PtExtlist_Domain_Model_Filter_SelectFilter
     */
    protected function buildAcccessibleSelectFilterWithTestDataProvider($testData = null)
    {
        if (!$testData) {
            $testData = ['key1' => 'value1', 'key2' => 'value2'];
        }

        $accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_SelectFilter');

        $abstractOptionsFilter = $this->getMockBuilder($accessibleClassName)
            ->setMethods(['buildDataProvider'])
            ->getMock();

        $allFilterSettings = $this->configurationBuilderMock->getSettings('filters');
        $filterSettings = $allFilterSettings['testfilterbox']['filterConfigs']['10'];

        $abstractOptionsFilter->_set('filterConfig', new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig(
            $this->configurationBuilderMock,
            $filterSettings, 'test')
        );

        $dataProviderFixture = new Tx_PtExtlist_Tests_Domain_Model_Filter_Fixture_TestDataProvider();
        $dataProviderFixture->setOptions($testData);

        $abstractOptionsFilter->expects($this->once())->method('buildDataProvider')->will($this->returnValue($dataProviderFixture));

        return $abstractOptionsFilter;
    }
}
