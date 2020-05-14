<?php

namespace PunktDe\PtExtlist\Domain\Model\Filter;

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

use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtlist\Domain\AbstractComponentFactoryWithState;
use PunktDe\PtExtlist\Domain\Configuration\Filters\FilterboxConfig;
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class implements factory for filter boxes
 *
 * @author Michael Knoll <mimi@kaktusteam.de>
 * @package Domain
 * @subpackage Model\Filter
 * @see Tx_PtExtlist_Tests_Domain_Model_Filter_FilterboxFactoryTest
 */
class FilterboxFactory extends AbstractComponentFactoryWithState implements SingletonInterface
{
    /**
     * @var FilterFactory
     */
    private $filterFactory;



    /**
     * @var DataBackendFactory
     */
    private $dataBackendFactory;



    /**
     * @param FilterFactory $filterFactory
     */
    public function injectFilterFactory(FilterFactory $filterFactory)
    {
        $this->filterFactory = $filterFactory;
    }



    /**
     * We can not use DI here, since we would get cyclic dependency!
     *
     * TODO think about how we get rid of this cyclic dependency!
     *
     * @param DataBackendFactory $dataBackendFactory
     */
    public function setDataBackendFactory(DataBackendFactory $dataBackendFactory)
    {
        $this->dataBackendFactory = $dataBackendFactory;
        $this->filterFactory->setDataBackendFactory($this->dataBackendFactory);
    }



    /**
     * Factory method for filter boxes. Returns filterbox for a given filterbox configuration and list identifier
     *
     * @param FilterboxConfig $filterboxConfiguration
     * @return Filterbox
     */
    public function createInstance(FilterboxConfig $filterboxConfiguration)
    {
        Assert::isNotEmptyString($filterboxConfiguration->getListIdentifier(), ['message' => 'List identifier must not be empty 1277889458']);
        $filterbox = $this->objectManager->get(Filterbox::class); /* @var $filterbox Filterbox */
        $filterbox->_injectFilterboxConfiguration($filterboxConfiguration);
        $filterbox->_injectFilterboxFactory($this);
        foreach ($filterboxConfiguration as $filterConfiguration) {
            $filter = $this->filterFactory->createInstance($filterConfiguration);
            $filter->_injectFilterbox($filterbox);
            $filterbox->addFilter($filter, $filter->getFilterIdentifier());
        }

        $sessionPersistenceManager = $this->sessionPersistenceManagerBuilder->getInstance();
        $sessionPersistenceManager->registerObjectAndLoadFromSession($filterbox);

        return $filterbox;
    }



    /**
     * Factory method for filter boxes. Returns only accessible filters from a given filterbox.
     *
     * @param FilterBox $collection
     * @return Filterbox
     */
    public function createAccessableInstance(FilterBox $collection)
    {
        $accessibleCollection = $this->objectManager->get(Filterbox::class);
        $accessibleCollection->_injectFilterboxConfiguration($collection->getFilterboxConfiguration());

        foreach ($collection as $filter) { /* @var $filter FilterInterface */
            if ($filter->getFilterConfig()->isAccessable()) {
                $accessibleCollection->addFilter($filter, $filter->getFilterIdentifier());
            }
        }
        return $accessibleCollection;
    }
}
