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

use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtbase\Configuration\AbstractConfigurationBuilder;
use PunktDe\PtExtbase\Exception\Assertion;
use PunktDe\PtExtbase\Exception\InternalException;
use PunktDe\PtExtlist\Domain\Configuration\Aggregates\AggregateRowConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\Aggregates\AggregateRowConfigCollectionFactory;
use PunktDe\PtExtlist\Domain\Configuration\Base\BaseConfig;
use PunktDe\PtExtlist\Domain\Configuration\Base\BaseConfigFactory;
use PunktDe\PtExtlist\Domain\Configuration\Bookmark\BookmarkConfig;
use PunktDe\PtExtlist\Domain\Configuration\Bookmark\BookmarkConfigFactory;
use PunktDe\PtExtlist\Domain\Configuration\BreadCrumbs\BreadCrumbsConfig;
use PunktDe\PtExtlist\Domain\Configuration\BreadCrumbs\BreadCrumbsConfigFactory;
use PunktDe\PtExtlist\Domain\Configuration\Columns\ColumnConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\Columns\ColumnConfigCollectionFactory;
use PunktDe\PtExtlist\Domain\Configuration\ColumnSelector\ColumnSelectorConfig;
use PunktDe\PtExtlist\Domain\Configuration\ColumnSelector\ColumnSelectorConfigFactory;
use PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollectionFactory;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollectionFactory;
use PunktDe\PtExtlist\Domain\Configuration\DataBackend\DataBackendConfiguration;
use PunktDe\PtExtlist\Domain\Configuration\DataBackend\DataBackendConfigurationFactory;
use PunktDe\PtExtlist\Domain\Configuration\Export\ExportConfig;
use PunktDe\PtExtlist\Domain\Configuration\Export\ExportConfigFactory;
use PunktDe\PtExtlist\Domain\Configuration\Filters\FilterboxConfig;
use PunktDe\PtExtlist\Domain\Configuration\Filters\FilterboxConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\Filters\FilterboxConfigCollectionFactory;
use PunktDe\PtExtlist\Domain\Configuration\Lists\ListConfig;
use PunktDe\PtExtlist\Domain\Configuration\Lists\ListConfigFactory;
use PunktDe\PtExtlist\Domain\Configuration\Lists\ListDefaultConfig;
use PunktDe\PtExtlist\Domain\Configuration\Lists\ListDefaultConfigFactory;
use PunktDe\PtExtlist\Domain\Configuration\Pager\PagerConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\Pager\PagerConfigCollectionFactory;
use PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererChainConfig;
use PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererChainConfigFactory;
use PunktDe\PtExtlist\Domain\Configuration\Sorting\SorterConfig;
use PunktDe\PtExtlist\Domain\Configuration\Sorting\SorterConfigFactory;
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
class ConfigurationBuilder extends AbstractConfigurationBuilder
{
    /**
     * Holds settings to build configuration objects
     *
     * @var array
     */
    protected $configurationObjectSettings = [
        'aggregateData' =>
        ['factory' => AggregateConfigCollectionFactory::class],
        'aggregateRows' =>
        ['factory' => AggregateRowConfigCollectionFactory::class],
        'base' =>
        ['factory' => BaseConfigFactory::class],
        'bookmarks' =>
        ['factory' => BookmarkConfigFactory::class,
            'prototype' => 'bookmarks'],
        'columns' =>
        ['factory' => ColumnConfigCollectionFactory::class],
        'columnSelector' =>
        ['factory' => ColumnSelectorConfigFactory::class,
            'prototype' => 'columnSelector'],
        'dataBackend' =>
        ['factory' => DataBackendConfigurationFactory::class,
            'tsKey' => 'backendConfig'],
        'export' =>
        ['factory' => ExportConfigFactory::class],
        'fields' =>
        ['factory' => FieldConfigCollectionFactory::class],
        'filter' =>
        ['factory' => FilterboxConfigCollectionFactory::class,
            'tsKey' => 'filters'],
        'list' =>
        ['factory' => ListConfigFactory::class,
            'prototype' => 'list',
            'tsKey' => null],
        'listDefault' =>
        ['factory' => ListDefaultConfigFactory::class,
            'tsKey' => 'default'],
        'pager' =>
        ['factory' => PagerConfigCollectionFactory::class,
            'prototype' => 'pager'],
        'rendererChain' =>
        ['factory' => RendererChainConfigFactory::class,
            'prototype' => 'rendererChain'],
        'breadCrumbs' =>
        ['factory' => BreadCrumbsConfigFactory::class,
            'tsKey' => 'breadCrumbs'],
        'sorter' =>
        ['factory' => SorterConfigFactory::class,
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
    public function __construct(array $settings, string $listIdentifier = null)
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
        Assert::isArray($settings['prototype'], ['message' => 'The basic settings are not available. Maybe the static typoscript template for pt_extlist is not included on this page. 1281175089']);
        $this->prototypeSettings = $settings['prototype'];
    }



    /**
     * Sets the list identifier of current list
     *
     * @param array $settings
     * @param string $listIdentifier
     * @throws \Exception if no list configuration can be found for list identifier
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
            throw new \Exception('No list configuration can be found for list identifier "' . $listIdentifier . '" 1278419536' . '<br>' . $helpListIdentifier);
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
     * @return FilterboxConfig
     *
     * @throws Assertion
     * @throws InternalException
     */
    public function getFilterboxConfigurationByFilterboxIdentifier($filterboxIdentifier)
    {
        Assert::isNotEmptyString($filterboxIdentifier, ['message' => 'Filterbox identifier must not be empty! 1277889453']);
        return $this->buildFilterConfiguration()->getItemById($filterboxIdentifier);
    }



    /**
     * Returns a singleton instance of databackend configuration
     * @return DatabackendConfiguration
     * @throws \Exception
     */
    public function buildDataBackendConfiguration()
    {
        return $this->buildConfigurationGeneric('dataBackend');
    }



    /**
     * Returns a singleton instance of a fields configuration collection for current list configuration
     *
     * @return FieldConfigCollection
     * @throws \Exception
     */
    public function buildFieldsConfiguration()
    {
        return $this->buildConfigurationGeneric('fields');
    }



    /**
     * Returns a singleton instance of a aggregateData configuration collection for current list configuration
     *
     * @return AggregateConfigCollection
     * @throws \Exception
     */
    public function buildAggregateDataConfig()
    {
        return $this->buildConfigurationGeneric('aggregateData');
    }



    /**
     * return a singelton instance of aggregate row collection
     *
     * @return AggregateRowConfigCollection
     * @throws \Exception
     */
    public function buildAggregateRowsConfig()
    {
        return $this->buildConfigurationGeneric('aggregateRows');
    }



    /**
     * return a singleton instance of export configuratrion
     * @return ExportConfig
     * @throws \Exception
     */
    public function buildExportConfiguration()
    {
        return $this->buildConfigurationGeneric('export');
    }



    /**
     * Returns a singleton instance of columns configuration collection for current list configuration
     *
     * @return ColumnConfigCollection
     * @throws \Exception
     */
    public function buildColumnsConfiguration()
    {
        return $this->buildConfigurationGeneric('columns');
    }



    /**
     * Returns a singleton instance of a filter configuration collection for current list configuration
     *
     * @return FilterboxConfigCollection
     * @throws \Exception
     */
    public function buildFilterConfiguration()
    {
        return $this->buildConfigurationGeneric('filter');
    }



    /**
     * Returns a singleton instance of the renderer chain configuration object.
     *
     * @return RendererChainConfig
     * @throws \Exception
     */
    public function buildRendererChainConfiguration()
    {
        return $this->buildConfigurationGeneric('rendererChain');
    }



    /**
     * Returns base configuration
     *
     * @return BaseConfig
     * @throws \Exception
     */
    public function buildBaseConfiguration()
    {
        return $this->buildConfigurationGeneric('base');
    }



    /**
     * Returns bookmark configuration
     *
     * @return BookmarkConfig
     * @throws \Exception
     */
    public function buildBookmarkConfiguration()
    {
        return $this->buildConfigurationGeneric('bookmarks');
    }



    /**
     * @return ListDefaultConfig
     * @throws \Exception
     */
    public function buildListDefaultConfig()
    {
        return $this->buildConfigurationGeneric('listDefault');
    }



    /**
     * Returns configuration object for pager
     *
     * @return PagerConfigCollection Configuration object for pager
     * @throws \Exception
     */
    public function buildPagerConfiguration()
    {
        return $this->buildConfigurationGeneric('pager');
    }



    /**
     * Returns a list configuration object
     *
     * @return ListConfig
     * @throws \Exception
     */
    public function buildListConfiguration()
    {
        return $this->buildConfigurationGeneric('list');
    }



    /**
     * Returns a breadcrumbs configuration object
     *
     * @return BreadCrumbsConfig
     * @throws \Exception
     */
    public function buildBreadCrumbsConfiguration()
    {
        return $this->buildConfigurationGeneric('breadCrumbs');
    }



    /**
     * Returns a sorter configuration object
     *
     * @return SorterConfig
     * @throws \Exception
     */
    public function buildSorterConfiguration()
    {
        return $this->buildConfigurationGeneric('sorter');
    }



    /**
     * Returns a columnSelector configuration object
     *
     * @return ColumnSelectorConfig
     * @throws \Exception
     */
    public function buildColumnSelectorConfiguration()
    {
        return $this->buildConfigurationGeneric('columnSelector');
    }
}
