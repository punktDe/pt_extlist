<?php
namespace PunktDe\PtExtlist\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2012 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
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

use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\Export\ExportConfig;
use PunktDe\PtExtlist\Domain\Model\Filter\Filterbox;
use PunktDe\PtExtlist\Domain\Model\Filter\FilterboxCollection;
use PunktDe\PtExtlist\Domain\Model\Lists\ListFactory;
use PunktDe\PtExtbase\Utility\HeaderInclusion;
use PunktDe\PtExtlist\Domain\Model\Pager\PagerCollection;
use PunktDe\PtExtlist\ExtlistContext\ExtlistContext;
use PunktDe\PtExtlist\ExtlistContext\ExtlistContextFactory;

/**
 * This controller is meant to be used in a backend module, as in backend context we have only one controller
 */
abstract class AbstractListApplicationController extends AbstractController
{
    /**
     * @var string relative path under settings of this extension to the extlist typoScript configuration
     */
    protected $extlistTypoScriptSettingsPath = '';



    /**
     * @var string the pagerIdentifier to use
     */
    protected $pagerIdentifier = 'delta';



    /**
     * @var string
     */
    protected $filterboxIdentifier = '';



    /**
     * Array of available exportTypeIdentifiers
     *
     * @var array
     */
    protected $exportIdentifiers = [];



    /******************************************************
     * End of configuration section
     */
    /**
     * Holds an instance of filterbox collection for processed list
     *
     * @var FilterboxCollection
     */
    protected $filterboxCollection = null;



    /**
     * Holds an instance of filterbox processed by this controller
     *
     * @var Filterbox
     */
    protected $filterbox = null;



    /**
     * @var PagerCollection
     */
    protected $pagerCollection = null;



    /**
     * @var ExtlistContext
     */
    protected $extListContext;



    /**
     * @var HeaderInclusion
     */
    protected $headerInclusionUtility;



    /**
     * @var ListFactory
     */
    protected $listFactory;



    /**
     * @var ExtlistContextFactory
     */
    protected $extlistContextFactory;


   /**
    * @param ExtlistContextFactory $extlistContextFactory
    */
   public function injectExtlistContextFactory(ExtlistContextFactory $extlistContextFactory)
   {
       $this->extlistContextFactory = $extlistContextFactory;
   }

    /**
     * @param HeaderInclusion $headerInclusionUtility
     */
    public function injectHeaderInclusionUtility(HeaderInclusion $headerInclusionUtility)
    {
        $this->headerInclusionUtility = $headerInclusionUtility;
    }

    /**
     * @param ListFactory $listFactory
     */
    public function injectListFactory(ListFactory $listFactory)
    {
        $this->listFactory = $listFactory;
    }



    /**
     * Initialize this controller
     */
    public function initializeAction()
    {
        parent::initializeAction();
        $this->initFilterBox();
        $this->initPager();
    }



    /**
     * Init the filterbox
     */
    protected function initFilterBox()
    {
        if ($this->filterboxIdentifier) {
            $this->filterboxCollection = $this->dataBackend->getFilterboxCollection();
            $this->filterbox = $this->filterboxCollection->getFilterboxByFilterboxIdentifier($this->filterboxIdentifier, true);
        }
    }



    /**
     * Init the pager
     */
    protected function initPager()
    {
        $this->pagerCollection = $this->dataBackend->getPagerCollection();
        $this->pagerCollection->setItemCount($this->dataBackend->getTotalItemsCount());
    }



    /**
     * Sets list identifier for this controller
     *
     * @throws \Exception
     */
    protected function initListIdentifier()
    {
        $settings = \PunktDe\PtExtbase\Utility\NamespaceUtility::getArrayContentByArrayAndNamespace($this->settings, $this->extlistTypoScriptSettingsPath);

        if (!$this->extlistTypoScriptSettingsPath) {
            throw new \Exception('No extlist typoscript settings path given', 1330188161);
        }
        $this->listIdentifier = array_pop(explode('.', $this->extlistTypoScriptSettingsPath));
        $this->extListContext = $this->extlistContextFactory->getContextByCustomConfigurationNonStatic($settings, $this->listIdentifier);

        return $this->extListContext->getConfigurationBuilder();
    }



    /**
     * Build the configuration builder with settings from the given extlistTypoScriptConfigurationPath
     *
     * @return ConfigurationBuilder
     * @throws \Exception
     */
    protected function buildConfigurationBuilder()
    {
        $this->configurationBuilder = $this->extListContext->getConfigurationBuilder();
    }


