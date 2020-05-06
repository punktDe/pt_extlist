<?php


namespace PunktDe\PtExtlist\Domain\Model\Lists\Aggregates;

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
 * Class implements factory the list of aggregate rows
 *  
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Lists\Aggregates
 * @see Tx_PtExtlist_Tests_Domain_Model_List_Aggregates_AggregateListBuilderTest
 */
class AggregateListBuilder
{
    /**
     * @var \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder
     */
    protected $configurationBuilder;
    
    
    
    /**
     * @var \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface
     */
    protected $dataBackend;
    
    
    
    /**
     * Aggregator for aggregating listData
     *  
     * @var \PunktDe\PtExtlist\Domain\Model\Lists\Aggregates\ArrayAggregator
     */
    protected $arrayAggregator;
    
    
    
    /**
     * Holds the aggregated data
     * @var \PunktDe\PtExtlist\Domain\Model\Lists\Row
     */
    protected $aggregatedDataRow;
    
    
    
    /**
     * Reference to the configured renderer
     *  
     * @var \PunktDe\PtExtlist\Domain\Renderer\RendererInterface
     */
    protected $renderer;
    
    
    
    /**
     * @var \PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollection
     */
    protected $aggregateDataConfiguration;
    
    
    
    /**
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     */
    public function __construct(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $this->configurationBuilder = $configurationBuilder;
        $this->aggregateDataConfiguration = $configurationBuilder->buildAggregateDataConfiguration();
    }
    
    
    
    /**
     * @param \PunktDe\PtExtlist\Domain\Model\Lists\Aggregates\ArrayAggregator $arrayAggregator
     */
    public function injectArrayAggregator(\PunktDe\PtExtlist\Domain\Model\Lists\Aggregates\ArrayAggregator $arrayAggregator)
    {
        $this->arrayAggregator = $arrayAggregator;
    }

    
    
    /**
     * @param \PunktDe\PtExtlist\Domain\Renderer\RendererInterface $renderer
     */
    public function injectRenderer(\PunktDe\PtExtlist\Domain\Renderer\RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }
    
    
    
    /**
     * @param \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface $dataBackend
     */
    public function injectDataBackend(\PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface $dataBackend)
    {
        $this->dataBackend = $dataBackend;
    }
    
    
    
    /**
     * Init the aggregates to fill in the columns
     */
    public function init()
    {
    }

    
    
    /**
     * Build the aggregate list
     * @return \PunktDe\PtExtlist\Domain\Model\Lists\ListData
     */
    public function buildAggregateListData()
    {
        $aggreagteListData = new \PunktDe\PtExtlist\Domain\Model\Lists\ListData();
        $aggreagteListData->addRow($this->buildAggregateDataRow());
        return $aggreagteListData;
    }
    
    
    
    /**
     * Build the aggregate data by configuration
     *  
     * @return \PunktDe\PtExtlist\Domain\Model\Lists\Row
     */
    protected function buildAggregateDataRow()
    {
        $dataRow = new \PunktDe\PtExtlist\Domain\Model\Lists\Row();
        $aggregateDataConfigCollection = $this->configurationBuilder->buildAggregateDataConfig();
        
        $aggregatesForPage = $this->getAggregatesForPage($aggregateDataConfigCollection->extractCollectionByScope('page'));
        $aggregatesForQuery = $this->getAggregatesForQuery($aggregateDataConfigCollection->extractCollectionByScope('query'));
        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($aggregatesForQuery, $aggregatesForPage);

        foreach ($aggregatesForQuery as $key => $value) {
            $dataRow->createAndAddCell($value, $key);
        }
        
        return $dataRow;
    }


    /**
     * Get Aggregate data for Page
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollection $aggregateDataConfigCollection
     * @return array
     */
    protected function getAggregatesForPage(\PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollection $aggregateDataConfigCollection)
    {
        $aggregates = [];
    
        foreach ($aggregateDataConfigCollection as $aggregateDataConfig) {
            $aggregates[$aggregateDataConfig->getIdentifier()] = $this->arrayAggregator->getAggregateByConfig($aggregateDataConfig);
        }

        return $aggregates;
    }


    /**
     * Get aggregate data for the whole query
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollection $aggregateDataConfigCollection
     * @return array
     */
    protected function getAggregatesForQuery(\PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollection $aggregateDataConfigCollection)
    {
        if ($aggregateDataConfigCollection->count() > 0) {
            $aggregates = $this->dataBackend->getAggregatesByConfigCollection($aggregateDataConfigCollection);
        }

        if (!is_array($aggregates)) {
            $aggregates = [];
        }
        return $aggregates;
    }
}
