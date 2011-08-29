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
 * Testcase for abstract groupDataFilter class
 *
 * @package Tests
 * @subpackage Somain\Model\Filter\DataProvider
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_TimeSpanTest extends Tx_PtExtlist_Tests_BaseTestcase {
    
	
	protected $defaultFilterSettings = array(
               'filterIdentifier' => 'timeSpanTest',
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_TimeSpanFilter',
               'partialPath' => 'Filter/Options/TimeSpanFilter',
               'fieldIdentifier' => 'field1',
               'invert' => '0'
       		 );
	
	
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}


	/**
	 * @test
	 */
	public function initDataProviderByTsConfigElementCountField() {

	}


	/**
	 * @param $filterSettings
	 * @return Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpan
	 */
	protected function buildAccessibleTimeSpanDataProvider($filterSettings) {

		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpan');
		$accessibleTimeSpanDataProvider = new $accessibleClassName;

		$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $filterSettings, 'test');

		$dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilderMock);

		$accessibleTimeSpanDataProvider->injectFilterConfig($filterConfiguration);
		$accessibleTimeSpanDataProvider->init();

		return $accessibleTimeSpanDataProvider;
	}
}
?>