    /**
     * Alias action to use the unmodified pager templates
     * @throws StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function showAction()
    {
        $this->forward('list');
    }



    /**
     * List action rendering list
     *
     * @return string  Rendered list for given list identifier
     */
    public function listAction()
    {
        $list = $this->listFactory->createList($this->dataBackend, $this->configurationBuilder);

        if ($list->count() == 0) {
            $this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('general.emptyList', 'PtExtlist'), '', \TYPO3\CMS\Core\Messaging\FlashMessage::INFO);
        }

        $this->view->assign('config', $this->configurationBuilder);
        $this->view->assign('listHeader', $list->getListHeader());
        $this->view->assign('listCaptions', $list->getRenderedListHeader());
        $this->view->assign('listData', $list->getRenderedListData());
        $this->view->assign('aggregateRows', $list->getRenderedAggregateListData());

        $this->view->assign('exportIdentifiers', $this->exportIdentifiers);

        if ($this->filterbox) {
            $this->view->assign('filterBoxCollection', $this->filterboxCollection);
            $this->view->assign('filterbox', $this->filterbox);
        }

        if ($this->pagerIdentifier) {
            $this->view->assign('pagerCollection', $this->pagerCollection);
            $this->view->assign('pager', $this->pagerCollection->getPagerByIdentifier($this->pagerIdentifier));
        }
    }



    /**
     * @param string $exportIdentifier
     * @return string
     * @throws \Exception
     */
    public function downloadAction($exportIdentifier)
    {
        $exportSettingsPath = $this->extlistTypoScriptSettingsPath . '.export.exportConfigs.' . $exportIdentifier;
        $exportSettings = \PunktDe\PtExtbase\Utility\NamespaceUtility::getArrayContentByArrayAndNamespace($this->settings, $exportSettingsPath);

        if (!is_array($exportSettings) || empty($exportSettings)) {
            throw new \Exception('No export settings found within the path ' . $exportSettingsPath, 1331644291);
        }

        $exportConfig = new ExportConfig($this->configurationBuilder, $exportSettings);

        if (array_key_exists('exportListSettingsPath', $exportSettings)) {
            $exportListSettings = \PunktDe\PtExtbase\Utility\NamespaceUtility::getArrayContentByArrayAndNamespace($this->settings, $exportSettings['exportListSettingsPath']);
        } else {
            $exportListSettings = $this->configurationBuilder->getSettings();
        }

        $extListContext = ExtlistContextFactory::getContextByCustomConfiguration($exportListSettings, $this->listIdentifier, false);
        $list = $extListContext->getList(true);

        $view = $this->objectManager->get($exportConfig->getViewClassName());
        $view->setConfigurationBuilder($extListContext->getConfigurationBuilder());
        $view->setExportConfiguration($exportConfig);
        $view->assign('listHeader', $list->getListHeader());
        $view->assign('listCaptions', $list->getRenderedListHeader());
        $view->assign('listData', $list->getRenderedListData());
        $view->assign('aggregateRows', $list->getRenderedAggregateListData());

        return $view->render();
    }



    /**
     * Resets all filters of filterbox
     *
     * @param string $filterboxIdentifier Identifier of filter which should be reset
     * @return string Rendered reset action
     */
    public function resetAction($filterboxIdentifier)
    {
        if ($this->filterboxCollection->hasItem($filterboxIdentifier)) {
            $this->filterboxCollection->getFilterboxByFilterboxIdentifier($filterboxIdentifier)->reset();
        }

        $this->resetPagers();

        $this->redirect('list');
    }



    /**
     * Sorting action used to change sorting of a list
     *
     * @return string Rendered sorting action
     */
    public function sortAction()
    {
        $this->dataBackend->resetListDataCache();
        // ATTENTION: When a list header is reset, its GP var data is not reset, so every header that has
        // sorting data set in GP vars will not be effected when reset!
        $this->dataBackend->getSorter()->reset();

        $this->forward('list');
    }



    /**
     * Reset all pagers for this list.
     */
    protected function resetPagers()
    {
        // Reset pagers
        if ($this->pagerCollection === null) {
            // Only get pagerCollection if it's not set already. Important for testing.
            $this->pagerCollection = $this->dataBackend->getPagerCollection();
        }
        $this->pagerCollection->reset();
    }
}
