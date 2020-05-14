<?php
namespace PunktDe\PtExtlist\Domain\Model\Lists;

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
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\ListHeaderFactory;
use PunktDe\PtExtlist\Domain\Renderer\RendererChainFactory;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Factory to put all parts of a list together.
 *  
 * @author Michael Knoll
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\List
 */
class ListFactory extends AbstractComponentFactory implements SingletonInterface
{
    /**
     * @var ListHeaderFactory
     */
    protected $listHeaderFactory;



    /**
     * @var RendererChainFactory
     */
    protected $rendererChainFactory;



    /**
     * @param ListHeaderFactory $listHeaderFactory
     */
    public function injectListHeaderFactory(ListHeaderFactory $listHeaderFactory)
    {
        $this->listHeaderFactory = $listHeaderFactory;
    }



    /**
     * @param RendererChainFactory $rendererChainFactory
     */
    public function injectRendererChainFactory(RendererChainFactory $rendererChainFactory)
    {
        $this->rendererChainFactory = $rendererChainFactory;
    }



    /**
     * Returns a full featured list object.
     *
     * @param DataBackendInterface $dataBackend
     * @param ConfigurationBuilder $configurationBuilder
     * @param boolean $resetList
     * @return Lists
     */
    public function createList(DataBackendInterface $dataBackend, ConfigurationBuilder $configurationBuilder, $resetList = false)
    {
        $list = new Lists();

        // We have to build headers here, as they are no longer created by data backend
        $listHeader = $this->listHeaderFactory->createInstance($configurationBuilder, $resetList);
        $list->setListHeader($listHeader);

        $list->setRendererChain($this->rendererChainFactory->getRendererChain($configurationBuilder->buildRendererChainConfiguration()));

        if ($configurationBuilder->buildListConfiguration()->getUseIterationListData()) {
            $list->setIterationListData($dataBackend->getIterationListData());
            $list->setUseIterationListData(true);
        } else {
            if ($resetList) {
                $dataBackend->resetListDataCache();
            }
            $list->setListData($dataBackend->getListData());
        }

        $list->setAggregateListData(self::buildAggregateListData($dataBackend, $configurationBuilder));

        return $list;
    }



    /**
     * Build the aggregate list data if any aggregates are defined
     *
     * @param DataBackendInterface $dataBackend
     * @param ConfigurationBuilder $configurationBuilder
     * @return ListData
     */
    public function buildAggregateListData(DataBackendInterface $dataBackend, ConfigurationBuilder $configurationBuilder)
    {
        if ($configurationBuilder->buildAggregateDataConfig()->count() > 0) {
            return $dataBackend->getAggregateListData();
        } else {
            return new ListData();
        }
    }
}
