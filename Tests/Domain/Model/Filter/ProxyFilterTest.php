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
 * Testcase for Proxy Filter class
 *
 * @package Tests
 * @subpackage Domain\Model\Filter
 * @author Daniel Lienert 
 */
 class Tx_PtExtlist_Tests_Domain_Model_Filter_ProxyFilter_testcase extends Tx_PtExtlist_Tests_BaseTestcase { 
	
 	public function setup() {
 		$this->initDefaultConfigurationBuilderMock();
 	}
 	
    public function testSetProxyConfigFromProxyPath() {
		$proxyFilter = $this->buildAccessibleProxyFilter();
    	$proxyFilter->_call('setProxyConfigFromProxyPath','testlist.testfilterbox.filter1');

		$this->assertEquals('testlist', $proxyFilter->_get('proxyListIdentifier'));
		$this->assertEquals('testfilterbox', $proxyFilter->_get('proxyFilterBoxIdentifier'));
		$this->assertEquals('filter1', $proxyFilter->_get('proxyFilterIdentifier'));				
	}

	
	
	public function testInitFilterByTsConfig() {
		$proxyFilter = $this->buildAccessibleProxyFilter();
		$proxyFilter->_call('initFilterByTsConfig');
		
		$this->assertEquals('testlist', $proxyFilter->_get('proxyListIdentifier'));
		$this->assertEquals('testfilterbox', $proxyFilter->_get('proxyFilterBoxIdentifier'));
		$this->assertEquals('filter1', $proxyFilter->_get('proxyFilterIdentifier'));		
	}
	
	
	
	public function testGetRealFilterConfig() {
		$proxyFilter = $this->buildAccessibleProxyFilter();
		$proxyFilter->_call('initFilterByTsConfig');
		$config = $proxyFilter->_call('getRealFilterConfig');
		$this->assertTrue(is_a($config, 'Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig'));
	}
	
	
	
 	public function testGetRealFilterObject() {
		$proxyFilter = $this->buildAccessibleProxyFilter();
		$proxyFilter->_call('initFilterByTsConfig');
		$filterObject = $proxyFilter->_call('getRealFilterObject');
		
		$this->assertTrue(is_a($filterObject, 'Tx_PtExtlist_Domain_Model_Filter_FilterInterface'));
		$this->assertTrue(is_a($filterObject, 'Tx_PtExtlist_Domain_Model_Filter_StringFilter'));
	}

	
	
	/**
	 * @test
	 */
	public function buildProxyQueryWithSimpleQuery() {
		$proxyFilter = $this->buildAccessibleProxyFilter();
				
		$realQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
		$realQuery->addCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('realField', '100', '>'));
		
		$proxyQuery = $proxyFilter->_call('buildProxyQuery', $realQuery); /* @var $proxyQuery Tx_PtExtlist_Domain_QueryObject_Query */
		
		$this->assertTrue(is_a($proxyQuery, 'Tx_PtExtlist_Domain_QueryObject_Query'));
		
		$firstCriteria = current($proxyQuery->getCriterias()); /* @var $firstCriteria Tx_PtExtlist_Domain_QueryObject_SimpleCriteria */
		$this->assertTrue(is_a($firstCriteria, 'Tx_PtExtlist_Domain_QueryObject_SimpleCriteria'));
		$this->assertEquals($firstCriteria->getField(),'tableName1.fieldName1');
		$this->assertEquals($firstCriteria->getOperator(),'>');
		$this->assertEquals($firstCriteria->getValue(),'100');
	}
	
	
	
	protected function buildAccessibleProxyFilter() {
		
		$proxyFilterMock = $this->getAccessibleMock('Tx_PtExtlist_Domain_Model_Filter_ProxyFilter', array('getConfigurationBuilderForRealList'), array());
		$proxyFilterMock->expects($this->any())
		    ->method('getConfigurationBuilderForRealList')
		    ->will($this->returnValue($this->configurationBuilderMock));
		
		$filterSettings = array('filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_ProxyFilter',
								'partialPath' => 'partialPath', 
								'proxyPath' => 'testlist.testfilterbox.filter1', 
								'fieldIdentifier' => 'field1',
								'filterIdentifier' => 'testProxyFilter',
							);
		
		$filterConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $filterSettings, 'someOtherBox');
		
		$proxyFilterMock->injectFilterConfig($filterConfig);
		
		return $proxyFilterMock;
	}
}
 
?>