<?php


namespace PunktDe\PtExtlist\Domain\DataBackend;

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
 * This class implements a dummy data backend for generating
 * some output for testing and development.
 *  
 * @author Michael Knoll 
 * @package Domain
 * @subpackage DataBackend
 */
class DummyDataBackend extends \PunktDe\PtExtlist\Domain\DataBackend\AbstractDataBackend
{
    /**
     * Constructor for dummy data backend
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     */
    public function __construct(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        parent::__construct($configurationBuilder);
    }
    
    
    
    /**
     * Generates dummy list data
     *
     * @return \PunktDe\PtExtlist\Domain\Model\Lists\ListData
     */
    public function buildListData()
    {
        $this->updateObserversItemCount($this->dataSource->countItems());
        $rawListData = $this->getListDataFromDataSource();

        $mappedListData = $this->dataMapper->getMappedListData($rawListData);
        return $mappedListData;
    }
    
    
    
    /**
     * Executes query on data source
     *
     * @return array   Raw list data array
     */
    protected function getListDataFromDataSource()
    {
        if ($this->pager->isEnabled()) {
            $startIndex = $this->pager->getFirstItemIndex();
            $endIndex = $this->pager->getLastItemIndex();
            
            return $this->dataSource->executeWithLimit($startIndex, $endIndex);
        }
        
        return $this->dataSource->execute();
    }
    
    
    
    /**
     * Factory method for data source
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @return \PunktDe\PtExtlist\Domain\DataBackend\DataSource\DummyDataSource
     */
    public static function createDataSource(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        return new \PunktDe\PtExtlist\Domain\DataBackend\DataSource\DummyDataSource($configurationBuilder->buildDataBackendConfiguration()->getDataSourceSettings());
    }
    
    
    
    /**
     * Returns count of items 
     *
     * @return integer Total count of items
     */
    public function getTotalItemsCount()
    {
        return 10;
    }



    /**
     * Returns raw data for all filters excluding given filters.
     *
     * Result is given as associative array with fields given in query object.
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $groupDataQuery Query that defines which group data to get
     * @param array $excludeFilters List of filters to be excluded from query (<filterboxIdentifier>.<filterIdentifier>)
     * @param \PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig $filterConfig
     * @return array Array of group data with given fields as array keys
     */
    public function getGroupData(\PunktDe\PtExtlist\Domain\QueryObject\Query $groupDataQuery, $excludeFilters = [],
                                 \PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig $filterConfig = null)
    {
        // TODO implement me!
    }
    
    
    /**
     * (non-PHPdoc)
     * @see Classes/Domain/DataBackend/Tx_PtExtlist_Domain_DataBackend_DataBackendInterface::getAggregatesByConfigCollection()
     */
    public function getAggregatesByConfigCollection(\PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfigCollection $aggregateDataConfigCollection)
    {
        // TODO implement me!
    }

    /**
     * @return \PunktDe\PtExtlist\Domain\Model\Lists\IterationListDataInterface
     */
    public function getIterationListData()
    {
        // TODO: Implement getIterationListData() method.
    }
}
