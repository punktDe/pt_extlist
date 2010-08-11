<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>, Christoph Ehscheidt <ehscheidt@punkt.de>
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
 * Testcase for default pager object
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Model_Pager_DefaultPager_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $pager;
	
	
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
		$this->pagerConfiguration = $this->configurationBuilderMock->buildPagerConfiguration();
		$this->pager = new Tx_PtExtlist_Domain_Model_Pager_DefaultPager($this->pagerConfiguration);
	}
	
	
		
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Pager_DefaultPager'));
    }
    
    
    
    public function testIsEnabled() {
    	$disabledPagerConfiguration = $this->getPagerConfigurationByArray(array('pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager', 'enabled' => '0'));
    	$disabledPager = new Tx_PtExtlist_Domain_Model_Pager_DefaultPager($disabledPagerConfiguration);
    	$this->assertFalse($disabledPager->isEnabled());
    	
    	$enabledPagerConfiguration = $this->getPagerConfigurationByArray(array('pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager', 'enabled' => '1'));
    	$enabledPager = new Tx_PtExtlist_Domain_Model_Pager_DefaultPager($enabledPagerConfiguration);
    	$this->assertTrue($enabledPager->isEnabled());
    }
    
    
    
    public function testCurrentPage() {
    	$this->pager->setCurrentPage(10);
    	$this->assertEquals($this->pager->getCurrentPage(), 10);
    }
    
    
    
    public function testItemCount() {
    	$this->pager->setItemCount(10);
    	$this->assertEquals($this->pager->getItemCount(),10);
    }
    
    
    
    public function testGetShowFirstLink() {
    	$this->assertFalse($this->pager->getShowFirstLink());
    	
    	$settings = $this->getPagerBaseSettings();
    	
    	$settings['showFirstLink'] = 1;
    	$settings['itemsPerPage'] = 10;
    	
    	$this->pager = new Tx_PtExtlist_Domain_Model_Pager_DefaultPager($this->getPagerConfigurationByArray($settings));
    	$this->assertTrue($this->pager->getShowFirstLink());
    }
    
    public function testGetShowLastLink() {
    	$this->assertFalse($this->pager->getShowLastLink());
    	
        $settings = $this->getPagerBaseSettings();
        
    	$settings['showLastLink'] = 1;
        $settings['itemsPerPage'] = 10;
    	
        $this->pager = new Tx_PtExtlist_Domain_Model_Pager_DefaultPager($this->getPagerConfigurationByArray($settings));
    	$this->assertTrue($this->pager->getShowLastLink());
    }
    
    
    
    public function testGetShowPreviousLink() {
    	$this->assertFalse($this->pager->getShowPreviousLink());
        
        $settings = $this->getPagerBaseSettings();
        
    	$settings['showPreviousLink'] = 1;
        $settings['itemsPerPage'] = 10;
    	
        $this->pager = new Tx_PtExtlist_Domain_Model_Pager_DefaultPager($this->getPagerConfigurationByArray($settings));
    	$this->assertTrue($this->pager->getShowPreviousLink());
    }
    
    
    
    public function testGetShowNextLink() {
    	$this->assertFalse($this->pager->getShowNextLink());
        
        $settings = $this->getPagerBaseSettings();
        
        $settings['showNextLink'] = 1;
        $settings['itemsPerPage'] = 10;
    	
        $this->pager = new Tx_PtExtlist_Domain_Model_Pager_DefaultPager($this->getPagerConfigurationByArray($settings));
    	$this->assertTrue($this->pager->getShowNextLink());
    }
    
    
    
    public function testGetPages() {
    	$settings = $this->getPagerBaseSettings();
    	$settings['itemsPerPage'] = 2;
    	
    	$this->pager = new Tx_PtExtlist_Domain_Model_Pager_DefaultPager($this->getPagerConfigurationByArray($settings));
    	$this->pager->setItemCount(21);
    	
    	$this->assertEquals(count($this->pager->getPages()),11);
    }
    
    
    
    public function testGetFirstItemIndex() {
    	$settings = $this->getPagerBaseSettings();
    	$settings['itemsPerPage'] = 10;
    	
    	$this->pager = new Tx_PtExtlist_Domain_Model_Pager_DefaultPager($this->getPagerConfigurationByArray($settings));
    	$this->pager->setItemCount(100);
    	$this->pager->setCurrentPage(2);
    	
    	$this->assertEquals($this->pager->getFirstItemIndex(), 11);
    }
    
    
    
    public function testGetLastItemIndex() {
    	$settings = $this->getPagerBaseSettings();
    	$settings['itemsPerPage'] = 10;
    	
    	$this->pager = new Tx_PtExtlist_Domain_Model_Pager_DefaultPager($this->getPagerConfigurationByArray($settings));
    	$this->pager->setCurrentPage(3);
    	$this->pager->setItemCount(100);
    	
    	$this->assertEquals($this->pager->getLastItemIndex(), 30);
    }
    
    
    
    protected function getPagerConfigurationByArray($pagerConfigurationArray) {
    	$configurationBuilderMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder', array('getPagerSettings', 'getListIdentifier'), array(array()), '', FALSE);
    	$configurationBuilderMock->expects($this->any())
    	   ->method('getListIdentifier')
    	   ->will($this->returnValue('test'));
    	$configurationBuilderMock->expects($this->any())
    	   ->method('getPagerSettings')
    	   ->will($this->returnValue($pagerConfigurationArray));
    	
    	$pagerConfiguration = new Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration($configurationBuilderMock);
    	return $pagerConfiguration;
    }
    
    
    
    protected function getPagerBaseSettings() {
    	return array('pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager');
    }
    
}
 
 ?>