<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2014 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
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

namespace PunktDe\PtExtlist\Domain\Model\Filter\DataProvider;

use \PunktDe\PtExtbase\Domain\Repository;

/**
 * Implements data provider for system categories
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 *
 */

class SysCategory extends \Tx_PtExtlist_Domain_Model_Filter_DataProvider_AbstractDataProvider
{
    /**
     * @var \TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository;


    /**
     * @var integer
     */
    protected $categoryPid;


    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::init()
     */
    public function init()
    {
        $this->initDataProviderByTsConfig($this->filterConfig->getSettings());
    }



    /**
     * Init the dataProvider by TS-conifg
     *
     * @param array $filterSettings
     * @throws \Exception
     */
    protected function initDataProviderByTsConfig($filterSettings)
    {
        if (!array_key_exists('categoryPid', $filterSettings)) {
            throw new \Exception('Please define the categoryPid within the category filter', 1391012707);
        }
        $this->categoryPid = (int) $filterSettings['categoryPid'];
    }


    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::getRenderedOptions()
     */
    public function getRenderedOptions()
    {
        $categories = $this->getCategoryRecords();

        foreach ($categories as $category) { /** @var  \TYPO3\CMS\Extbase\Domain\Model\Category $category */
            $optionKey = $category->getUid();

            $renderedOptions[$optionKey]['value'] = $category->getTitle();
            $renderedOptions[$optionKey]['selected'] = false;
        }

        return $renderedOptions;
    }


    /**
     * @return mixed
     */
    protected function getCategoryRecords()
    {
        return $this->categoryRepository->findByPid($this->categoryPid);
    }
}
