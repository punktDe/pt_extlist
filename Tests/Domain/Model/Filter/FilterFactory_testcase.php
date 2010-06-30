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
 * Class implements testcase for filterfactory
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_FilterFactory_testcase extends Tx_Extbase_BaseTestcase {
	
	public function testCreateInstanceByConfiguration() {
		$filterConfigurationMock = new Tx_PtExtlist_Tests_Domain_Configuration_Filters_Stubs_FilterboxConfigurationCollectionMock();
		$filter = Tx_PtExtlist_Domain_Model_Filter_FilterFactory::createInstanceByFilterConfigAndListIdentifier($filterConfigurationMock->getFilterConfigurationMock('filter1'), 'test');
		$this->assertEquals($filter->getFilterIdentifier(), 'filter1');
	    $this->assertEquals($filter->getListIdentifier(), 'test');	
	}
	
	public function testCreateNonInterfaceImplementingClass() {
		$mockFilterConfiguration = $this->getMock(
            'Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig',
            array('getFilterIdentifier', 'getFilterClassName'),array(),'',FALSE,FALSE,FALSE);
        
        $mockFilterConfiguration->expects($this->once())
            ->method('getFilterIdentifier')
            ->will($this->returnValue('testFilterIdentifier'));
        
        $mockFilterConfiguration->expects($this->once())
            ->method('getFilterClassName')
            ->will($this->returnValue(__CLASS__));
        
        try {
            $filter = Tx_PtExtlist_Domain_Model_Filter_FilterFactory::createInstanceByFilterConfigAndListIdentifier($mockFilterConfiguration, 'test');
        } catch(Exception $e) {
        	return;
        }
        $this->fail('No Exception thrown on creating non-filterinterface implementing class');
	}
	
}

?>