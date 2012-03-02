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
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_TagCloudTest extends Tx_PtExtlist_Tests_BaseTestcase {
    
	
	protected $defaultFilterSettings = array(
               'filterIdentifier' => 'tagCloudTest', 
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_TagCloudFilter',
               'partialPath' => 'Filter/Options/TagCloudFilter',
               'fieldIdentifier' => 'field1',
               'displayFields' => 'field1',
               'filterField' => 'field3',
               'invert' => '0'
       		 );
	
	
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
 	/**
    * @test
    */
    public function initDataProviderByTsConfigElementCountField() {
    	$filterSettings = $this->defaultFilterSettings;
    	$tagCloudDataProvider = $this->buildAccessibleTagCloudDataProvider($filterSettings);
    	$this->assertEquals($tagCloudDataProvider->_get('elementCountField'), NULL);
    	
    	$filterSettings['countFieldIdentifier'] = 'field2';
    	$tagCloudDataProvider = $this->buildAccessibleTagCloudDataProvider($filterSettings);
    	$this->assertEquals($tagCloudDataProvider->_get('elementCountField')->getField(), 'fieldName2');
    }

	

	protected function buildAccessibleTagCloudDataProvider($filterSettings) {

		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DataProvider_TagCloud');
		$accesibleTagCloudDataProvider = new $accessibleClassName;

		$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $filterSettings, 'test');

		$dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilderMock);

        $accesibleTagCloudDataProvider->injectDataBackend($dataBackend);
		$accesibleTagCloudDataProvider->injectFilterConfig($filterConfiguration);
		$accesibleTagCloudDataProvider->init();

		return $accesibleTagCloudDataProvider;
	}
}
?>