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
 * Testcase for 
 *
 * @package Tests
 * @subpackage Domain\Model\BreadCrumbs
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_Model_BreadCrumbs_BreadCrumb_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
     
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb'));	
    }
    
    
    
    public function testConstruct() {
    	$filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter');
    	$breadCrumb = new Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb($filterMock);
    	$this->assertTrue(is_a($breadCrumb, 'Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb'));
    }
    
    
    
    public function testGetFilter() {
        $filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter');
        $breadCrumb = new Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb($filterMock);
        $this->assertEquals($breadCrumb->getFilter(), $filterMock);
    }
    
    
    
    public function testGetSetIsResettable() {
        $filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter');
        $breadCrumb = new Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb($filterMock);
        
        // Test default value
        $this->assertEquals($breadCrumb->getIsResettable(), true);
        
        // Test setter
        $breadCrumb->setIsResettable(false);
        $this->assertEquals($breadCrumb->getIsResettable(), false);
    }
    
    
    
    public function testGetSetMessage() {
    	$filterMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_StringFilter');
        $breadCrumb = new Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb($filterMock);
        
        $breadCrumb->setMessage('breadcrumb');
        $this->assertEquals($breadCrumb->getMessage(), 'breadcrumb');
    }
	
}

?>