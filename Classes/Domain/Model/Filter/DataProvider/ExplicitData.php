<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * Implements data provider for explicid defined data in typoscript
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class Tx_PtExtlist_Domain_Model_Filter_DataProvider_ExplicitData implements Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface {

	/**
	 * Filter configuration object
	 * 
	 * @var Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig 
	 */
	protected $filterConfig;
	
	
	/**
	 * array of options defined in typoscript
	 * 
	 * @var array
	 */
	protected $tsOptions;
	
	
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
		
		foreach ($this->tsOptions as $optionKey => $option) {
			
			$optionData['allDisplayFields'] = trim($option);
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
		tx_pttools_assert::isArray($this->tsOptions, array('message' => 'Options configured by TS has to be an array, '.gettype($this->tsOptions).' given! 1284142006'));
		
	}
	
}
?>