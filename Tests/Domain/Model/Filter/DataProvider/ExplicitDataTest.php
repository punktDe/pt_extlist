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
 * Testcase for abstract explicitData class
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Daniel Lienert >
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_ExplicitDataTest extends Tx_PtExtlist_Tests_BaseTestcase {
    
	
	protected $defaultFilterSettings;
	
	
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();

		$this->defaultFilterSettings = array(
			'filterIdentifier' => 'test',
			'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_SelectFilter',
			'partialPath' => 'Filter/SelectFilter',
			'fieldIdentifier' => 'field1',
			'displayFields' => 'field1,field2',
			'filterField' => 'field3',
			'invert' => '0',
			'options' => array('x' => 'test',
									 'y' => 'hallo'
			)
		);
	}



	public function testGetRenderedOptionsSimple() {
		
		$explicitDataProvider = $this->buildAccessibleExplicitDataProvider();
		$options = $explicitDataProvider->getRenderedOptions();
		
		$this->assertEquals(2, count($options));
		$this->assertEquals(array('value' => 'test', 'selected' => false), $options['x']);
	}

	
	/** @test */
	public function getRenderedOptionsComplex() {

		$filterSettings = $this->defaultFilterSettings;
		unset($filterSettings['options']);
		$filterSettings['options'][10]['key'] = 'theKey';
		$filterSettings['options'][10]['value'] = 'theLabel';

		$explicitDataProvider = $this->buildAccessibleExplicitDataProvider($filterSettings);
		$options = $explicitDataProvider->getRenderedOptions();
		$this->assertEquals(array('value' => 'theLabel', 'selected' => false), $options['theKey']);
	}



	/**
	 * Build the dataprovider
	 * 
	 * @param array $filterSettings
	 * @return Tx_PtExtlist_Domain_Model_Filter_DataProvider_ExplicitData
	 */
	protected function buildAccessibleExplicitDataProvider($filterSettings = NULL) {
   		
		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DataProvider_ExplicitData');
		$accesibleExplicitDataProvider = new $accessibleClassName;

		if(!$filterSettings) $filterSettings = $this->defaultFilterSettings;

    	$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $filterSettings,'test');
    	
    	$dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilderMock);
    	
		$accesibleExplicitDataProvider->injectFilterConfig($filterConfiguration);
		$accesibleExplicitDataProvider->init();
   		
		return $accesibleExplicitDataProvider;
   }
   
}

?>