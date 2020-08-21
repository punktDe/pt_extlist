<?php


namespace PunktDe\PtExtlist\Domain\Model\Filter\DataProvider;

use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\QueryObject\Query;
use PunktDe\PtExtlist\Utility\DbUtils;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 */
class GroupData extends \PunktDe\PtExtlist\Domain\Model\Filter\DataProvider\AbstractDataProvider
{
    /**
     * Show the group row count
     *
     * @var integer
     */
    protected $showRowCount;



    /**
     * Array of filters to be excluded if options for this filter are determined
     *
     * @var array
     */
    protected $excludeFilters = null;



    /**
     * Holds the filter value to be used when filter is submitted
     *
     * @var \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig
     */
    protected $filterField;



    /**
     * Holds an collection of fieldconfigs to be used as displayed values for the filter (the options that can be selected)
     *
     * @var \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection
     */
    protected $displayFields = null;



    /**
     * Holds a comma seperated list of additional tables that are required for this filter
     *
     * @var string
     */
    protected $additionalTables;



    /**
     * Set DisplayFields by TS-Settings
     *
     * @param array $displayFieldSettings
     */
    protected function setDisplayFieldsByTSConfig($displayFieldSettings)
    {
        if ($displayFieldSettings) {
            $displayFields = GeneralUtility::trimExplode(',', $displayFieldSettings);
            $this->displayFields = $this->dataBackend->getFieldConfigurationCollection()->extractCollectionByIdentifierList($displayFields);
        } else {
            $fieldIdentifierList = GeneralUtility::trimExplode(',', $this->filterConfig->getSettings('fieldIdentifier'));
            $this->displayFields = $this->dataBackend->getFieldConfigurationCollection()->extractCollectionByIdentifierList($fieldIdentifierList);
        }
    }



    /**
     * Returns an array of options to be displayed by filter
     * for a given array of fields
     *
     * @param array FieldConfig
     * @return array Options to be displayed by filter
     */
    protected function getRenderedOptionsByFields($fields)
    {
        $renderedOptions = [];
        $options =& $this->getOptionsByFields($fields);

        if (is_array($options) && count($options) === 0 || $options === null) {
            return $renderedOptions;
        }

        foreach ($options as $optionData) {
            $optionKey = $optionData[$this->filterField->getIdentifier()];

            $renderedOptions[$optionKey] = $optionData;
            $renderedOptions[$optionKey]['value'] = $this->renderOptionData($optionData);
            $renderedOptions[$optionKey]['selected'] = false;
        }

        return $renderedOptions;
    }



    /**
     * Get the raw data from the database
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig $fields
     * @return array
     */
    protected function getOptionsByFields($fields)
    {
        $groupDataQuery = $this->buildGroupDataQuery($fields);
        $excludeFiltersArray = $this->buildExcludeFiltersArray();
        return $this->dataBackend->getGroupData($groupDataQuery, $excludeFiltersArray, $this->filterConfig);
    }



    /**
     * Build the group data query to retrieve the group data
     *
     * @param array FieldConfig $fields
     * @return Query
     */
    protected function buildGroupDataQuery($fields)
    {
        $groupDataQuery = new Query();

        foreach ($fields as $selectField) {
            $groupDataQuery->addField(DbUtils::getAliasedSelectPartByFieldConfig($selectField));
        }

        if ($this->additionalTables != '') {
            $groupDataQuery->addFrom($this->additionalTables);
        }

        foreach ($this->displayFields as $displayField) { /* @var $displayField FieldConfig */
            $groupDataQuery->addSorting($displayField->getIdentifier(), Query::SORTINGSTATE_ASC);
        }

        if ($this->showRowCount) {
            // TODO only works with SQL!
            $groupDataQuery->addField(sprintf('count("%s") as rowCount', $this->filterField->getTableFieldCombined()));
        }

        $groupFields = [];
        foreach ($this->getFieldsRequiredToBeSelected() as $field) { /* @var $field FieldConfig */
            $groupFields[] = $field->getIdentifier();
        }

        $groupDataQuery->addGroupBy(implode(',', $groupFields));

        return $groupDataQuery;
    }



    /**
     *
     * @return array
     */
    protected function getFieldsRequiredToBeSelected()
    {
        if ($this->filterField) {
            $mergedFields = clone $this->displayFields;
            $mergedFields->addFieldConfig($this->filterField);

            return $mergedFields;
        } else {
            return $this->displayFields;
        }
    }



    /**
     * Returns associative array of exclude filters for given TS configuration
     *
     * @throws Exception on wrong configuration for exclude filters
     * @return array Array with exclude filters. Encoded as (array('filterboxIdentifier' => array('excludeFilter1','excludeFilter2',...)))
     */
    protected function buildExcludeFiltersArray()
    {
        $excludeFiltersAssocArray = [$this->filterConfig->getFilterboxIdentifier() => [$this->filterConfig->getFilterIdentifier()]];

        if ($this->excludeFilters) {
            foreach ($this->excludeFilters as $excludeFilter) {
                list($filterboxIdentifier, $filterIdentifier) = explode('.', $excludeFilter);

                if ($filterIdentifier != '' && $filterboxIdentifier != '') {
                    $excludeFiltersAssocArray[$filterboxIdentifier][] = $filterIdentifier;
                } else {
                    throw new Exception('Wrong configuration of exclude filters for filter ' . $this->filterConfig->getFilterboxIdentifier() . '.' . $this->filterConfig->getFilterIdentifier() . '. Should be comma seperated list of "filterboxIdentifier.filterIdentifier" but was ' . $excludeFilter . ' 1281102702');
                }
            }
        }

        return $excludeFiltersAssocArray;
    }



    /**
     * Init the dataProvider by TS-conifg
     *
     * @param array $filterSettings
     */
    protected function initDataProviderByTsConfig($filterSettings)
    {
        $filterField = trim($filterSettings['filterField']);
        if ($filterField) {
            $this->filterField = $this->resolveFieldConfig($filterField);
        } else {
            $this->filterField = $this->filterConfig->getFieldIdentifier()->getItemByIndex(0);
        }

        $this->setDisplayFieldsByTSConfig(trim($filterSettings['displayFields']));

        if (array_key_exists('excludeFilters', $filterSettings) && trim($filterSettings['excludeFilters'])) {
            $this->excludeFilters = GeneralUtility::trimExplode(',', $filterSettings['excludeFilters']);
        }

        if (array_key_exists('additionalTables', $filterSettings)) {
            $this->additionalTables = $filterSettings['additionalTables'];
        }

        if (array_key_exists('showRowCount', $filterSettings) && $filterSettings['showRowCount']) {
            $this->showRowCount = $filterSettings['showRowCount'];
        }
    }



    /**
     * Get the field config object by fieldIdentifier string
     *
     * @param string $fieldIdentifier
     * @return \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig
     */
    protected function resolveFieldConfig($fieldIdentifier)
    {
        return $this->dataBackend->getFieldConfigurationCollection()->getFieldConfigByIdentifier($fieldIdentifier);
    }



    /****************************************************************************************************************
     * Methods implementing "DataProvider_DataProviderInterface"
     *****************************************************************************************************************/

    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/DataProvider/DataProvider_DataProviderInterface::init()
     */
    public function init()
    {
        $this->initDataProviderByTsConfig($this->filterConfig->getSettings());
    }



    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/DataProvider/DataProvider_DataProviderInterface::getRenderedOptions()
     */
    public function getRenderedOptions()
    {
        $fields = $this->getFieldsRequiredToBeSelected();
        return $this->getRenderedOptionsByFields($fields);
    }
}
