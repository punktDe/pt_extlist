<?php
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

/**
 * Implements factory for filterbox collections
 *
 * @author Daniel Lienert
 * @author Michael Knoll
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_FilterboxCollectionFactory
    extends Tx_PtExtlist_Domain_AbstractComponentFactory
    implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * Holds singleton instances of FilterboxCollections for each list identifier
     *
     * @var array<Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection>
     */
    private $instances = array();



    /**
     * @var Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory
     */
    private $filterboxFactory;



    /**
     * @var Tx_PtExtlist_Domain_DataBackend_DataBackendFactory
     */
    private $dataBackendFactory;



    /**
     * @param Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory $filterboxFactory
     */
    public function injectFilterboxFactory(Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory $filterboxFactory)
    {
        $this->filterboxFactory = $filterboxFactory;
    }



    /**
     * We can not use DI here, since we would get cyclic dependency!
     *
     * TODO think about how we get rid of this cyclic dependency!
     *
     * @param Tx_PtExtlist_Domain_DataBackend_DataBackendFactory $dataBackendFactory
     */
    public function setDataBackendFactory(Tx_PtExtlist_Domain_DataBackend_DataBackendFactory $dataBackendFactory)
    {
        $this->dataBackendFactory = $dataBackendFactory;
        $this->filterboxFactory->setDataBackendFactory($this->dataBackendFactory);
    }



    /**
     * Factory method for creating filterbox collection for a given configuration builder
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @param boolean $resetFilterBoxCollection
     * @return Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection
     */
    public function createInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $resetFilterBoxCollection)
    {
        if ($this->instances[$configurationBuilder->getListIdentifier()] === null || $resetFilterBoxCollection === true) {
            $filterboxConfigCollection = $configurationBuilder->buildFilterConfiguration();
            $filterboxCollection = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection', $configurationBuilder); /* @var $filterboxCollection Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection */

            foreach ($filterboxConfigCollection as $filterboxConfiguration) {
                /* @var $filterboxConfiguration Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig */
                $filterbox = $this->filterboxFactory->createInstance($filterboxConfiguration);
                $filterboxCollection->addFilterBox($filterbox, $filterbox->getfilterboxIdentifier());
            }

            $this->instances[$configurationBuilder->getListIdentifier()] = $filterboxCollection;
        }

        return $this->instances[$configurationBuilder->getListIdentifier()];
    }
}
