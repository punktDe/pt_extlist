<?php

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

namespace PunktDe\PtExtlist\ExtlistContext;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface;
use PunktDe\PtExtlist\Domain\Model\Bookmark\BookmarkManager;
use PunktDe\PtExtlist\Domain\Model\Bookmark\BookmarkManagerFactory;
use PunktDe\PtExtlist\Domain\Model\Lists\ListData;
use PunktDe\PtExtlist\Domain\Model\Lists\ListFactory;
use PunktDe\PtExtlist\Domain\Model\Lists\Lists;
use PunktDe\PtExtlist\Domain\Model\Pager\PagerCollection;
use PunktDe\PtExtlist\Domain\Model\Pager\PagerInterface;
use PunktDe\PtExtlist\Domain\QueryObject\Query;
use PunktDe\PtExtlist\Domain\Renderer\RendererChain;
use PunktDe\PtExtlist\Domain\Renderer\RendererChainFactory;
use PunktDe\PtExtlist\Extbase\ExtbaseContext;
use TYPO3\CMS\Extbase\Object\Exception;

/**
 * Class implements ExtListContext
 *
 * @package ExtlistContext
 * @author Daniel Lienert
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_ExtlistContext_ExtlistContextTest
 */
class ExtlistContext
{
    /**
     * @var \PunktDe\PtExtlist\Extbase\ExtbaseContext
     */
    protected $extBaseContext;



    /**
     * Holds an instance of list configuration for this context
     *
     * @var array
     */
    protected $listSettings;



    /**
     * Holds list identifier for list kept in context
     *
     * @var string
     */
    protected $listIdentifier;


    /**
     * Holds pager identifier as default
     *
     * @var string
     */
    protected $pagerIdentifier = 'default';



    /**
     * Holds data backend for list identifier
     *
     * @var \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface
     */
    protected $dataBackend = null;



    /**
     * Holds an instance of renderer chain for this list
     *
     * @var \PunktDe\PtExtlist\Domain\Renderer\RendererChain
     */
    protected $rendererChain = null;



    /**
     * Cached rendered list data
     *
     * @var
     */
    protected $renderedListData;



    /**
     * Cached pager collection
     *
     * @var PagerCollection
     */
    protected $pagerCollection;



    /**
     * @var Lists
     */
    protected $lists = null;



    /**
     * Holds an instance of the renderer chain factory
     *
     * @var RendererChainFactory
     */
    protected $rendererChainFactory;



    /**
     * @var ListFactory
     */
    protected $listFactory;



    /**
     * @var BookmarkManagerFactory
     */
    protected $bookmarkManagerFactory;



    /**
     * @var BookmarkManager
     */
    protected $bookmarkManager;



    /**
     * @param ListFactory $listFactory
     */
    public function injectListFactory(ListFactory $listFactory)
    {
        $this->listFactory = $listFactory;
    }



    /**
     * @param ExtbaseContext $extBaseContext
     */
    public function injectExtBaseContext(ExtbaseContext $extBaseContext)
    {
        $this->extBaseContext = $extBaseContext;
    }



    /**
     * @param RendererChainFactory $rendererChainFactory
     */
    public function injectRendererChainFactory(RendererChainFactory $rendererChainFactory)
    {
        $this->rendererChainFactory = $rendererChainFactory;
    }



    /**
     * @param BookmarkManagerFactory $bookmarkManagerFactory
     */
    public function injectBookmarkManagerFactory(BookmarkManagerFactory $bookmarkManagerFactory)
    {
        $this->bookmarkManagerFactory = $bookmarkManagerFactory;
    }



    /**
     * Inject the Databackend
     *
     * @param DataBackendInterface $dataBackend
     */
    public function _injectDataBackend(DataBackendInterface $dataBackend)
    {
        $this->dataBackend = $dataBackend;
    }



    /**
     * Initialize the extbaseContext
     */
    public function init()
    {
        $this->initBookmarkManager();
    }



    /**
     * @return ConfigurationBuilder
     */
    public function getConfigurationBuilder()
    {
        return $this->dataBackend->getConfigurationBuilder();
    }



