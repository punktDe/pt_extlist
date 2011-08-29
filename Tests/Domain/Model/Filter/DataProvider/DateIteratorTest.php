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
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_DateIteratorTest extends Tx_PtExtlist_Tests_BaseTestcase {
    
	
	protected $defaultFilterSettings = array(
               'filterIdentifier' => 'timeSpanTest',
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_TimeSpanFilter',
               'partialPath' => 'Filter/Options/TimeSpanFilter',
               'fieldIdentifier' => 'field1',
               'invert' => '0',
					'dateIteratorStart' => '1227999510',
					'dateIteratorEnd' =>   '1314607478',
					'dateIteratorIncrement' => 'm',
					'dateIteratorFormat' => 'm',
       		 );
	
	
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}


	public function settingsDataProvider() {
		return array(
			'incrementMonth' => array(
				'settings' => array (
					'dateIteratorStart' => mktime(0,0,0,1,1,date('Y')),
					'dateIteratorEnd' => mktime(0,0,0,12,12,date('Y')),
					'dateIteratorIncrement' => 'm',
					'dateIteratorFormat' => 'm',
				),
				'result' => 'unknown'
			)
		);
	}


	public function incorrectSettingsDataProvider() {
		return array(
			'NoStartDateGiven' => array(
				'settings' => array (
					'dateIteratorStart' => '',
					'dateIteratorEnd' => '1314607478',
					'dateIteratorIncrement' => 'm',
					'dateIteratorFormat' => 'm',
				)
			),
			'NoEndDateGiven' => array(
				'settings' => array (
					'dateIteratorStart' => '1227999510',
					'dateIteratorEnd' => '',
					'dateIteratorIncrement' => 'm',
					'dateIteratorFormat' => 'm',
				)
			),
			'NoIncrementGiven' => array(
				'settings' => array (
					'dateIteratorStart' => '1227999510',
					'dateIteratorEnd' => '1314607478',
					'dateIteratorIncrement' => '',
					'dateIteratorFormat' => 'm',
				)
			),
			'NoFormatGiven' => array(
				'settings' => array (
					'dateIteratorStart' => '1227999510',
					'dateIteratorEnd' => '1314607478',
					'dateIteratorIncrement' => 'm',
					'dateIteratorFormat' => '',
				)
			),
			'EndDateIsBeforeStartDate' => array(
				'settings' => array (
					'dateIteratorStart' => '1314607478',
					'dateIteratorEnd' => '1227999510',
					'dateIteratorIncrement' => 'm',
					'dateIteratorFormat' => 'm',
				)
			),
			'IncrementSettingUnknown' => array(
				'settings' => array (
					'dateIteratorStart' => '1314607478',
					'dateIteratorEnd' => '1227999510',
					'dateIteratorIncrement' => 'X',
					'dateIteratorFormat' => 'm',
				)
			)
		);
	}



	/**
	 * @test
	 */
	public function settingsAreInjectedAndSet() {
		$dataProvider = $this->buildAccessibleDateIteratorDataProvider();

		$this->assertEquals($this->defaultFilterSettings['dateIteratorStart'], $dataProvider->_get('dateIteratorStart'));
		$this->assertEquals($this->defaultFilterSettings['dateIteratorEnd'], $dataProvider->_get('dateIteratorEnd'));
		$this->assertEquals($this->defaultFilterSettings['dateIteratorIncrement'], $dataProvider->_get('dateIteratorIncrement'));
		$this->assertEquals($this->defaultFilterSettings['dateIteratorFormat'], $dataProvider->_get('dateIteratorFormat'));
	}



	/**
	 * @test
	 * @dataProvider incorrectSettingsDataProvider
	 */
	public function initThrowsExceptionOnConfigurationError($settings) {

		$filterSettings = t3lib_div::array_merge_recursive_overrule($this->defaultFilterSettings, $settings);

		try {
			$dataProvider = $this->buildAccessibleDateIteratorDataProvider($filterSettings);
		} catch (Exception $e) {
			return;
		}

		$this->fail('No Exception thrown!');
	}


	/**
	 * @test
	 * @dataProvider settingsDataProvider
	 */
	public function getRenderedOptions($settings, $result) {

		$filterSettings = t3lib_div::array_merge_recursive_overrule($this->defaultFilterSettings, $settings);

		$dataProvider = $this->buildAccessibleDateIteratorDataProvider($filterSettings);
		Tx_ExtDebug::var_dump($dataProvider->getRenderedOptions(), '', '(Debug '. __CLASS__ .' :: '.__METHOD__.'<br/> in '. __FILE__.' :: '.__LINE__.' @ '.time().')');		

	}


	/**
	 * @param $filterSettings
	 * @return Tx_PtExtlist_Domain_Model_Filter_DataProvider_DateIterator
	 */
	protected function buildAccessibleDateIteratorDataProvider($filterSettings = NULL) {

		if(!$filterSettings) $filterSettings = $this->defaultFilterSettings;

		$accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DataProvider_DateIterator');
		$accessibleTimeSpanDataProvider = new $accessibleClassName;

		$filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $filterSettings, 'test');

		$accessibleTimeSpanDataProvider->injectFilterConfig($filterConfiguration);
		$accessibleTimeSpanDataProvider->init();

		return $accessibleTimeSpanDataProvider;
	}
}
?>