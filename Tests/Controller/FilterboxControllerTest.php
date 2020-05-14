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
 * Testcase for filterbox controller class
 *
 * @package Tests
 * @subpackage Controller
 */
class Tx_PtExtlist_Tests_Controller_FilterboxControllerTestcase extends Tx_PtExtlist_Tests_BaseTestcase
{
    public function setup()
    {
        $this->initDefaultConfigurationBuilderMock();
    }



    public function testSetup()
    {
        $this->assertTrue(class_exists('Tx_PtExtlist_Controller_FilterboxController', 'Class Tx_PtExtlist_Controller_FilterboxController does not exist!'));
    }



    public function testThrowExceptionOnNonExistingFilterboxIdentifier()
    {
        try {
            $mockController = $this->getMock(
                $this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'),
                ['dummy'], [], '', false);
            $mockController->injectConfigurationmanager($this->objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManager'));
            $this->fail('No exception has been thrown, when no filterbox identifier has been set!');
        } catch (Exception $e) {
            return;
        }
    }



    public function testShowAction()
    {
        $mockFilterbox = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_Filterbox', [], [], '', false);

        $mockView = $this->getMock(
            '\TYPO3\CMS\Fluid\View\TemplateView',
            ['assign'], [], '', false);
        //$mockView->expects($this->once())->method('assign')->with('filterbox', $mockFilterbox);

        $mockController = $this->getMock(
            $this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'),
            ['dummy'], [], '', false);
        $mockController->_set('view', $mockView);
        $mockController->_set('filterbox', $mockFilterbox);

        $mockController->showAction();
    }


    /** @test */
    public function submitActionForwardsToShowWhenValidationFails()
    {

        // TODO: Abstract controller holds a data backend which is used in the called action -> addd a databackend to the controller mock
        $this->markTestIncomplete();

        $filterboxConfigMock = $this->getMock(
            Tx_PtExtlist_Domain_Model_Filter_Filterbox,
            ['doRedirectOnSubmit'],
            [],
            '',
            false
        );

        $filterboxConfigMock->expects($this->any())->method('doRedirectOnSubmit')->will($this->returnValue(false));

        $filterboxMock = $this->getMock(Tx_PtExtlist_Domain_Model_Filter_Filterbox, ['validate', 'getFilterValidationErrors', 'getFilterboxConfiguration'], [], '', false);
        $filterboxMock->expects($this->once())->method('validate')->will($this->returnValue(false));

        $filterboxControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'), ['forward', 'resetSorter'], [], '', false);
        $filterboxControllerMock->expects($this->any())->method('forward')->with('show');
        $filterboxControllerMock->expects($this->once())->method('resetSorter');

        $filterboxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', ['resetIsSubmittedFilterbox'], [], '', false);
        $filterboxCollectionMock->expects($this->once())->method('resetIsSubmittedFilterbox');

        $viewMock = $this->getMock('\TYPO3\CMS\Fluid\View\TemplateView', ['assign'], [], '', false);
        $viewMock->expects($this->once())->method('assign')->with('filtersDontValidate', true);

        $pagerCollectionMock = $this->getMock('PagerCollection', ['reset'], [], '', false);
        $pagerCollectionMock->expects($this->once())->method('reset');

        $configurationBuilderMock = $this->getMock('ConfigurationBuilder', ['getSettings'], [], '', false);
        $configurationBuilderMock->expects($this->any())->method('getSettings')->will($this->returnValue(''));

        $filterboxControllerMock->_set('view', $viewMock);
        $filterboxControllerMock->_set('pagerCollection', $pagerCollectionMock);
        $filterboxControllerMock->_set('filterbox', $filterboxMock);
        $filterboxControllerMock->_set('filterboxCollection', $filterboxCollectionMock);
        $filterboxControllerMock->_set('configurationBuilder', $configurationBuilderMock);

        $filterboxControllerMock->submitAction();
    }



    /** @test */
    public function submitActionForwardsToShowWhenValidationSucceeds()
    {

        // TODO: Abstract controller holds a data backend which is used in the called action -> addd a databackend to the controller mock
        $this->markTestIncomplete();

        $filterboxConfigMock = $this->getMock(
            Tx_PtExtlist_Domain_Model_Filter_Filterbox,
            ['getRedirectOnSubmitPageId', 'getRedirectOnSubmitControllerName', 'getRedirectOnSubmitAction', 'doRedirectOnSubmit'],
            [],
            '',
            false
        );
        $filterboxConfigMock->expects($this->any())->method('doRedirectOnSubmit')->will($this->returnValue(false));

        $filterboxMock = $this->getMock(Tx_PtExtlist_Domain_Model_Filter_Filterbox, ['validate', 'getFilterValidationErrors', 'getFilterboxConfiguration'], [], '', false);
        $filterboxMock->expects($this->once())->method('validate')->will($this->returnValue(true));

        $filterboxCollectionMock = $this->getMock('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', ['resetIsSubmittedFilterbox'], [], '', false);
        $filterboxCollectionMock->expects($this->once())->method('resetIsSubmittedFilterbox');

        $filterboxControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'), ['forward', 'resetSorter'], [], '', false);
        $filterboxControllerMock->expects($this->once())->method('forward')->with('show');
        $filterboxControllerMock->expects($this->once())->method('resetSorter');
        $filterboxControllerMock->_set('filterboxCollection', $filterboxCollectionMock);

        $pagerCollectionMock = $this->getMock('PagerCollection', ['reset'], [], '', false);
        $pagerCollectionMock->expects($this->once())->method('reset');

        $configurationBuilderMock = $this->getMock('ConfigurationBuilder', ['getSettings'], [], '', false);
        $configurationBuilderMock->expects($this->any())->method('getSettings')->will($this->returnValue(''));

        $filterboxControllerMock->_set('pagerCollection', $pagerCollectionMock);
        $filterboxControllerMock->_set('filterbox', $filterboxMock);
        $filterboxControllerMock->_set('configurationBuilder', $configurationBuilderMock);

        $filterboxControllerMock->submitAction();
    }



    /** @test */
    public function resetActionCallsResetInFilterboxGivenInParameter()
    {

        // TODO: Abstract controller holds a data backend which is used in the called action -> addd a databackend to the controller mock
        $this->markTestIncomplete();

        $filterboxMock1 = $this->getMock(Tx_PtExtlist_Domain_Model_Filter_Filterbox, ['reset'], [], '', false);
        $filterboxMock1->expects($this->once())->method('reset');

        $filterboxMock2 = $this->getMock(Tx_PtExtlist_Domain_Model_Filter_Filterbox, ['reset'], [], '', false);

        $filterboxCollectionMock = $this->getMock(Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection, ['hasItem', 'getFilterboxByFilterboxIdentifier'], [], '', false);
        /* @var $filterboxCollectionMock Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection */
        $filterboxCollectionMock->expects($this->any())->method('hasItem')->with($this->equalTo('toBeResetted'))->will($this->returnValue(true));
        $filterboxCollectionMock->expects($this->any())->method('getFilterboxByFilterboxIdentifier')->with($this->equalTo('toBeResetted'))->will($this->returnValue($filterboxMock1));

        $filterboxCollectionMock->addFilterbox($filterboxMock1, 'toBeResetted');
        $filterboxCollectionMock->addFilterBox($filterboxMock2, 'notToBeResetted');

        $pagerCollectionMock = $this->getMock('PagerCollection', ['reset'], [], '', false);
        $pagerCollectionMock->expects($this->once())->method('reset');

        $filterboxControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'), ['redirect', 'getFilterboxForControllerSettings', 'resetSorter'], [], '', false);
        $filterboxControllerMock->_set('filterboxIdentifier', 'test');
        $filterboxControllerMock->_set('filterboxCollection', $filterboxCollectionMock);
        $filterboxControllerMock->_set('pagerCollection', $pagerCollectionMock);
        $filterboxControllerMock->expects($this->once())->method('resetSorter');
        $filterboxControllerMock->expects($this->once())->method('redirect')->with('show');

        $filterboxControllerMock->resetAction('toBeResetted');
    }
    
    
    
    /* @test */
    public function submitActionRedirectsToGivenDestinationIfGivenInTypoScript()
    {
        $redirectControllerName = 'testController';
        $redirectActionName = 'testAction';
        $redirectPageUid = '10';
        
        $filterboxConfigMock = $this->getMock(
            Tx_PtExtlist_Domain_Model_Filter_Filterbox,
            ['getRedirectOnSubmitPageId', 'getRedirectOnSubmitControllerName', 'getRedirectOnSubmitAction', 'doRedirectOnSubmit'],
            [],
            '',
            false
        );
        $filterboxConfigMock->expects($this->any())->method('doRedirectOnSubmit')->will($this->returnValue(true));
        $filterboxConfigMock->expects($this->any())->method('getRedirectOnSubmitPageId')->will($this->returnValue($redirectPageUid));
        $filterboxConfigMock->expects($this->any())->method('getRedirectOnSubmitControllerName')->will($this->returnValue($redirectControllerName));
        $filterboxConfigMock->expects($this->any())->method('getRedirectOnSubmitAction')->will($this->returnValue($redirectActionName));
        
        
        $filterboxMock = $this->getMock(Tx_PtExtlist_Domain_Model_Filter_Filterbox, ['reset', 'getFilterboxConfiguration'], [], '', false);
        $filterboxMock->expects($this->once())->method('reset');
        $filterboxMock->expects($this->any())->method('getFilterboxConfiguration')->will($this->returnValue($filterboxConfigMock));

        $pagerCollectionMock = $this->getMock('PagerCollection', ['reset'], [], '', false);
        $pagerCollectionMock->expects($this->once())->method('reset');

        $filterboxControllerMock = $this->getMock($this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'), ['redirect', 'getFilterboxForControllerSettings'], [], '', false);
        $filterboxControllerMock->_set('filterboxIdentifier', 'test');
        $filterboxControllerMock->_set('filterbox', $filterboxMock);
        $filterboxControllerMock->_set('pagerCollection', $pagerCollectionMock);
        $filterboxControllerMock->expects($this->once())->method('redirect')->with($redirectActionName, $redirectControllerName, null, null, $redirectPageUid);
    }
}
