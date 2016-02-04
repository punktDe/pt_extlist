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
 * Testcase for filterbox
 * 
 * @author Michael Knoll 
 * @package Tests
 * @subpackage Domain\Model\Filter
 * @see Tx_PtExtlist_Domain_Model_Filter_Filterbox
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_FilterboxTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    protected $filterBoxConfigurationMock = null;



    /**
     * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
     */
    protected $configurationBuilderMock = null;
    
    
    
    protected $filterboxSettings = array();
    
    
    
    public function setup()
    {
        $this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
        $this->filterBoxConfigurationMock = new Tx_PtExtlist_Tests_Domain_Configuration_Filters_Stubs_FilterboxConfigurationMock($this->configurationBuilderMock, 'hirschIdentifier', array());
    }
    
    
    
    public function testSetup()
    {
        $filterbox = new Tx_PtExtlist_Domain_Model_Filter_Filterbox();
        $filterbox->_injectFilterboxConfiguration($this->filterBoxConfigurationMock);
        $this->assertEquals('hirschIdentifier', $filterbox->getfilterboxIdentifier());
        $this->assertEquals('test', $filterbox->getListIdentifier());
    }
    
    

    /** @test */
    public function resetResetsFiltersOfThisBoxAndSetsIsSubmittedFilterboxToFalse()
    {
        $filterbox = new Tx_PtExtlist_Domain_Model_Filter_Filterbox();
        $filterbox->_injectFilterboxConfiguration($this->filterBoxConfigurationMock);
        $filterbox->isSubmittedFilterbox();
        $filter = $this->getMock('Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub', array('reset'), array(), '', false);
        $filter->expects($this->once())
          ->method('reset');
        $filterbox->addItem($filter, 'testfilter');
        $filterbox->reset();
        $this->assertEquals($filterbox->isSubmittedFilterbox(), false);
    }
    
    

    /** @test */
    public function classImplementsSessionPersistableInterface()
    {
        $filterbox = new Tx_PtExtlist_Domain_Model_Filter_Filterbox();
        $filterbox->_injectFilterboxConfiguration($this->filterBoxConfigurationMock);
        $this->assertTrue(is_a($filterbox, 'Tx_PtExtbase_State_Session_SessionPersistableInterface'), 'Filterbox does not implement Tx_PtExtbase_State_Session_SessionPersistableInterface!');
        $this->assertTrue($filterbox->getObjectNamespace() == $filterbox->getListIdentifier() . '.filters.' . $filterbox->getfilterboxIdentifier() . '.' . Tx_PtExtlist_Domain_Model_Filter_Filterbox::OBJECT_NAMESPACE_SUFFIX);
    }
    
    

    /** @test */
    public function filterboxValiedatesOnValidatingFilters()
    {
        $validatingFilterboxMock = new Tx_PtExtlist_Domain_Model_Filter_Filterbox();
        $validatingFilterboxMock->_injectFilterboxConfiguration(new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'test', array()));
        $validatingFilterMock = $this->getMock('Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub', array('validate'), array(), '', false);
        $validatingFilterMock->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(true));
        $validatingFilterboxMock->addItem($validatingFilterMock);
        $this->assertTrue($validatingFilterboxMock->validate());
    }



    /** @test */
    public function filterboxDoesNotValidateOnNonValidatingFilters()
    {
        $notValidatingFilterboxMock = new Tx_PtExtlist_Domain_Model_Filter_Filterbox();
        $notValidatingFilterboxMock->_injectFilterboxConfiguration(new Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig($this->configurationBuilderMock, 'test', array()));
        $notValidatingFilterMock = $this->getMock('Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub', array('validate'), array(), '', false);
        $notValidatingFilterMock->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(false));
        $notValidatingFilterboxMock->addItem($notValidatingFilterMock);
        $this->assertFalse($notValidatingFilterboxMock->validate());
    }



    /** @test */
    public function getAccessibleFilterboxReturnsExpectedFilterbox()
    {
        $dataBackendFactoryMock = $this->getDataBackendFactoryMockForListConfigurationAndListIdentifier($this->configurationBuilderMock->getSettings(), $this->configurationBuilderMock->getListIdentifier());
        $filterboxFactory = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory'); /* @var $filterboxFactory Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory */
        $filterboxFactory->setDataBackendFactory($dataBackendFactoryMock);

        $filterbox = $filterboxFactory->createInstance($this->filterBoxConfigurationMock);
        $accessibleFilterbox = $filterbox->getAccessableFilterbox();
        
        $this->assertTrue(is_a($accessibleFilterbox, 'Tx_PtExtlist_Domain_Model_Filter_Filterbox'));
    }
    
    

    /** @test */
    public function getFilterByFilterIdentifierReturnsExpectedFilter()
    {
        $dataBackendFactoryMock = $this->getDataBackendFactoryMockForListConfigurationAndListIdentifier($this->configurationBuilderMock->getSettings(), $this->configurationBuilderMock->getListIdentifier());
        $filterboxFactory = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory'); /* @var $filterboxFactory Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory */
        $filterboxFactory->setDataBackendFactory($dataBackendFactoryMock);

        $filterbox = $filterboxFactory->createInstance($this->configurationBuilderMock->buildFilterConfiguration()->getFilterBoxConfig('testfilterbox'));

        $this->assertSame('testfilterbox', $filterbox->getFilterByFilterIdentifier('filter1')->getFilterBoxIdentifier());
    }
}
