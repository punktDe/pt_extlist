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
 * Class implements default pager for pt_extlist
 *
 * @package Domain
 * @subpackage Model\Pager
 * @author Michael Knoll
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Domain_Model_Pager_DefaultPager implements Tx_PtExtlist_Domain_Model_Pager_PagerInterface
{
    /**
     * The pager identifier.
     *
     * @var string
     */
    protected $pagerIdentifier;



    /**
     * The current page.
     * Basis of all calculations.
     *
     * @var integer
     */
    protected $currentPage = 1;



    /**
     * TS settings.
     *
     * @var array
     */
    protected $settings = array();



    /**
     * Amount of items to display per page.
     *
     * @var integer
     */
    protected $itemsPerPage;



    /**
     * The current amount of all items available for the list.
     *
     * @var integer
     */
    protected $totalItemCount;



    /**
     * Holds pager configuration for this pager
     *
     * @var Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig
     */
    protected $pagerConfiguration;



    /**
     * True, if pager is enabled.
     *
     * @var bool
     */
    protected $enabled;



    /**
     * The listIdentifier for which this pager is active.
     *
     * @var string
     */
    protected $listIdentifier;



    /**
     * Constructor for pager
     *
     * @param Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig $pagerConfiguration Configuration to be used for pager
     */
    public function __construct(Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig $pagerConfiguration)
    {
        $this->pagerConfiguration = $pagerConfiguration;
        $this->enabled = $pagerConfiguration->getEnabled();
        $this->settings = $pagerConfiguration->getPagerSettings();
        $this->itemsPerPage = $pagerConfiguration->getItemsPerPage();
        $this->pagerIdentifier = $pagerConfiguration->getPagerIdentifier();
        $this->listIdentifier = $pagerConfiguration->getListIdentifier();
    }



    /**
     * Returns the list identifier.
     *
     * @return string
     */
    public function getListIdentifier()
    {
        return $this->listIdentifier;
    }


    /**
     * @return string
     */
    public function getPagerIdentifier()
    {
        return $this->pagerIdentifier;
    }


    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }


    /**
     * @param int $page
     */
    public function setCurrentPage($page)
    {
        $this->currentPage = $page;
    }


    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled && $this->itemsPerPage > 0;
    }


    /**
     * @return bool
     */
    public function isNeeded()
    {
        return $this->getItemCount() > $this->getItemsPerPage();
    }


    /**
     * @return bool
     */
    public function getNeedsToBeVisible()
    {
        return $this->isNeeded() && $this->isEnabled();
    }


    /**
     * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getItemsPerPage()
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }



    /**
     * Set the ItemsPerPage
     *
     * @param integer $itemsPerPage
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = (int) $itemsPerPage;
    }



    /**
     * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getPages()
     */
    public function getPages()
    {
        $pages = array();
        for ($i = 1; $i <= $this->getPageCount(); $i++) {
            $pages[$i] = $i;
        }
        return $pages;
    }



    /**
     * Returns pager configuration
     *
     * @return Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig
     */
    public function getPagerConfiguration()
    {
        return $this->pagerConfiguration;
    }


    /**
     * @return int
     */
    public function getFirstItemIndex()
    {
        $firstItemIndex = ($this->currentPage - 1) * $this->itemsPerPage + 1;
        return $firstItemIndex > 0 ? $firstItemIndex : 0;
    }


    /**
     * @return int
     */
    public function getLastItemIndex()
    {
        if ($this->itemsPerPage == 0) {
            return $this->totalItemCount;
        } else {
            return (($this->currentPage - 1) * $this->itemsPerPage) + $this->itemsPerPage;
        }
    }


    /**
     * @param int $itemCount
     */
    public function setItemCount($itemCount)
    {
        $this->totalItemCount = (int) $itemCount;
    }


    /**
     * @param $itemCount
     */
    public function updateItemCount($itemCount)
    {
        $this->setItemCount($itemCount);
    }



    /**
     * @see Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getItemCount()
     */
    public function getItemCount()
    {
        return $this->totalItemCount;
    }



    /**
     * @return integer $pageCount
     */
    public function getPageCount()
    {
        if ($this->totalItemCount === 0) {
            return 0;
        }
        if ($this->itemsPerPage === 0) {
            return 1;
        }
        return ceil(intval($this->totalItemCount) / intval($this->itemsPerPage));
    }



    /**
     * @see Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface::getSessionNamespace()
     */
    public function getObjectNamespace()
    {
        return $this->listIdentifier . '.pager';
    }



    /**
     * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getShowFirstLink()
     */
    public function getShowFirstLink()
    {
        return $this->settings['showFirstLink'] == 1;
    }



    /**
     * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getShowLastLink()
     */
    public function getShowLastLink()
    {
        return $this->settings['showLastLink'] == 1;
    }



    /**
     * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getShowNextLink()
     */
    public function getShowNextLink()
    {
        return $this->settings['showNextLink'] == 1;
    }



    /**
     * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getShowPreviousLink()
     */
    public function getShowPreviousLink()
    {
        return $this->settings['showPreviousLink'] == 1;
    }



    /**
     * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getLastPage()
     */
    public function getLastPage()
    {
        $lastPage = $this->itemsPerPage > 0 ? ceil((intval($this->totalItemCount) / intval($this->itemsPerPage))) : 0;
        return $lastPage;
    }



    /**
     * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getFirstPage()
     */
    public function getFirstPage()
    {
        return 1;
    }



    /**
     * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getPreviousPage()
     */
    public function getPreviousPage()
    {
        if ($this->currentPage > $this->getFirstPage()) {
            return $this->currentPage - 1;
        }
        return $this->getFirstPage();
    }



    /**
     * @see Classes/Domain/Model/Pager/Tx_PtExtlist_Domain_Model_Pager_PagerInterface::getNextPage()
     */
    public function getNextPage()
    {
        if ($this->currentPage < $this->getLastPage()) {
            return $this->currentPage + 1;
        }
        return $this->getLastPage();
    }



    /**
     * Returns true if pager is on first page
     *
     * @return bool True, if pager is on first page
     */
    public function getIsOnFirstPage()
    {
        return ($this->currentPage == $this->getFirstPage());
    }



    /**
     * Returns true, if pager is on last page
     *
     * @return bool True, if pager is on last page
     */
    public function getIsOnLastPage()
    {
        return ($this->currentPage == $this->getLastPage());
    }



    /**
     * @return integer
     */
    public function getItemOffset()
    {
        return intval($this->getCurrentPage() - 1) * intval($this->getItemsPerPage());
    }
}
