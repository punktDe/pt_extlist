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
 * Testcase for filterbox factory
 * 
 * @author Michael Knoll 
 * @package Tests
 * @subpackage Domain\Model\Filter
 * @see Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_FilterboxFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * @var Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory
     */
    protected $filterboxFactory;


    
    public function setup()
    {
        $this->initDefaultConfigurationBuilderMock();
        $dataBackendFactory = $this->getDataBackendFactoryMockForListConfigurationAndListIdentifier($this->configurationBuilderMock->getSettings(), $this->configurationBuilderMock->getListIdentifier());
        $this->filterboxFactory = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory');
        $this->filterboxFactory->setDataBackendFactory($dataBackendFactory);
    }
    
    

    /** @test */
    public function getFilterboxConfigurationMockReturnsInstanceOfConfigurationBuilder()
    {

        // TODO what is this test for?

        $filterboxConfigurationMock = new Tx_PtExtlist_Tests_Domain_Configuration_Filters_Stubs_FilterboxConfigurationCollectionMock();
        $filterboxConfigurationMock->setup();
        $filterboxConfiguration = $filterboxConfigurationMock->getfilterboxConfigurationMock('filterbox1');
        $this->assertTrue($filterboxConfiguration->getConfigurationBuilder() instanceof ConfigurationBuilder);
    }
    
    

    /** @test */
    public function createInstanceByFilterboxConfigurationReturnsInstanceOfFilterbox()
    {
        $filterboxConfigurationMock = new Tx_PtExtlist_Tests_Domain_Configuration_Filters_Stubs_FilterboxConfigurationCollectionMock();
        $filterboxConfigurationMock->setup();
        $filterboxConfiguration = $filterboxConfigurationMock->getfilterboxConfigurationMock('filterbox1');
        $filterbox = $this->filterboxFactory->createInstance($filterboxConfiguration);

        $this->assertTrue($filterbox instanceof Tx_PtExtlist_Domain_Model_Filter_Filterbox);
    }
    
    

    /** @test */
    public function containedFiltersHaveCorrectAccessRights()
    {
        $filterBoxConfig = $this->configurationBuilderMock->getFilterboxConfigurationByFilterboxIdentifier('testfilterbox');
        $filterbox = $this->filterboxFactory->createInstance($filterBoxConfig);

        $accessableFilterBox = $this->filterboxFactory->createAccessableInstance($filterbox);
        
        // AccessGroups are configured for filter2 -> should not be allowed to access.
        $this->assertTrue($accessableFilterBox->hasItem('filter1'));
        $this->assertFalse($accessableFilterBox->hasItem('filter2'));
    }
}
