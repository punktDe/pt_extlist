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
 * Testcase for filterbox factory
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_FilterboxFactory_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
		Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilderMock);
	}
	
	public function testCreateInstanceByfilterboxConfiguration() {
		$filterboxConfigurationMock = new Tx_PtExtlist_Tests_Domain_Configuration_Filters_Stubs_FilterboxConfigurationCollectionMock();
		$filterboxConfiguration = $filterboxConfigurationMock->getfilterboxConfigurationMock('filterbox1');
        
        $filterbox = Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory::createInstance($filterboxConfiguration);
	}
	
	public function testCreateAccessableInstance() {
		
		
		$filterBoxConfig = $this->configurationBuilderMock->getFilterboxConfigurationByFilterboxIdentifier('testfilterbox');
		$filterBox = Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory::createInstance($filterBoxConfig);

		$accessableFilterBox = Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory::createAccessableInstance($filterBox);
		
		// AccessGroups are configured for filter2 -> should not be allowed to access.
		$this->assertTrue($accessableFilterBox->hasItem('filter1'));
		$this->assertFalse($accessableFilterBox->hasItem('filter2'));
		
	}
	
}

?>