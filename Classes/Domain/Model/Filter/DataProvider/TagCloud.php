<?php


namespace PunktDe\PtExtlist\Domain\Model\Filter\DataProvider;

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
 * Implements data provider for grouped list data
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 * @see Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_TagCloudTest
 */
class TagCloud extends \PunktDe\PtExtlist\Domain\Model\Filter\DataProvider\GroupData
{
    /**
     * ElementCountField is either a rowcount or an explicitly defined count field
     *
     * @var \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig
     */
    protected $elementCountField = null;



    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_GroupData::initDataProviderByTsConfig()
     */
    protected function initDataProviderByTsConfig($filterSettings)
    {
        parent::initDataProviderByTsConfig($filterSettings);

        if (array_key_exists('countFieldIdentifier', $filterSettings) && $filterSettings['countFieldIdentifier']) {
            $this->elementCountField = $this->filterConfig->getConfigurationBuilder()->buildFieldsConfiguration()->getFieldConfigByIdentifier($filterSettings['countFieldIdentifier']);
        }
    }



    /**
     * Build the group data query to retrieve the group data
     *
     * @param array Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fields
     * @return \PunktDe\PtExtlist\Domain\QueryObject\Query
     */
    protected function buildGroupDataQuery($fields)
    {
        $groupDataQuery = new \PunktDe\PtExtlist\Domain\QueryObject\Query();

        foreach ($fields as $selectField) {
            $groupDataQuery->addField(\PunktDe\PtExtlist\Utility\DbUtils::getAliasedSelectPartByFieldConfig($selectField));
        }

        if ($this->additionalTables != '') {
            $groupDataQuery->addFrom($this->additionalTables);
        }


        $groupDataQuery->addSorting('elementCount', \PunktDe\PtExtlist\Domain\QueryObject\Query::SORTINGSTATE_DESC);


        if ($this->elementCountField !== null) {
            $groupDataQuery->addField(sprintf('%s as elementCount', $this->elementCountField->getTableFieldCombined()));
        } else {
            // TODO only works with SQL!
            $groupDataQuery->addField(sprintf('count("%s") as elementCount', $this->filterField->getTableFieldCombined()));
        }

        $groupDataQuery->addGroupBy($this->filterConfig->getFieldIdentifier()->getItemByIndex(0)->getIdentifier());

        return $groupDataQuery;
    }
}
