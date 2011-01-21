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
 * Testcase for filterbox collection
 * 
 * @author Michael Knoll 
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_FilterboxCollection_testcase extends Tx_Extbase_BaseTestcase {
	
	protected $configurationBuilderMock = null;
	
	
	
	public function setup() {
		$this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
	}
	
	
	
	public function testSetUp() {
		$filterboxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection($this->configurationBuilderMock);
	}
	
	
	
    public function testThrowExceptionOnAddingWrongDataType() {
    	$filterboxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection($this->configurationBuilderMock);
    	try {
    		$filterboxCollection->addItem('wrong_data_type', 'identifier');	
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No error has been thrown on adding wrong data type');
    }
    
    public function testSetListIdentifier() {
    	$filterboxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection();
    	$filterboxCollection->setListIdentifier('test123');
    	$listId = $filterboxCollection->getListIdentifier();
    	$this->assertEquals('test123', $listId);
    }
    
    public function testGettingFilterboxByFilterboxIdentifier() {
    	$filterboxCollection = new Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection($this->configurationBuilderMock);
    	$filterboxConfiguration = new Tx_PtExtlist_Tests_Domain_Configuration_Filters_Stubs_FilterboxConfigurationMock($this->configurationBuilderMock, 'test', array());
    	$filterbox = new Tx_PtExtlist_Domain_Model_Filter_Filterbox($filterboxConfiguration);
    	$filterboxCollection->addFilterBox($filterbox, 'test');
    	$this->assertTrue($filterboxCollection->getFilterboxByFilterboxIdentifier('test') == $filterbox);
    }
}

?>