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
 * Interface for pager
 *
 * @package Domain
 * @subpackage Model\Pager
 * @author Michael Knoll
 * @author Christoph Ehscheidt
 */
interface Tx_PtExtlist_Domain_Model_Pager_PagerInterface
{
    /**
     * Checks if this page browser is enabled
     *
     * @return boolean true if enabled
     */
    public function isEnabled();


    /**
     * Returns if a pager is needed, depending
     * on the page size and the row count of the list data.
     *
     * @return bool
     */
    public function isNeeded();


    /**
     * Returns the pager identifier.
     *
     * @return string
     */
    public function getPagerIdentifier();



    /**
     * Returns the current page index.
     *
     * @return integer The current page index
     */
    public function getCurrentPage();



    /**
     * Returns the index of the first page.
     *
     * @return integer Index of first page
     */
    public function getFirstItemIndex();



    /**
     * Returns the index of the last page.
     *
     * @return integer Index of last page
     */
    public function getLastItemIndex();



    /**
     * Sets the current page
     *
     * @param integer $page A page index
     */
    public function setCurrentPage($page);



    /**
     * Sets the total item count.
     * Could be used by a list to inject the amount of rows.
     *
     * @param integer $itemCount The amount of items.
     */
    public function setItemCount($itemCount);



    /**
     * Returns the total item count.
     *
     * @return integer The total item count.
     */
    public function getItemCount();



    /**
     * Returns the items per page.
     *
     * @return integer Amount of items per page.
     */
    public function getItemsPerPage();



    /**
     * Sets the items per page
     *
     * @param integer $itemsPerPage
     */
    public function setItemsPerPage($itemsPerPage);



    /**
     * Returns an array with the index=>pageNumber pairs
     *
     * @return array PageNumbers
     */
    public function getPages();



    /**
     * Let you know if you should display a first page link
     *
     * @return boolean
     */
    public function getShowFirstLink();



    /**
     * Let you know if you should display a last page link
     *
     * @return boolean
     */
    public function getShowLastLink();



    /**
     * Let you know if you should display a next page link
     *
     * @return boolean
     */
    public function getShowNextLink();



    /**
     * Let you know if you should display a previous page link
     *
     * @return boolean
     */
    public function getShowPreviousLink();



    /**
     * Returns the last page index
     *
     * @return integer Index of last page
     */
    public function getLastPage();



    /**
     * Returns the first page index
     *
     * @return integer Index of first page
     */
    public function getFirstPage();



    /**
     * Returns the previous page index
     *
     * @return integer Index of previous page
     */
    public function getPreviousPage();



    /**
     * Returns the last next index
     *
     * @return integer Index of next page
     */
    public function getNextPage();



    /**
     * Returns true if pager is on first page
     *
     * @return bool True, if pager is on first page
     */
    public function getIsOnFirstPage();



    /**
     * Returns true, if pager is on last page
     *
     * @return bool True, if pager is on last page
     */
    public function getIsOnLastPage();



    /**
     * Return the calculated item offset
     *
     * @return integer
     */
    public function getItemOffset();
}
