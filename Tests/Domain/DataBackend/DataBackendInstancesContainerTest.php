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
 * Testcase for data backend instances container
 * 
 * @package Tests
 * @subpackage Domain\DataBackend
 * @author Michael Knoll
 * @see  Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_DataBackendInstancesContainerTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /** @test */
    public function classExists()
    {
        $instancesContainer = new Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer();
    }



    /** @test */
    public function classIsInstantiatedAsSingleton()
    {
        $instancesContainer1 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer');
        $instancesContainer2 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer');
        $this->assertEquals($instancesContainer1, $instancesContainer2);
    }



    /** @test */
    public function addAddsDataBackendInstanceAsExcepted()
    {
        $listIdentifier = 'testListIdentifier';
        $dataBackenMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend', array('getListIdentifier'), array(), '', false);
        $dataBackenMock->expects($this->any())->method('getListIdentifier')->will($this->returnValue($listIdentifier));
        $instancesContainer = new Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer();
        $instancesContainer->add($dataBackenMock);
        $this->assertTrue($instancesContainer->contains($listIdentifier));
        $this->assertEquals($dataBackenMock, $instancesContainer->get($listIdentifier));
    }



    /** @test */
    public function addThrowsExceptionIfSameBackendIsAddedTwice()
    {
        $listIdentifier = 'testListIdentifier';
        $dataBackenMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend', array('getListIdentifier'), array(), '', false);
        $dataBackenMock->expects($this->any())->method('getListIdentifier')->will($this->returnValue($listIdentifier));
        $instancesContainer = new Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer();
        $instancesContainer->add($dataBackenMock);
        try {
            $instancesContainer->add($dataBackenMock);
        } catch (Exception $e) {
            return;
        }
        $this->fail('No exception has been thrown whent rying to add the same backend twice to the container');
    }



    /** @test */
    public function getReturnNullOnNonExistingDataBackend()
    {
        $instancesContainer = new Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer();
        $this->assertEquals(null, $instancesContainer->get('testblubber'));
    }



    /** @test */
    public function setOverwritesInstances()
    {
        $listIdentifier = 'testListIdentifier';
        $dataBackenMock = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend', array('getListIdentifier'), array(), '', false);
        $dataBackenMock->expects($this->any())->method('getListIdentifier')->will($this->returnValue($listIdentifier));
        $instancesContainer = new Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer();

        $instancesContainer->add($dataBackenMock);
        $this->assertEquals($dataBackenMock, $instancesContainer->get($listIdentifier));

        $dataBackenMock2 = $this->getMock('Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend', array('getListIdentifier'), array(), '', false);
        $dataBackenMock2->expects($this->any())->method('getListIdentifier')->will($this->returnValue($listIdentifier));

        $instancesContainer->set($dataBackenMock2);
        $this->assertEquals($dataBackenMock2, $instancesContainer->get($listIdentifier));
    }
}
