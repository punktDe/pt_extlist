<?php
namespace PunktDe\PtExtlist\Domain\Configuration;

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
use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * Class implements a Builder for all configurations required in pt_extlist.
 *
 * @package Domain
 * @subpackage Configuration
 *
 * @author Daniel Lienert
 * @author Michael Knoll
 * @author Christoph Ehscheidt
 * @see Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderTest
 */
class ConfigurationBuilder extends \PunktDe\PtExtbase\Configuration\AbstractConfigurationBuilder
{
    /**
     * Holds settings to build configuration objects
     *
     * @var array
     */
    protected $configurationObjectSettings = [
        'aggregateData' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactory'],
        'aggregateRows' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfigCollectionFactory'],
        'base' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_Base_BaseConfigFactory'],
        'bookmarks' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_Bookmark_BookmarkConfigFactory',
            'prototype' => 'bookmarks'],
        'columns' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollectionFactory'],
        'columnSelector' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_ColumnSelector_ColumnSelectorConfigFactory',
            'prototype' => 'columnSelector'],
        'dataBackend' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfigurationFactory',
            'tsKey' => 'backendConfig'],
        'export' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_Export_ExportConfigFactory'],
        'fields' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollectionFactory'],
        'filter' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfigCollectionFactory',
            'tsKey' => 'filters'],
        'list' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_List_ListConfigFactory',
            'prototype' => 'list',
            'tsKey' => null],
        'listDefault' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_List_ListDefaultConfigFactory',
            'tsKey' => 'default'],
        'pager' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_Pager_PagerConfigCollectionFactory',
            'prototype' => 'pager'],
        'rendererChain' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfigFactory',
            'prototype' => 'rendererChain'],
        'breadCrumbs' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_BreadCrumbs_BreadCrumbsConfigFactory',
            'tsKey' => 'breadCrumbs'],
        'sorter' =>
        ['factory' => 'Tx_PtExtlist_Domain_Configuration_Sorting_SorterConfigFactory',
            'tsKey' => 'sorter']
    ];



    /**
     * Non-merged settings of plugin
     * @var array
     */
    protected $origSettings;



    /**
     * Holds list identifier of current list
     * @var string
     */
    protected $listIdentifier;



    /**
     * Constructor is private, use getInstance instead!
     *
     * @param array $settings  Settings of extension
     * @param string $listIdentifier
     */
    public function __construct(array $settings, $listIdentifier = null)
    {
        $this->setPrototypeSettings($settings);
        $this->setListIdentifier($settings, $listIdentifier);
        $this->origSettings = $settings;
        $this->mergeAndSetGlobalAndLocalConf();
    }



    /**
     * Check and set the prototype settings
     * @param array $settings
     */
    protected function setPrototypeSettings($settings)
    {
        PunktDe_PtExtbase_Assertions_Assert::isArray($settings['prototype'], ['message' => 'The basic settings are not available. Maybe the static typoscript template for pt_extlist is not included on this page. 1281175089']);
        $this->prototypeSettings = $settings['prototype'];
    }



    /**
     * Sets the list identifier of current list
     *
     * @param array $settings
     * @param string $listIdentifier
     * @throws Exception if no list configuration can be found for list identifier
     */
    protected function setListIdentifier($settings, $listIdentifier = null)
    {
        if (!$listIdentifier) {
            $listIdentifier = $settings['listIdentifier'];
        }

        if (!array_key_exists($listIdentifier, $settings['listConfig'])) {
            if (count($settings['listConfig']) > 0) {
                $helpListIdentifier = 'Available list configurations on this page are: ' . implode(', ', array_keys($settings['listConfig'])) . '.';
            } else {
                $helpListIdentifier = 'No list configurations available on this page.';
            }
            throw new Exception('No list configuration can be found for list identifier "' . $listIdentifier . '" 1278419536' . '<br>' . $helpListIdentifier);
        }

        $this->listIdentifier = $listIdentifier;
    }



    /**
     * Merges configuration of settings in namespace of list identifier
     * with settings from plugin.
     *
     * @param void
     * @return void
     */
    protected function mergeAndSetGlobalAndLocalConf()
    {
        $this->settings = $this->origSettings;

        unset($this->settings['listConfig']);

        if (is_array($this->origSettings['listConfig'][$this->listIdentifier])) {
            ArrayUtility::mergeRecursiveWithOverrule($this->settings, $this->origSettings['listConfig'][$this->listIdentifier]);
        }
    }



    /**
     * Returns identifier of list
     *
     * @return String
     */
    public function getListIdentifier()
    {
        return $this->listIdentifier;
    }



    /**
     * Returns configuration object for filterbox identifier
     *
     * @param string $filterboxIdentifier
     * @return \PunktDe\PtExtlist\Domain\Configuration\Filters\FilterboxConfig
     */
    public function getFilterboxConfigurationByFilterboxIdentifier($filterboxIdentifier)
    {
        PunktDe_PtExtbase_Assertions_Assert::isNotEmptyString($filterboxIdentifier, ['message' => 'Filterbox identifier must not be empty! 1277889453']);
        return $this->buildFilterConfiguration()->getItemById($filterboxIdentifier);
    }



    /**
     * Returns a singleton instance of databackend configuration
     * @return \PunktDe\PtExtlist\Domain\Configuration\DataBackend\DatabackendConfiguration
     */
    public function buildDataBackendConfiguration()
    {
        return $this->buildConfigurationGeneric('dataBackend');
    }



    /**
     * Returns a singleton instance of a fields configuration collection for current list configuration
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection
     */
    public function buildFieldsConfiguration()
    {
        return $this->buildConfigurationGeneric('fields');
    }



    /**
     * Returns a singleton instance of a aggregateData configuration collection for current list configuration
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollection
     */
    public function buildAggregateDataConfig()
    {
        return $this->buildConfigurationGeneric('aggregateData');
    }



    /**
     * return a singelton instance of aggregate row collection
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Aggregates\AggregateRowConfigCollection
     */
    public function buildAggregateRowsConfig()
    {
        return $this->buildConfigurationGeneric('aggregateRows');
    }



    /**
     * return a singleton instance of export configuratrion
     * @return \PunktDe\PtExtlist\Domain\Configuration\Export\ExportConfig
     */
    public function buildExportConfiguration()
    {
        return $this->buildConfigurationGeneric('export');
    }



    /**
     * Returns a singleton instance of columns configuration collection for current list configuration
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Columns\ColumnConfigCollection
     */
    public function buildColumnsConfiguration()
    {
        return $this->buildConfigurationGeneric('columns');
    }



    /**
     * Returns a singleton instance of a filter configuration collection for current list configuration
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Filters\FilterboxConfigCollection
     */
    public function buildFilterConfiguration()
    {
        return $this->buildConfigurationGeneric('filter');
    }



    /**
     * Returns a singleton instance of the renderer chain configuration object.
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererChainConfig
     */
    public function buildRendererChainConfiguration()
    {
        return $this->buildConfigurationGeneric('rendererChain');
    }



    /**
     * Returns base configuration
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Base\BaseConfig
     */
    public function buildBaseConfiguration()
    {
        return $this->buildConfigurationGeneric('base');
    }



    /**
     * Returns bookmark configuration
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Bookmark\BookmarkConfig
     */
    public function buildBookmarkConfiguration()
    {
        return $this->buildConfigurationGeneric('bookmarks');
    }



    /**
     * @return \PunktDe\PtExtlist\Domain\Configuration\Lists\ListDefaultConfig
     */
    public function buildListDefaultConfig()
    {
        return $this->buildConfigurationGeneric('listDefault');
    }



    /**
     * Returns configuration object for pager
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Pager\PagerConfigCollection Configuration object for pager
     */
    public function buildPagerConfiguration()
    {
        return $this->buildConfigurationGeneric('pager');
    }



    /**
     * Returns a list configuration object
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Lists\ListConfig
     */
    public function buildListConfiguration()
    {
        return $this->buildConfigurationGeneric('list');
    }



    /**
     * Returns a breadcrumbs configuration object
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\BreadCrumbs\BreadCrumbsConfig
     */
    public function buildBreadCrumbsConfiguration()
    {
        return $this->buildConfigurationGeneric('breadCrumbs');
    }



    /**
     * Returns a sorter configuration object
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\Sorting\SorterConfig
     */
    public function buildSorterConfiguration()
    {
        return $this->buildConfigurationGeneric('sorter');
    }



    /**
     * Returns a columnSelector configuration object
     *
     * @return \PunktDe\PtExtlist\Domain\Configuration\ColumnSelector\ColumnSelectorConfig
     */
    public function buildColumnSelectorConfiguration()
    {
        return $this->buildConfigurationGeneric('columnSelector');
    }
}
