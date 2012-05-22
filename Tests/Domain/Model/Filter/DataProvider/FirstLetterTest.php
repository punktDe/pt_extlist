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
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_FirstLetterTest extends Tx_PtExtlist_Tests_BaseTestcase {
    
	
	protected $defaultFilterSettings = array(
		'filterIdentifier' => 'firstLetterTest',
		'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_FirsrLetterFilter',
		'partialPath' => 'Filter/Options/FirstLetterFilter',
		'fieldIdentifier' => 'field1',
		'displayFields' => 'field1',
		'filterField' => 'field3',
		'invert' => '0',
		'addLettersIfMissing' => 'X,B'
    );
	
	

	public function setUp() {
		$this->initDefaultConfigurationBuilderMock();
	}
	


	/**
	 * @test
	 */
	public function getMissingLetters() {
		$firstLetterDataProvider = $this->buildAccessibleFirstLetterDataProvider($this->defaultFilterSettings);
		$missingLetters = $firstLetterDataProvider->_call('getMissingLetters');
		
		$this->assertEquals('X', $missingLetters[0]);
		$this->assertEquals('B', $missingLetters[1]);
	}


	/**
	 * @test
	 */
	public function addMissingLetters() {
		$firstLetterDataProvider = $this->buildAccessibleFirstLetterDataProvider($this->defaultFilterSettings);

		$missingLetters = array('F', 'B');
		
		$renderedOptions['A'] = array('value' => 'A','hasRecords' => TRUE, 'selected' => FALSE);
		$renderedOptions['G'] = array('value' => 'G','hasRecords' => TRUE, 'selected' => FALSE);
		
		$renderedOprionsWithMissingLetters = $firstLetterDataProvider->_call('addMissingLetters', $renderedOptions, $missingLetters);
		
		$this->assertEquals(4, count($renderedOprionsWithMissingLetters));

		$expectedMissingLetter = array('value' => 'B','hasRecords' => FALSE, 'selected' => FALSE);
		$this->assertEquals($expectedMissingLetter, $renderedOprionsWithMissingLetters['B']);
	}

	
	protected function buildAccessibleFirstLetterDataProvider($filterSettings) {

		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DataProvider_FirstLetter');
		$accesibleFirstLetterDataProvider = new $accessibleClassName;

		$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $filterSettings, 'test');

		$dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilderMock);

        $accesibleFirstLetterDataProvider->injectDataBackend($dataBackend);
		$accesibleFirstLetterDataProvider->injectFilterConfig($filterConfiguration);
		$accesibleFirstLetterDataProvider->init();

		return $accesibleFirstLetterDataProvider;
	}
}
?>