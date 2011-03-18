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
 * Class implements an proxy filter to get data from a filter of an other list
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_ProxyFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractFilter {
	
	
	/**
	 * Holds identifier of field that should be filtered
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	protected $fieldIdentifier;
	
	protected $proxyListIdentifier;
	
	protected $proxyFilterBoxIdentifier;
	
	protected $proxyFilterIdentifier;
	
	protected $proxyFilterClass;
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilter()
	 */
	protected function initFilter() {}

	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::buildFilterCriteria()
	 */
	protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::setActiveState()
	 */
	protected function setActiveState() {}
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilterByGpVars()
	 *
	 */
	protected function initFilterByGpVars() {}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilterBySession()
	 *
	 */
	protected function initFilterBySession() {}
	
	
	public function reset() {}
	
	public function getFilterBreadCrumb() {}
	
	public function getFilterValueForBreadCrumb() {}
	
	public function persistToSession() {}
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilterByTsConfig()
	 *
	 */
	protected function initFilterByTsConfig() {
		$filterSettings = $this->filterConfig->getSettings();
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($filterSettings['proxyPath'], array('message' => 'No proxy path to the proxy filter set. 1288033657'));
		
		$this->setProxyConfigFromProxyPath(trim($filterSettings['proxyPath']));
	}
	
	
	protected function setProxyConfigFromProxyPath($proxyPath) {
		list($this->proxyListIdentifier, $this->proxyFilterBoxIdentifier, $this->proxyFilterIdentifier) = explode('.', $proxyPath);
		
		if(!$this->proxyListIdentifier || !$this->proxyFilterBoxIdentifier || !$this->proxyFilterIdentifier) {
			throw new Exception("Either proxyListIdentifier, proxyFilterBoxIdentifier or proxyFilterIdentifier not given! 1288352507");
		}
	}
	
	
	/**
	 * Get the Configurationbuilder for the real list
	 * 
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected function getConfigurationBuilderForRealList() {
		return  Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($this->proxyListIdentifier);
	}
	
	protected function getRealFilterObject() {
		$realFilterConfig = $this->getRealFilterConfig();
		$realFilter = Tx_PtExtlist_Domain_Model_Filter_FilterFactory::createInstance($realFilterConfig);
	}
	
	
	/**
	 * 
	 * @return Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig
	 */
	protected function getRealFilterConfig() {
		$configurationBuilder = $this->getConfigurationBuilderForRealList();
		$realFilterConfig = $configurationBuilder->buildFilterConfiguration()
												 ->getItemById($this->proxyFilterBoxIdentifier)
												 ->getFilterConfigByFilterIdentifier($this->proxyFilterIdentifier);
		return $realFilterConfig;
	}
	
	
	
}