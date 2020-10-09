<?php

namespace PunktDe\PtExtlist\Domain\Model\Filter;


/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2013 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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

use PunktDe\PtExtlist\Domain\AbstractComponentFactory;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\Filters\FilterboxConfig;
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Implements factory for filterbox collections
 *
 * @author Daniel Lienert
 * @author Michael Knoll
 * @package Domain
 * @subpackage Model\Filter
 */
class FilterboxCollectionFactory
    extends AbstractComponentFactory
    implements SingletonInterface
{
    /**
     * Holds singleton instances of FilterboxCollections for each list identifier
     *
     * @var array<\PunktDe\PtExtlist\Domain\Model\Filter\FilterboxCollection>
     */
    private $instances = [];



    /**
     * @var FilterboxFactory
     */
    private $filterboxFactory;



    /**
     * @var DataBackendFactory
     */
    private $dataBackendFactory;



    /**
     * @param FilterboxFactory $filterboxFactory
     */
    public function injectFilterboxFactory(FilterboxFactory $filterboxFactory)
    {
        $this->filterboxFactory = $filterboxFactory;
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
        $this->filterboxFactory->setDataBackendFactory($this->dataBackendFactory);
    }



    /**
     * Factory method for creating filterbox collection for a given configuration builder
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @param boolean $resetFilterBoxCollection
     * @return FilterboxCollection
     */
    public function createInstance(ConfigurationBuilder $configurationBuilder, $resetFilterBoxCollection)
    {
        if ($this->instances[$configurationBuilder->getListIdentifier()] === null || $resetFilterBoxCollection === true) {
            $filterboxConfigCollection = $configurationBuilder->buildFilterConfiguration();
            $filterboxCollection = $this->objectManager->get(FilterboxCollection::class, $configurationBuilder); /* @var $filterboxCollection FilterboxCollection */

            foreach ($filterboxConfigCollection as $filterboxConfiguration) {
                /* @var $filterboxConfiguration FilterboxConfig */
                $filterbox = $this->filterboxFactory->createInstance($filterboxConfiguration);
                $filterboxCollection->addFilterBox($filterbox, $filterbox->getfilterboxIdentifier());
            }

            $this->instances[$configurationBuilder->getListIdentifier()] = $filterboxCollection;
        }

        return $this->instances[$configurationBuilder->getListIdentifier()];
    }
}
