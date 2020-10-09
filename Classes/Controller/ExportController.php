<?php
namespace PunktDe\PtExtlist\Controller;


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

use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtlist\Domain\Model\Lists\ListFactory;
use PunktDe\PtExtlist\ExtlistContext\ExtlistContextFactory;
use PunktDe\PtExtlist\View\Export\AbstractExportView;


/**
 * Controller for export actions
 *
 * @package Controller
 * @author Daniel Lienert
 */
class ExportController extends AbstractController
{
    /**
     * Reset ConfigurationBuilder for actions in this Controller
     *
     * @var bool
     */
    protected $resetConfigurationBuilder = true;

    /**
     * @var string
     */
    protected $exportListIdentifier;

    /**
     * @var ListFactory
     */
    protected $listFactory;

    /**
     * @var AbstractExportView
     */
    protected $view;



    /**
     * @param ListFactory $listFactory
     */
    public function injectListFactory(ListFactory $listFactory)
    {
        $this->listFactory = $listFactory;
    }



    /**
     * @return void
     * @throws \Exception
     */
    public function initializeAction()
    {
        parent::initializeAction();

        $this->exportListIdentifier = $this->settings['exportListIdentifier'];
        if (!$this->exportListIdentifier) {
            $this->exportListIdentifier = $this->listIdentifier;
        }
        Assert::isNotEmptyString($this->exportListIdentifier, ['message' => 'No export list identifier set.', 1316446015]);
    }



    /**
     * @return void
     */
    public function showLinkAction()
    {
        $fileExtension = $this->configurationBuilder->buildExportConfiguration()->getFileExtension();
        $this->view->assign('fileExtension', $fileExtension);
    }



    /**
     * Returns download for given parameters
     *
     * @return string
     * @throws \Exception
     */
    public function downloadAction()
    {
        if ($this->listIdentifier == $this->exportListIdentifier || !$this->exportListIdentifier) {
            $list = $this->listFactory->createList($this->dataBackend, $this->configurationBuilder);
        } else {
            $exportListConfiguration = $this->settings['listConfig'][$this->exportListIdentifier];
            
            if (!is_array($exportListConfiguration)) {
                throw new \Exception('No export list configuration found for listIdentifier ' . $this->exportListIdentifier, 1317116470);
            }

            $extListContext = ExtlistContextFactory::getContextByCustomConfiguration($exportListConfiguration, $this->listIdentifier, false);
            
            $list = $extListContext->getList(true);
        }

        $this->view->setExportConfiguration($this->configurationBuilder->buildExportConfiguration());
        $this->view->initConfiguration();

        $this->view->assign('listHeader', $list->getListHeader());
        $this->view->assign('listCaptions', $list->getRenderedListHeader());
        $this->view->assign('listData', $list->getRenderedListData());
        $this->view->assign('aggregateRows', $list->getRenderedAggregateListData());

        return $this->view->render();
    }
}
