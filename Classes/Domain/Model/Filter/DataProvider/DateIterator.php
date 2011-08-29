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
 * Implements data provider for grouped list data
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class Tx_PtExtlist_Domain_Model_Filter_DataProvider_DateIterator implements Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface {

	/**
	 * Filter configuration object
	 * 
	 * @var Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig 
	 */
	protected $filterConfig;

	/**
	 * @var string
	 */
	protected $dateIteratorStart;

	/**
	 * @var string
	 */
	protected $dateIteratorEnd;


	/**
	 * @var string
	 */
	protected $dateIteratorIncrement;


	/**
	 * @var string
	 */
	protected $dateIteratorFormat;



	/**
	 * Set and validate the config values
	 *
	 * @return void
	 */
	protected function initByTsConfig() {

		$this->dateIteratorStart = (int) $this->filterConfig->getSettings('dateIteratorStart');
			Tx_PtExtbase_Assertions_Assert::isPositiveInteger($this->dateIteratorStart, false, array('message' => 'The Value dateIteratorStart is not given. 1314608757'));

		$this->dateIteratorEnd = (int) $this->filterConfig->getSettings('dateIteratorEnd');
			Tx_PtExtbase_Assertions_Assert::isPositiveInteger($this->dateIteratorEnd,false, array('message' => 'The Value dateIteratorEnd is not given. 1314608758'));

		$this->dateIteratorFormat = $this->filterConfig->getSettings('dateIteratorFormat');
			Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->dateIteratorFormat, array('message' => 'The Value dateIteratorFormat is not given. 1314608759'));

		$this->dateIteratorIncrement = strtolower(trim($this->filterConfig->getSettings('dateIteratorIncrement')));
			Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->dateIteratorIncrement, array('message' => 'The Value dateIteratorIncrement is not given. 1314608760'));
			Tx_PtExtbase_Assertions_Assert::isTrue(in_array($this->dateIteratorIncrement,array('s', 'i', 'h', 'd', 'm', 'y')), array('message' => "The parameter dateIteratorIncrement has to be out of 's', 'i', 'h', 'd', 'm', 'y'"));

		Tx_PtExtbase_Assertions_Assert::isTrue($this->dateIteratorStart < $this->dateIteratorEnd, array('message' => 'The Value dateIteratorStart ('.$this->dateIteratorStart.') is higher than dateIteratorEnd ('.$this->dateIteratorEnd.')'));

	}


	/****************************************************************************************************************
	 * Methods implementing "Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface"
	 *****************************************************************************************************************/
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::init()
	 */
	public function init() {
		$this->initByTsConfig();
	}

		
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::injectFilterConfig()
	 */
	public function injectFilterConfig(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig) {
		$this->filterConfig = $filterConfig;
	}
	
	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::getRenderedOptions()
	 */
	public function getRenderedOptions() {

		$renderedOptions = array();
		$timeSpanList = $this->buildTimeStampList();

		foreach ($timeSpanList as $key => $timestamp) {

			$optionData['allDisplayFields'] = strftime($this->dateIteratorFormat, $timestamp);
			$renderedOptions[$key] = array('value' => Tx_PtExtlist_Utility_RenderValue::renderByConfigObjectUncached($optionData, $this->filterConfig),
													 'selected' => false);
		}

		return $renderedOptions;

	}


	/**
	 * @return array
	 */
	protected function buildTimeStampList() {

		$iterationStart = $this->dateIteratorStart;
		$timeStampList = array();

		$iterator = array_combine(array('s', 'i', 'h', 'd', 'm', 'y'), array(0, 0, 0, 0, 0, 0));
		$iterator[$this->dateIteratorIncrement] = 1;

		while($iterationStart <= $this->dateIteratorEnd) {
			
			$iterationEnd = mktime(
				date('s', $iterationStart) + $iterator['s'],
				date('i', $iterationStart) + $iterator['i'],
				date('h', $iterationStart) + $iterator['h'],
				date('m', $iterationStart) + $iterator['m'],
				date('d', $iterationStart) + $iterator['d'],
				date('y', $iterationStart) + $iterator['y']
			);

			$timeStampList[$iterationStart . ',' . $iterationEnd] = $iterationStart;
			$iterationStart = $iterationEnd;
		}

		return $timeStampList;
	}
}
?>