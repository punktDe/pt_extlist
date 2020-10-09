<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt, Joachim Mathes
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

namespace PunktDe\PtExtlist\Domain\Model\Filter\DataProvider;

use PunktDe\PtExtbase\Domain\Repository\PageRepository;

/**
 * Implements data provider for grouped list data
 *
 * @author Joachim Mathes
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class PageTree extends \PunktDe\PtExtlist\Domain\Model\Filter\DataProvider\AbstractDataProvider
{
    /**
     * @var int
     */
    protected $rootPageUid;

    /**
     * @var bool
     */
    protected $respectEnableFields;

    /**
     * @var
     */
    protected $respectDeletedField;

    /**
     * @var PageRepository
     */
    protected $pageRepository;

    /**
     * @param PageRepository $pageRepository
     */
    public function injectPageRepository(PageRepository $pageRepository): void
    {
        $this->pageRepository = $pageRepository;
    }


    /**
     * Init the data provider
     */
    public function init()
    {
        $this->rootPageUid = $this->filterConfig->getSettings('rootPageUid');
        $this->respectEnableFields = $this->filterConfig->getSettings('respectEnableFields');
        $this->respectDeletedField = $this->filterConfig->getSettings('respectDeletedField');
    }

    /**
     * Return the rendered filteroptions
     *
     * @return array filter options
     */
    public function getRenderedOptions()
    {
        $pageTree = $this->pageRepository->getPageTreeFromRootPageUid($this->rootPageUid, $this->respectEnableFields, $this->respectDeletedField);

        $valueData = $this->getPageDataFromTreeLevel($pageTree);

        return $valueData;
    }

    /**
     * @param $pageTree
     * @param int $depth
     *
     * @return array
     */
    protected function getPageDataFromTreeLevel($pageTree, $depth = 0)
    {
        $valueData = [];


        foreach ($pageTree as $pageUid => $pageData) {
            $pageObject = $pageData['pageObject'];

            if ($pageObject instanceof \PunktDe_PtExtbase_Domain_Model_Page) {
                $valueData[$pageUid] = [
                    'value' => sprintf('%s%s (%s)', str_repeat(' - ', $depth), $pageObject->getTitle(), $pageObject->getUid())
                ];
            }

            if (count($pageData['subPages'])) {
                $subPageArray = $this->getPageDataFromTreeLevel($pageData['subPages'], $depth+1);
                $valueData = $valueData + $subPageArray;
            }
        }

        return $valueData;
    }
}
