<?php
namespace PunktDe\PtExtlist\Controller;

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

use PunktDe\PtExtlist\Domain\Model\ColumnSelector\ColumnSelectorFactory;
use PunktDe\PtExtlist\Domain\Model\Lists\ListFactory;
use PunktDe\PtExtlist\Domain\Renderer\RendererChain;
use PunktDe\PtExtlist\Domain\Renderer\RendererChainFactory;

/**
 * Controller for filter column selector widget
 *
 * @package Controller
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Controller_ColumnSelectorControllerTest
 */
class ColumnSelectorController extends AbstractController
{
    /**
     * Holds an instance of list renderer
     *
     * @var RendererChain
     */
    protected $rendererChain;



    /**
     * Holds an instance of the renderer chain factory
     *
     * @var RendererChainFactory
     */
    protected $rendererChainFactory;



    /**
     * @var ColumnSelectorFactory
     */
    protected $columnSelectorFactory;



    /**
     * @var ListFactory
     */
    protected $listFactory;




    /**
     * @param RendererChainFactory $rendererChainFactory
     */
    public function injectRendererChainFactory(RendererChainFactory $rendererChainFactory)
    {
        $this->rendererChainFactory = $rendererChainFactory;
    }



    /**
     * @param ListFactory $listFactory
     */
    public function injectListFactory(ListFactory $listFactory)
    {
        $this->listFactory = $listFactory;
    }



    /**
     * @param ColumnSelectorFactory $columnSelectorFactory
     */
    public function injectColumnSelectorFactory(ColumnSelectorFactory $columnSelectorFactory)
    {
        $this->columnSelectorFactory = $columnSelectorFactory;
    }



    /**
     * Overwrites initAction for setting properties
     * and enabling easy testing
     *
     * @throws \Exception
     */
    public function initializeAction()
    {
        parent::initializeAction();
        $this->rendererChain = $this->rendererChainFactory->getRendererChain($this->configurationBuilder->buildRendererChainConfiguration());
    }



    /**
     * Renders show action for column selector controller
     *
     * @return string The rendered index action
     */
    public function showAction()
    {
        $list = $this->listFactory->createList($this->dataBackend, $this->configurationBuilder);
        $renderedCaptions = $this->rendererChain->renderCaptions($list->getListHeader());
        $columnSelector = $this->columnSelectorFactory->getInstance($this->configurationBuilder);

        $this->view->assign('columnSelector', $columnSelector);
        $this->view->assign('listHeader', $list->getListHeader());
        $this->view->assign('listCaptions', $renderedCaptions);
    }
}
