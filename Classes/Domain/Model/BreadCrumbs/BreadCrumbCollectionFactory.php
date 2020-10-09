<?php

namespace PunktDe\PtExtlist\Domain\Model\BreadCrumbs;


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

use PunktDe\PtExtlist\Domain\AbstractComponentFactory;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Model\Filter\Filterbox;
use PunktDe\PtExtlist\Domain\Model\Filter\FilterboxCollection;
use PunktDe\PtExtlist\Domain\Model\Filter\FilterInterface;
use PunktDe\PtExtlist\Domain\StateAdapter\GetPostVarAdapterFactory;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class implements a factory for a collection of breadcrumbs
 *
 * @package Domain
 * @subpackage Model\BreadCrumbs
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Model_BreadCrumbs_BreadCrumbCollectionFactoryTest
 */
class BreadCrumbCollectionFactory
    extends AbstractComponentFactory
    implements SingletonInterface
{
    /**
     * Holds an array of instances for each list identifier
     *
     * @var array
     */
    protected $instances = [];



    /**
     * Factory method creates instance of breadcrumbs collection as list identifier-specific singleton
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @return BreadCrumbCollection
     */
    public function getInstanceByConfigurationBuilder(ConfigurationBuilder $configurationBuilder)
    {
        if (!array_key_exists($configurationBuilder->getListIdentifier(), $this->instances)
            || $this->instances[$configurationBuilder->getListIdentifier()] == null) {
            $filterboxCollectionFactory = GeneralUtility::makeInstance(ObjectManager::class)->get(FilterboxCollectionFactory::class); /* @var $filterboxCollectionFactory FilterboxCollectionFactory */
        
            $filterboxCollection = $filterboxCollectionFactory->createInstance($configurationBuilder, false);
            $breadCrumbCollection = $this->getInstanceByFilterboxCollection($configurationBuilder, $filterboxCollection);

            $this->instances[$configurationBuilder->getListIdentifier()] = $breadCrumbCollection;
        }
        return $this->instances[$configurationBuilder->getListIdentifier()];
    }



    /**
     * Factory method creates instance of breadcrumbs collection for a given filterbox collection
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @param FilterboxCollection $filterboxCollection
     * @return BreadCrumbCollection
     */
    public function getInstanceByFilterboxCollection(ConfigurationBuilder $configurationBuilder, FilterboxCollection $filterboxCollection)
    {
        if (!array_key_exists($filterboxCollection->getListIdentifier(), $this->instances)
            || $this->instances[$filterboxCollection->getListIdentifier()] == null) {
            $breadCrumbCollection = new BreadCrumbCollection();
            $breadCrumbCollection->injectConfigurationBuilder($configurationBuilder);

            $getPostVarsAdapterFactory = GeneralUtility::makeInstance(ObjectManager::class)->get(GetPostVarAdapterFactory::class); /* @var $getPostVarsAdapterFactory GetPostVarAdapterFactory */

            $gpVarsAdapter = $getPostVarsAdapterFactory->getInstance();
            $gpVarsAdapter->injectParametersInObject($breadCrumbCollection);

            foreach ($filterboxCollection as $filterbox) { /* @var $filterbox Filterbox */
                foreach ($filterbox as $filter) { /* @var $filter FilterInterface */
                    if ($filter->isActive()) {
                        $breadcrumb = $filter->getFilterBreadCrumb();

                        if ($breadcrumb !== null) {
                            // TODO at the moment, proxy filters generate a null breadcrumb. Fix this!
                            $breadCrumbCollection->addBreadCrumb($breadcrumb);
                        }
                    }
                }
            }
            $this->instances[$filterboxCollection->getListIdentifier()] = $breadCrumbCollection;
        }
        return $this->instances[$filterboxCollection->getListIdentifier()];
    }
}
