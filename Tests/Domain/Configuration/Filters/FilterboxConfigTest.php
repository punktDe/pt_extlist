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
 * Class implementing testcase for filterbox configuration factory
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Filters_FilterboxConfig_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setUp() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig'), 'Class Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig does not exist!');
    }
    
    public function testGetFilterIdentifier() {
    	$filterBoxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array());
    	$this->assertEquals('testfilterbox', $filterBoxConfig->getFilterboxIdentifier());
    }
    
    public function testGetshowReset() {
    	$filterBoxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array());
    	$this->assertEquals(true, $filterBoxConfig->getShowReset());
    	
    	$filterBoxConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'testfilterbox', array('showReset' => 0));
    	$this->assertEquals(false, $filterBoxConfig->getShowReset());
    }
	
}

?>