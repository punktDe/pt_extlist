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
 * Implements data provider for explicit defined data in typoscript
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 *
 * Have a look at the documentation for an example.
 *
 */
class Tx_PtExtlist_Domain_Model_Filter_DataProvider_ExplicitData extends Tx_PtExtlist_Domain_Model_Filter_DataProvider_AbstractDataProvider {

	/**
	 * array of options defined in typoscript
	 * 
	 * @var array
	 */
	protected $tsOptions;

	


	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::getRenderedOptions()
	 */
	public function getRenderedOptions() {
		$renderedOptions = array();
		
		foreach ($this->tsOptions as $key => $option) {
			if(is_array($option)) {

				if(t3lib_div::isFirstPartOfStr($option['value'], 'LLL:')) {
					$optionData['allDisplayFields'] = Tx_Extbase_Utility_Localization::translate($option['value'], '');
				} else {
					$optionData['allDisplayFields'] = $option['value'];
				}
				
				$optionKey = $option['key'];
			} else {
				$optionKey = $key;
				if(t3lib_div::isFirstPartOfStr($option, 'LLL:')) {
					$optionData['allDisplayFields'] = Tx_Extbase_Utility_Localization::translate($option, '');
				} else {
					$optionData['allDisplayFields'] = trim($option);
				}
			}

			$renderedOptions[$optionKey] = array('value' => Tx_PtExtlist_Utility_RenderValue::renderByConfigObjectUncached($optionData, $this->filterConfig),
													 'selected' => false);
		}
		
		return $renderedOptions;
	}


	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::init()
	 */
	public function init() {
		$this->initDataProviderByTsConfig($this->filterConfig->getSettings());
	} 

    
	
	/**
	 * Init the dataProvider by TS-conifg
	 * 
	 * @param array $filterSettings
	 */
	protected function initDataProviderByTsConfig($filterSettings) {
		
		$this->tsOptions = $this->filterConfig->getSettings('options');
		Tx_PtExtbase_Assertions_Assert::isArray($this->tsOptions, array('message' => 'Options configured by TS has to be an array, '.gettype($this->tsOptions).' given! 1284142006'));
		
	}
	
}
?>