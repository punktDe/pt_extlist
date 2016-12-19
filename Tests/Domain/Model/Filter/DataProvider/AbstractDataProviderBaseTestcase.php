<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Michael Knoll <knoll@punkt.de>, punkt.de GmbH
 *
 *
 *  All rights reserved
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
 *
 *
 * @author Michael Knoll <knoll@punkt.de>
 * @package
 * @subpackage
 * @see
 */
abstract class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_AbstractDataProviderBaseTestcase extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * @var array
     */
    protected $defaultFilterSettings;



    /**
     * Build the dataprovider
     *
     * @param array $filterSettings
     * @return Tx_PtExtlist_Domain_Model_Filter_DataProvider_ExplicitSQLQuery
     */
    protected function buildAccessibleDataProvider($filterSettings = null)
    {
        $accessibleClassName = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Filter_DataProvider_ExplicitSQLQuery');
        $accessibleExplicitDataProvider = new $accessibleClassName;
        /* @var $accessibleExplicitDataProvider Tx_PtExtlist_Domain_Model_Filter_DataProvider_ExplicitSQLQuery */

        if (!$filterSettings) {
            $filterSettings = $this->defaultFilterSettings;
        }

        $filterConfiguration = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $filterSettings, 'test');

        $accessibleExplicitDataProvider->_injectFilterConfig($filterConfiguration);
        $accessibleExplicitDataProvider->init();

        // We need to do this to initially create a configuration builder! TODO remove this, once we have proper DI!
        $dataBackendFactory = $this->getDataBackendFactoryMockForListConfigurationAndListIdentifier($this->configurationBuilderMock->getSettings(), $this->configurationBuilderMock->getListIdentifier());
        // Create singleton instance of dataBackendFactory for corresponding configuration
        $dataBackendFactory->getDataBackendInstanceByListIdentifier($this->configurationBuilderMock->getListIdentifier());

        return $accessibleExplicitDataProvider;
    }
}