    /**
     * Returns renderer chain
     *
     * @return RendererChain
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     */
    public function getRendererChain()
    {
        if ($this->rendererChain === null) {
            $this->rendererChain = $this->rendererChainFactory->getRendererChain($this->dataBackend->getConfigurationBuilder()->buildRendererChainConfiguration());
        }

        return $this->rendererChain;
    }



    /**
     * Returns data backend of list context
     *
     * @return DataBackendInterface
     */
    public function getDataBackend()
    {
        return $this->dataBackend;
    }



    /**
     * Sets sorting of list to given column identifier.
     *
     * Sorting needs to be set up in column configuration to make this work.
     *
     * ATTENTION: If sorting doesn't change after re-configuration, make sure to have
     * truncated the fe_sessions_data table, as sorting is stored in session and not overwritten
     * by changed configuration.
     *
     * @param string $sortingColumn Column identifier of column by which list should be sorted.
     * @param integer $sortingDirection Sorting direction (one of Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC | SORTINGSTATE_DESC | SORTINGSTATE_NONE)
     * @param bool $rebuildListCache If set to false, the list cache has to be re-calculated manually (e.g. by calling $extlistcontext->getList(TRUE))
     * @throws Exception , if given column identifier does not exist in this list
     */
    public function setSortingColumn($sortingColumn, $sortingDirection = Query::SORTINGSTATE_ASC, $rebuildListCache = true)
    {
        if (!$this->getList()->getListHeader()->hasItem($sortingColumn)) {
            throw new Exception('The column with column identifier ' . $sortingColumn . ' does not exist in this list (' . $this->getConfigurationBuilder()->getListIdentifier() . '1359373245) ');
        }

        $this->getDataBackend()->getSorter()->removeAllSortingObservers();
        $this->getList()->getListHeader()->getHeaderColumn($sortingColumn)->setSorting($sortingDirection);
        $this->getDataBackend()->getSorter()->registerSortingObserver($this->getList()->getListHeader()->getHeaderColumn($sortingColumn));

        if ($rebuildListCache) {
            $this->getList(true);
        }
    }



    /**
     * Returns pager collection fo databacken for this list context
     *
     * @return PagerCollection
     */
    public function getPagerCollection()
    {
        if ($this->pagerCollection === null) {
            $this->pagerCollection = $this->dataBackend->getPagerCollection();
            $this->pagerCollection->setItemCount($this->dataBackend->getTotalItemsCount());
        }
        return $this->pagerCollection;
    }

    /**
     * Set a pager Identifier
     *
     * @param string $pagerIdentifier
     */
    public function setPagerIdentifier($pagerIdentifier = '')
    {
        $this->pagerIdentifier = $pagerIdentifier;
    }


    /**
     * Get a pager object - if pagerIdentifier is null, get default pager
     *
     * @param string $pagerIdentifier
     * @return PagerInterface
     */
    public function getPager($pagerIdentifier = '')
    {
        $pagerIdentifier = $pagerIdentifier ? $pagerIdentifier : $this->pagerIdentifier;
        return $this->dataBackend->getPagerCollection()->getPagerByIdentifier($pagerIdentifier);
    }



    /**
     * Returns list object of this list context
     *
     * @param boolean $buildNew
     * @return Lists
     */
    public function getList($buildNew = false)
    {
        if ($this->list == null || $buildNew) {
            $this->list = $this->listFactory->createList($this->dataBackend, $this->dataBackend->getConfigurationBuilder(), $buildNew);
        }

        return $this->list;
    }



    /**
     * Returns list data for this list context
     *
     * @return ListData
     */
    public function getListData()
    {
        return $this->getList()->getListData();
    }



    /**
     * @return ListData
     */
    public function getIterationListData()
    {
        return $this->dataBackend->getIterationListData();
    }



    /**
     * Returns rendered list data for this list context
     *
     * @param bool $buildNew If set to TRUE, the list data is rebuild
     * @return ListData
     */
    public function getRenderedListData($buildNew = false)
    {
        return $this->getList($buildNew)->getRenderedListData();
    }



    /**
     * Returns the filterboxCollection
     *
     * @return Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection
     */
    public function getFilterBoxCollection()
    {
        return $this->dataBackend->getFilterboxCollection();
    }



