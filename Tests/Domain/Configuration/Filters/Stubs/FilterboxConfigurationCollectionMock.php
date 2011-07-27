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
 * Class implements mock object for filterbox configuration collection
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Filters_Stubs_FilterboxConfigurationCollectionMock extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
	public function getFilterboxConfigurationCollectionMock() {
		$filterBoxConfigurationCollection = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfigCollection();
		$filterBoxConfigurationCollection->addItem($this->getFilterboxConfigurationMock('filterbox1'));
		$filterBoxConfigurationCollection->addItem($this->getFilterboxConfigurationMock('filterbox2'));
		return $filterBoxConfigurationCollection;
	}
	
	
	
	public function getFilterboxConfigurationMock($filterBoxIdentifier) {
		
		$configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
		
		$mockFilterConfiguration1 = $this->getFilterConfigurationMock('filter1', $filterBoxIdentifier);
		$mockFilterConfiguration2 = $this->getFilterConfigurationMock('filter2', $filterBoxIdentifier);
		
        $filterBoxConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($configurationBuilderMock, $filterBoxIdentifier, array());
        
        $filterBoxConfiguration->addItem($mockFilterConfiguration1);
        $filterBoxConfiguration->addItem($mockFilterConfiguration2);
        
        return $filterBoxConfiguration;
        
	}
	
	
	
	public function getFilterConfigurationMock($filterIdentifier, $filterboxIdentifer) {
		$mockFilterConfiguration1 = $this->getMock(
            'Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig',
            array('getFilterIdentifier','getConfigurationBuilder', 'getFilterClassName', 'getListIdentifier', 'getFilterboxIdentifier', 'isAccessable'),
            array(Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance(), 
                $this->extBaseSettings, 'test'),'',FALSE,FALSE);
            
        $mockFilterConfiguration1->expects($this->any())
            ->method('getFilterIdentifier')
            ->will($this->returnValue($filterIdentifier));
            
        $mockFilterConfiguration1->expects($this->any())
            ->method('getConfigurationBuilder')
            ->will($this->returnValue($this->configurationBuilderMock));
        
        $mockFilterConfiguration1->expects($this->any())
            ->method('getFilterClassName')
            ->will($this->returnValue('Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub'));
            
        $mockFilterConfiguration1->expects($this->any())
            ->method('getListIdentifier')
            ->will($this->returnValue('test'));
            
        $mockFilterConfiguration1->expects($this->any()) 
            ->method('getFilterboxIdentifier')
            ->will($this->returnValue($filterboxIdentifer));
            
        $mockFilterConfiguration1->expects($this->any()) 
            ->method('isAccessable')
            ->will($this->returnValue(true));
            
        return $mockFilterConfiguration1;
         
	}
	
}


?>