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

/**
 * Implements data provider for grouped list data
 *
 * @author Joachim Mathes
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class PageTree extends \Tx_PtExtlist_Domain_Model_Filter_DataProvider_AbstractDataProvider
{
    /**
     * @var int
     */
    protected $rootPageUid;

    /**
     * @var boolean
     */
    protected $respectEnableFields;

    /**
     * @var
     */
    protected $respectDeletedField;

    /**
     * @var \Tx_PtExtbase_Domain_Repository_PageRepository
     * @inject
     */
    protected $pageRepository;

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
        $valueData = array();


        foreach ($pageTree as $pageUid => $pageData) {
            $pageObject = $pageData['pageObject'];

            if ($pageObject instanceof \Tx_PtExtbase_Domain_Model_Page) {
                $valueData[$pageUid] = array(
                    'value' => sprintf('%s%s (%s)', str_repeat(' - ', $depth), $pageObject->getTitle(), $pageObject->getUid())
                );
            }

            if (count($pageData['subPages'])) {
                $subPageArray = $this->getPageDataFromTreeLevel($pageData['subPages'], $depth+1);
                $valueData = $valueData + $subPageArray;
            }
        }

        return $valueData;
    }
}