    /**
     * Returns filter for given full filter name (filterboxIdentifier.filterIdentifier)
     *
     * TODO refactor me, use filterboxCollection->getFilterByFullFiltername()
     *
     * @param $fullFilterName
     * @return Tx_PtExtlist_Domain_Model_Filter_FilterInterface
     */
    public function getFilterByFullFiltername($fullFilterName)
    {
        $filter = $this->getFilterBoxCollection()->getFilterByFullFiltername($fullFilterName);
        return $filter;
    }



    /**
     * Sets a filter value for a given filterbox identifier, filter identifier and filter value.
     *
     * If $resetListCache is set to FALSE, the data backend is not invalidated and the list has
     * to be re-rendered manually. This can be useful, if several methods are called that invalidate
     * the list cache and the cache should only be re-calculated once.
     *
     * Make sure that the filter addressed by filterbox and filter identifier has a setValue() method.
     *
     * @param string $filterboxIdentifier Identifier of filterbox in which we want to set a filter value
     * @param string $filterIdentifier Identifier of filter for which we want to set a value
     * @param mixed $filterValue Filter value to be set in filter
     * @param bool $resetListCache If set to FALSE, list cache must be re-calculated manually
     * @throws Exception , if addressed filter object does not have a setValue method (this is not part of the filter interface!)
     */
    public function setFilterValue($filterboxIdentifier, $filterIdentifier, $filterValue, $resetListCache = true)
    {
        $filter = $this->getFilterByFullFiltername($filterboxIdentifier . '.' . $filterIdentifier);
        $filter->reset();
        $filter->setFilterValue($filterValue); // ATM this is not part of the filter interface, so we might get an exception if this method does not exist!
        $filter->init();
        if ($resetListCache) {
            $this->getList(true);
        }
    }



    /**
     * @return Row
     */
    public function getRenderedCaptions()
    {
        return $this->getList()->getRenderedListHeader();
    }



    /**
     * @return ListData
     */
    public function getRenderedAggregateRows()
    {
        return $this->getList()->getRenderedAggregateListData();
    }



    /**
     * Return an array with all parts to display an integrated list
     *
     * @return array
     */
    public function getAllListTemplateParts()
    {
        $viewParts = [
            'config' => $this->getConfigurationBuilder(),
            'listHeader' => $this->getList()->getListHeader(),
            'listCaptions' => $this->getRenderedCaptions(),
            'listData' => $this->getRenderedListData(),
            'aggregateRows' => $this->getRenderedAggregateRows(),
            'pagerCollection' => $this->getPagerCollection(),
            'pager' => $this->getPager(),
        ];
        if ($this->getFilterBoxCollection()->count() > 0) {
            $viewParts['filterbox'] = $this->getFilterBoxCollection()->getItemByIndex(0);
        }

        return $viewParts;
    }



    /**
     * Resets pager collection for list context
     */
    public function resetPagerCollection()
    {
        $this->dataBackend->getPagerCollection()->reset();
    }



    /**
     * Resets filterbox collection for list context
     */
    public function resetFilterboxCollection()
    {
        $this->dataBackend->getFilterboxCollection()->reset();
    }



    /**
     * @return \ExtbaseContext
     */
    public function getExtBaseContext()
    {
        return $this->extBaseContext;
    }



    /**
     * @param Bookmark $bookmark Bookmark to be stored to database(This bookmark does not contain session data yet)
     */
    public function storeBookmark(Bookmark $bookmark)
    {
        $this->bookmarkManager->storeBookmark($bookmark);
    }


    protected function initBookmarkManager()
    {
        $this->bookmarkManager = $this->bookmarkManagerFactory->getInstanceByConfigurationBuilder($this->getConfigurationBuilder());
    }


    /**
     * @return array
     */
    public function getBookmarks()
    {
        return $this->bookmarkManager->getBookmarksForCurrentConfigAndFeUser();
    }


    /**
     * @return Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfig
     */
    public function getBookmarkConfiguration()
    {
        return $this->getConfigurationBuilder()->buildBookmarkConfiguration();
    }



    /**
     * @param Bookmark $bookmark
     */
    public function removeBookmark(Bookmark $bookmark)
    {
        $this->bookmarkManager->removeBookmark($bookmark);
    }
}
