<?php


namespace PunktDe\PtExtlist\Domain\Model\Filter\DataProvider;

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
 * Implements factory for options filter data provider
 *  
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 * @see Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_DataProviderFactoryTest
 */
class DataProviderFactory
    extends \PunktDe\PtExtlist\Domain\AbstractComponentFactory
    implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @var \PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory
     */
    protected $dataBackendFactory;



    /**
     * @param \PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory $dataBackendFactory
     */
    public function injectDataBackendFactory(\PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory $dataBackendFactory)
    {
        $this->dataBackendFactory = $dataBackendFactory;
    }


    
    /**
     * Create a dataprovider for options filter data
     *  
     * @param \PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig $filterConfig
     * @return \PunktDe\PtExtlist\Domain\Model\Filter\DataProvider\DataProviderInterface
     */
    public function createInstance(\PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig $filterConfig)
    {
        $dataProviderClassName = $this->determineDataProviderClass($filterConfig);
        $dataProvider = $this->objectManager->get($dataProviderClassName);
        Assert::isInstanceOf($dataProvider, 'DataProvider_DataProviderInterface', ['message' => 'The Dataprovider "' . $dataProviderClassName . ' does not implement the required interface! 1283536125']);
        /* @var $dataProvider DataProvider_DataProviderInterface */

        $dataProvider->_injectFilterConfig($filterConfig);
        $dataProvider->_injectDataBackend($this->dataBackendFactory->getDataBackendInstanceByListIdentifier($filterConfig->getListIdentifier()));
        $dataProvider->init();

        return $dataProvider;
    }



    /**
     * Determine the dataProvider to use for filter options
     *
     * TODO: Test me!
     * @param \PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig $filterConfig
     * @return string dataProviderClass
     */
    protected function determineDataProviderClass(\PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig $filterConfig)
    {
        if ($filterConfig->getSettings('dataProviderClassName')) {
            $dataProviderClassName = $filterConfig->getSettings('dataProviderClassName');
        } else {
            if ($filterConfig->getSettings('options')) {
                $dataProviderClassName = 'DataProvider_ExplicitData';
            } else {
                $dataProviderClassName = 'DataProvider_GroupData';
            }
        }
        Assert::isTrue(class_exists($dataProviderClassName), ['message' => 'The defined DataProviderClass "'.$dataProviderClassName.'" does not exist! 1283535558']);
        return $dataProviderClassName;
    }
}
