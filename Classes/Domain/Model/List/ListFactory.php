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
 * Factory to put all parts of a list together.
 * 
 * @author Michael Knoll
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\List
 */
class Tx_PtExtlist_Domain_Model_List_ListFactory
    extends Tx_PtExtlist_Domain_AbstractComponentFactory
    implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @var Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory
     */
    protected $listHeaderFactory;



    /**
     * @var Tx_PtExtlist_Domain_Renderer_RendererChainFactory
     */
    protected $rendererChainFactory;



    /**
     * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory $listHeaderFactory
     */
    public function injectListHeaderFactory(Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory $listHeaderFactory)
    {
        $this->listHeaderFactory = $listHeaderFactory;
    }



    /**
     * @param Tx_PtExtlist_Domain_Renderer_RendererChainFactory $rendererChainFactory
     */
    public function injectRendererChainFactory(Tx_PtExtlist_Domain_Renderer_RendererChainFactory $rendererChainFactory)
    {
        $this->rendererChainFactory = $rendererChainFactory;
    }



    /**
     * Returns a full featured list object.
     *
     * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @param boolean $resetList
     * @return Tx_PtExtlist_Domain_Model_List_List
     */
    public function createList(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $resetList = false)
    {
        $list = new Tx_PtExtlist_Domain_Model_List_List();

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
     * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Model_List_ListData
     */
    public function buildAggregateListData(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder)
    {
        if ($configurationBuilder->buildAggregateDataConfig()->count() > 0) {
            return $dataBackend->getAggregateListData();
        } else {
            return new Tx_PtExtlist_Domain_Model_List_ListData();
        }
    }
}
