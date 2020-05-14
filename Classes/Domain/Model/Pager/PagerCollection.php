<?php
namespace PunktDe\PtExtlist\Domain\Model\Pager;

use PunktDe\PtExtbase\Collection\Collection;
use PunktDe\PtExtbase\State\GpVars\GpVarsInjectableInterface;
use PunktDe\PtExtbase\State\Session\SessionPersistableInterface;
use PunktDe\PtExtbase\State\Session\SessionPersistenceManager;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtbase\Exception\InternalException;

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
 * A collection to manage a bunch of pagers.
 *
 * @author Daniel Lienert
 * @author Christoph Ehscheidt
 * @package Domain
 * @subpackage Model\Pager
 * @see Tx_PtExtlist_Tests_Domain_Model_Pager_PagerCollectionTest
 */
class PagerCollection extends Collection
    implements SessionPersistableInterface, GpVarsInjectableInterface
{
    /**
     * @var ConfigurationBuilder
     */
    protected $configurationBuilder;



    /**
     * Holds the current page index.
     * New pagers need to know the current page.
     *
     * @var integer
     */
    protected $currentPage = 1;



    /**
     * Shows if one of the pagers is enabled.
     *
     * @var boolean
     */
    protected $enabled = false;



    /**
     * Holds a instance of the persitence manager.
     *
     * @var SessionPersistenceManager
     */
    protected $sessionPersistenceManager;



    /**
     * @param ConfigurationBuilder $configurationBuilder
     */
    public function __construct(ConfigurationBuilder $configurationBuilder)
    {
        $this->configurationBuilder = $configurationBuilder;
    }



    /**
     * @param SessionPersistenceManager $sessionPersistenceManager
     */
    public function injectSessionPersistenceManager(SessionPersistenceManager $sessionPersistenceManager)
    {
        $this->sessionPersistenceManager = $sessionPersistenceManager;
    }



    /**
     * Adds a pager to the collection.
     *
     * @param PagerInterface $pager
     * @throws InternalException
     */
    public function addPager(PagerInterface $pager)
    {
        $pager->setCurrentPage($this->currentPage);

        // If one pager is enabled, the collection is marked enabled.
        if ($pager->isEnabled()) {
            $this->enabled = true;
        }

        $this->addItem($pager, $pager->getPagerIdentifier());
    }



    /**
     * Sets the current page index to all pagers.
     *
     * @param integer $pageIndex
     */
    public function setCurrentPage($pageIndex)
    {
        $this->currentPage = (int)$pageIndex;
        foreach ($this->itemsArr as $pager) {    /* @var $pager PagerInterface */
            $pager->setCurrentPage($pageIndex);
        }
    }



    /**
     * Resets every pager to start page.
     *
     */
    public function reset()
    {
        $this->setCurrentPage(1);
    }



    /**
     * Returns the current page which is valid for all pagers.
     *
     * @return integer
     */
    public function getCurrentPage()
    {
        // If number of items has changed between to requests, we can check here, whether we still have enough items to be on recent page
        if ($this->currentPage > $this->getLastPage()) {
            $this->reset();
        }
        return $this->currentPage;
    }



    /**
     * Returns true if any of the pages is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }



    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }



    /**
     * Sets the total item count for each pager in the collection.
     * Could be used by a list to inject the amount of rows.
     *
     * @param integer $itemCount The amount of items.
     */
    public function setItemCount($itemCount)
    {
        foreach ($this as $pager) {        /** @var PagerInterface $pager */
            $pager->setItemCount($itemCount);
        }
    }



    /**
     * (non-PHPdoc)
     *
     * @see PunktDe_PtExtbase_State_IdentifiableInterface::getObjectNamespace()
     */
    public function getObjectNamespace()
    {
        return $this->configurationBuilder->getListIdentifier() . '.pagerCollection';
    }


    /**
     * @param array $GPVars
     */
    public function _injectGPVars($GPVars)
    {
        if (array_key_exists('page', $GPVars)) {
            $this->currentPage = (int) $GPVars['page'] > 0 ? (int) $GPVars['page'] : 1;
        }
    }


    /**
     * @param array $sessionData
     */
    public function _injectSessionData(array $sessionData)
    {
        if (array_key_exists('page', $sessionData)) {
            $this->currentPage = (int) $sessionData['page'] > 0 ? (int) $sessionData['page'] : 1;
        }
    }



    /**
     * (non-PHPdoc)
     *
     * @see SessionPersistableInterface::persistToSession()
     */
    public function _persistToSession()
    {
        if ($this->currentPage > 1) {
            return ['page' => $this->currentPage];
        } else {
            /*
             *  Page 1 is default therefore we don't need it in the session
             *
             *  Don't change this, this belongs to RealUrl configuration if everything is
             *  put into URL.
             */
            $this->sessionPersistenceManager->removeSessionDataByNamespace($this->getObjectNamespace());
            return null;
        }
    }



    /**********************************************************
     *
     * Implementing parts of the pager interface.
     * Delegate to the first pager in this collection.
     *
     **********************************************************/
    /**
     * Returns the index of the first page.
     *
     * @return integer Index of first page
     * @throws InternalException
     */
    public function getFirstItemIndex()
    {
        return $this->getItemByIndex(0)->getFirstItemIndex();
    }



    /**
     * Returns the index of the last page.
     *
     * @return integer Index of last page
     * @throws InternalException
     */
    public function getLastItemIndex()
    {
        return $this->getItemByIndex(0)->getLastItemIndex();
    }



    /**
     * Returns the total item count.
     *
     * @return integer The total item count.
     * @throws InternalException
     */
    public function getItemCount()
    {
        return $this->getItemByIndex(0)->getItemCount();
    }



    /**
     * Returns the items per page.
     *
     * @return integer Amount of items per page.
     * @throws InternalException
     */
    public function getItemsPerPage()
    {
        return $this->getItemByIndex(0)->getItemsPerPage();
    }



    /**
     * Set itmesPerPage
     *
     * @param integer $itemsPerPage
     */
    public function setItemsPerPage($itemsPerPage)
    {
        foreach ($this->itemsArr as $pager) {    /* @var $pager DefaultPager */
            $pager->setItemsPerPage($itemsPerPage);
        }
    }



    /**
     * Set the page by row index
     *
     * @param integer $rowIndex
     * @throws InternalException
     */
    public function setPageByRowIndex($rowIndex)
    {
        $rowIndex++; // rowindex = 0 means item 1
        $pageIndex = ceil($rowIndex / $this->getItemsPerPage());
        $this->setCurrentPage($pageIndex);
    }



    /**
     * Returns an array with the index=>pageNumber pairs
     *
     * @return array PageNumbers
     * @throws InternalException
     */
    public function getPages()
    {
        return $this->getItemByIndex(0)->getPages();
    }



    /**
     * Return pager by Identifier
     *
     * @param string $pagerIdentifier
     * @throws \Exception
     * @return PagerInterface
     */
    public function getPagerByIdentifier($pagerIdentifier)
    {
        if (!$this->hasItem($pagerIdentifier)) {
            throw  new \Exception('No pager configuration with id ' . $pagerIdentifier . ' found. 1282216891');
        }
        return $this->getItemById($pagerIdentifier);
    }



    /**
     * @return integer the item offset
     * @throws InternalException
     */
    public function getItemOffset()
    {
        return $this->getItemByIndex(0)->getItemOffset();
    }



    /**
     * Returns the last page index
     *
     * @return integer Index of last page
     * @throws InternalException
     */
    public function getLastPage()
    {
        return $this->getItemByIndex(0)->getLastPage();
    }



    /**
     * Returns the first page index
     *
     * @return integer Index of first page
     * @throws InternalException
     */
    public function getFirstPage()
    {
        return $this->getItemByIndex(0)->getFirstPage();
    }



    /**
     * Returns the previous page index
     *
     * @return integer Index of previous page
     * @throws InternalException
     */
    public function getPreviousPage()
    {
        return $this->getItemByIndex(0)->getPreviousPage();
    }



    /**
     * Returns the last next index
     *
     * @return integer Index of next page
     * @throws InternalException
     */
    public function getNextPage()
    {
        return $this->getItemByIndex(0)->getNextPage();
    }
}
