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
 * Testcase for breadcrumb collection
 *
 * @package Tests
 * @subpackage Domain\Model\BreadCrumbs
 * @author Michael Knoll
 * @see Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollection
 */
class Tx_PtExtlist_Tests_Domain_Model_BreadCrumbs_BreadCrumbCollectionTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /** @test */
    public function assertThatClassExists()
    {
        $this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollection'));
    }
    
    

    /** @test */
    public function addBreadCrumbAddsBreadCrumbToCollection()
    {
        $filterMock = $this->getMock('Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub', array(), array(), '', false);
        
        $breadCrumbMock = $this->getAccessibleMock('Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb', array('dummy'), array($filterMock), '', false);
        $breadCrumbMock->_set('filter', $filterMock);
        
        $breadCrumbCollection = new Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollection();
        $breadCrumbCollection->addBreadCrumb($breadCrumbMock);
    }
}
