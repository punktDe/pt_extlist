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
	

	/**
	 * @var string
	 */
	protected $proxyListIdentifier;

	
	/**
	 * @var string
	 */
	protected $proxyFilterBoxIdentifier;
	

	/**
	 * @var string
	 */
	protected $proxyFilterIdentifier;
	
	
	/**
	 * @var string
	 */
	protected $proxyFilterClass;
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilter()
	 */
	protected function initFilter() {}
	
	
	
	/**
	 * Copy the filter query from realFilter 
	 */
	protected function buildFilterQuery() {
		$realFilterObject = $this->getRealFilterObject();
		$this->filterQuery = $this->buildProxyQuery($realFilterObject->getFilterQuery());
	}
	
	
	
	/**
	 * Set the fieldIdentifier of the proxy filter as fieldIdentifier in the filterQuery
	 * 
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $filterQuery
	 * @return Tx_PtExtlist_Domain_QueryObject_Query $proxyQuery
	 */
	protected function buildProxyQuery(Tx_PtExtlist_Domain_QueryObject_Query $filterQuery) {
		$proxyQuery = new Tx_PtExtlist_Domain_QueryObject_Query(); 
		$criterias = $filterQuery->getCriterias();
		
		foreach($criterias as $criteria) { /* @var $criteria Tx_PtExtlist_Domain_QueryObject_SimpleCriteria */
			if(get_class($criteria) != 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria') {
				throw new Exception('Only simple criterias are supported at the moment in proxy filters. 1302864386');
			}
			
			$proxyQuery->addCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria($this->filterConfig->getFieldIdentifier()->getItemByIndex(0)->getTableFieldCombined(), 
																						$criteria->getValue(), 
																						$criteria->getOperator()));
		}
		
		return $proxyQuery;
	}

	

	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::setActiveState()
	 */
	protected function setActiveState() {
		$this->isActive = true;
	}
	
	
	
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

	
	public function persistToSession() {}
	
	protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {}
	
	
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
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($this->proxyListIdentifier);
		
		if($configurationBuilder->getListIdentifier() != $this->proxyListIdentifier) {
			throw new Exception('Tried to get configurationBuilder for listIdentifier ' . $this->proxyListIdentifier . ', but got ' . $configurationBuilder->getListIdentifier());
		}
		
		return $configurationBuilder;
	}
	
	
	
	/**
	 * Get the real filter object from realList
	 * 
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterInterface $realFilterObject
	 * @throws Exception
	 */
	protected function getRealFilterObject() {
		$realFilterConfig = $this->getRealFilterConfig();
		$realFilterObject = Tx_PtExtlist_Domain_Model_Filter_FilterFactory::createInstance($realFilterConfig);
		
		if(!is_a($realFilterObject, 'Tx_PtExtlist_Domain_Model_Filter_FilterInterface')) {
			throw new Exception('The real filter object of type "'.get_class($realFilterObject).'" is not a filter. 1302854030');
		}
		
		return $realFilterObject;
	}
	
	
	
	/**
	 * Returns filter breadcrumb of proxied filter
	 *
	 * @return Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb
	 */
	public function getFilterBreadCrumb() {
		return $this->getRealFilterObject()->getFilterBreadCrumb();
	}
	
	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_FilterInterface::getValue()
	 */
	public function getValue() {
		return $this->getRealFilterObject()->getValue();
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