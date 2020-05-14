<?php

namespace PunktDe\PtExtlist\Domain\Model\Lists\Header;



/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
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

use PunktDe\PtExtlist\Domain\AbstractComponentFactoryWithState;
use PunktDe\PtExtlist\Domain\Configuration\Columns\ColumnConfig;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Model\ColumnSelector\ColumnSelectorFactory;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class implements factory for list header
 * 
 * @author Daniel Lienert
 * @author Michael Knoll
 * @package Domain
 * @subpackage Model\Lists\Header
 * @see Tx_PtExtlist_Tests_Domain_Model_List_Header_ListHeaderfactoryTest
 */
class ListHeaderFactory
    extends AbstractComponentFactoryWithState
    implements SingletonInterface
{
    /**
     * Holds an array of singleton instances for each list identifier
     * 
     * @var array
     */
    private $instances = [];



    /**
     * @var HeaderColumnFactory
     */
    private $headerColumnFactory;



    /**
     * @var ColumnSelectorFactory
     */
    private $columnSelectorFactory;



    /**
     * @param HeaderColumnFactory $headerColumnFactory
     */
    public function injectHeaderColumnFactory(HeaderColumnFactory $headerColumnFactory)
    {
        $this->headerColumnFactory = $headerColumnFactory;
    }



    /**
     * @param ColumnSelectorFactory $columnSelectorFactory
     */
    public function injectColumnSelectorFactory(ColumnSelectorFactory $columnSelectorFactory)
    {
        $this->columnSelectorFactory = $columnSelectorFactory;
    }



    /**
     * Build singleton instance of listHeader, a collection of header column objects
     * 
     * @param ConfigurationBuilder $configurationBuilder
     * @param boolean $resetListHeader
     * @return ListHeader
     */
    public function createInstance(ConfigurationBuilder $configurationBuilder, $resetListHeader = false)
    {
        $listIdentifier = $configurationBuilder->getListIdentifier();

        // Check whether singleton instance exists
        if (!array_key_exists($listIdentifier, $this->instances) || $this->instances[$listIdentifier] === null || $resetListHeader) {
            $defaultSortingColumn = $configurationBuilder->buildListDefaultConfig()->getSortingColumn();
            $columnConfigurationCollection = $configurationBuilder->buildColumnsConfiguration();
            $listHeader = new ListHeader($configurationBuilder->getListIdentifier());
            $listIsSorted = false;

            foreach ($columnConfigurationCollection as $columnIdentifier => $singleColumnConfiguration) { /* @var $singleColumnConfiguration ColumnConfig */
                $headerColumn = $this->headerColumnFactory->createInstance($singleColumnConfiguration);

                if ($singleColumnConfiguration->isAccessable()) {
                    // We set list as sorted as soon as one column has sorting-status from user / session
                    if ($headerColumn->isSortingActive()) {
                        $listIsSorted = true;
                    }

                    $listHeader->addHeaderColumn($headerColumn, $singleColumnConfiguration->getColumnIdentifier());
                }
            }

            $this->setVisibilityByColumnSelector($configurationBuilder, $listHeader);

            // Check whether we have a sorting from header columns (set by user)
            // or whether we have to set default sorting
            if (!$listIsSorted && $defaultSortingColumn && $listHeader->hasItem($defaultSortingColumn)) {
                $listHeader->getHeaderColumn($defaultSortingColumn)->setDefaultSorting($configurationBuilder->buildListDefaultConfig()->getSortingDirection());
                $listHeader->getHeaderColumn($defaultSortingColumn)->init();
            }

            $this->getPostVarsAdapterFactory->getInstance()->injectParametersInObject($listHeader);

            $listHeader->init();

            $this->instances[$listIdentifier] = $listHeader;
        }

        
        // We return singleton instance of listHeader
        return $this->instances[$listIdentifier];
    }



    /**
     * @static
     * @param ConfigurationBuilder $configurationBuilder
     * @param ListHeader $listHeader
     */
    protected function setVisibilityByColumnSelector(ConfigurationBuilder $configurationBuilder, ListHeader $listHeader)
    {
        if ($configurationBuilder->buildColumnSelectorConfiguration()->getEnabled()) {
            $this->columnSelectorFactory->getInstance($configurationBuilder)->setVisibilityOnListHeader($listHeader);
        }
    }
}
