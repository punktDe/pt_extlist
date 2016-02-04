<?php
/***************************************************************
 * Copyright notice
 *
 *   2015 Michael Lihs <lihs@punkt.de>
 * All rights reserved
 *
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/



/**
 * Class implements a new delta pager for pt_extlist
 *
 * The delta pager takes 5 arguments:
 *
 *   n = number of pages
 *   c = current page
 *   d = delta
 *   f = first item delta
 *   l = last item delta
 *
 * From that it creates a pager with the following pages
 *
 *   [ 1 .. f ] ... [ c - d .. c + d ] ... [ n - l + 1 .. n ]
 *
 * Example for n = 12, c = 6 and d = 2 it would create
 *
 *   1  2  ...  4  5  6  7  8  ...  11  12
 *
 * @package Domain
 * @subpackage Model\Pager
 * @author Michael Lihs <lihs@punkt.de>
 * @see Tx_PtExtlist_Tests_Domain_Model_Pager_DeltaPagerTest
 */
class Tx_PtExtlist_Domain_Model_Pager_DeltaPager extends Tx_PtExtlist_Domain_Model_Pager_DefaultPager
{
    /**
     * How many pages before and after the current page should be displayed?
     *
     * @var integer
     */
    protected $delta;



    /**
     * How many pages after the first page should be displayed?
     *
     * @var integer
     */
    protected $firstItemDelta = 0;



    /**
     * How many pages before the last page should be displayed?
     *
     * @var integer
     */
    protected $lastItemDelta = 0;



    /**
     * The item to fill in between pages
     *
     * @var string
     */
    protected $filler;



    public function __construct($pagerConfiguration)
    {
        parent::__construct($pagerConfiguration);
        $this->delta = (int)$this->pagerConfiguration->getSettings('delta');
        $this->firstItemDelta = (int)$this->pagerConfiguration->getSettings('firstItemDelta');
        $this->lastItemDelta = (int)$this->pagerConfiguration->getSettings('lastItemDelta');
        $this->filler = $this->pagerConfiguration->getSettings('fillItem');
    }



    /**
     * Returns an array of pages e.g. with some fill elements.
     */
    public function getPages()
    {
        /*
         * How does this work?
         *
         * Pages are created in 3 blocks:
         *
         * 1. block: [1 .. firstItemDelta]
         * 2. block: [currentPage - delta .. currentPage + delta]
         * 3. block: [lastPage - lastItemDelta + 1 .. lastPage]
         *
         * After that, the 3 blocks are merged in such a way, that any (potential) overlapping is removed.
         *
         * Finally, any gap in between pages is filled by a filler
         */
        $firstBlock = array_combine(range(1, $this->firstItemDelta), range(1, $this->firstItemDelta));
        $secondBlock = array_combine(
            $this->newRange($this->currentPage - $this->delta, $this->currentPage + $this->delta),
            $this->newRange($this->currentPage - $this->delta, $this->currentPage + $this->delta)
        );
        $thirdBlock = array_combine(
            $this->newRange($this->getLastPage() - $this->lastItemDelta + 1, $this->getLastPage()),
            $this->newRange($this->getLastPage() - $this->lastItemDelta + 1, $this->getLastPage())
        );

        return $this->mergeBlocks($firstBlock, $secondBlock, $thirdBlock);
    }



    /**
     * Merges the 3 given blocks in such a way, that we have one array eg.
     *
     * 1 => 1, 2 => 2
     * 5 => 5, 6 => 6, 7 => 7
     * 10 => 10, 11 => 11
     *
     * ===>
     *
     * 1 => 1, 2 => 2, 5 => 5, 6 => 6, 7 => 7, 10 => 10, 11 => 11
     *
     * and fills the "gaps" with the given filler.
     *
     * @param array $block1
     * @param array $block2
     * @param array $block3
     * @return array
     */
    protected function mergeBlocks($block1, $block2, $block3)
    {
        $merged = array();
        foreach ($block1 as $key => $value) {
            if (0 < $key && $key <= $this->getPageCount()) {
                $merged[$key] = intval($value);
            }
        }
        foreach ($block2 as $key => $value) {
            if (0 < $key && $key <= $this->getPageCount()) {
                $merged[$key] = intval($value);
            }
        }
        foreach ($block3 as $key => $value) {
            if (0 < $key && $key <= $this->getPageCount()) {
                $merged[$key] = intval($value);
            }
        }
        $filled = $this->fillGaps($merged);
        return $filled;
    }



    /**
     * Fills the "gaps" in the merged arrays with the given filler
     *
     * 1 => 1, 2 => 2, 5 => 5, 6 => 6, 7 => 7, 10 => 10, 11 => 11
     *
     * ==>
     *
     * 1 => 1, 2 => 2, ffi => ..., 5 => 5, 6 => 6, 7 => 7, bfi => ..., 10 => 10, 11 => 11
     *
     * @param $array
     * @return array
     */
    protected function fillGaps($array)
    {
        $fillers = array('ffi', 'bfi'); // We use those keys for the fillers for downwardscompatibility
        $filledArray = array();
        $previousPage = 0;
        foreach ($array as $page) {
            // Check for gaps like 1 2 3 ^ 6 7 in the given array and add filler
            if ($page != ($previousPage + 1)) {
                if (!array_key_exists($previousPage + 2, $array)) {
                    $filledArray[array_shift($fillers)] = $this->filler;
                } else {
                    // prevent things like 1 2 3 ... 5
                    $filledArray[$previousPage + 1] = $previousPage + 1;
                }
            }
            $previousPage = $page;
            $filledArray[$page] = $page;
        }
        return $filledArray;
    }



    /**
     * Helper method that fixes the strange behavior of range(low,high)
     * if low > high
     *
     * @param $low
     * @param $high
     * @return array
     */
    private function newRange($low, $high)
    {
        if ($low > $high) {
            return array();
        } else {
            return range($low, $high);
        }
    }
}
