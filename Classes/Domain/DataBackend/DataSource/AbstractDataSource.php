<?php
namespace PunktDe\PtExtlist\Domain\DataBackend\DataSource;


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


use PunktDe\PtExtlist\Domain\Configuration\DataBackend\DataSource\DatabaseDataSourceConfiguration;

/**
 * The base class for all data source objects.
 *
 * TODO use interface here!
 *
 * @package Domain
 * @subpackage DataBackend\DataSource
 * @author Daniel Lienert
 */
abstract class AbstractDataSource
{
    /**
     * Holds a data source configuration object
     *
     * @var DatabaseDataSourceConfiguration
     */
    protected $dataSourceConfiguration;


    /**
     * @var float
     */
    protected $lastQueryDuration;


    /**
     * @var float
     */
    protected $queryStartTime;


    /**
     * Constructor for typo3 data source
     *
     * @param DatabaseDataSourceConfiguration $dataSourceConfiguration
     */
    public function __construct(DatabaseDataSourceConfiguration $dataSourceConfiguration)
    {
        $this->dataSourceConfiguration = $dataSourceConfiguration;
    }


    /**
     * Template method to initialize the dataSource
     */
    public function initialize()
    {
    }


    protected function startTimeMeasure()
    {
        $this->queryStartTime = round(microtime(true) * 1000);
    }


    protected function stopTimeMeasure()
    {
        $this->lastQueryDuration = round(microtime(true) * 1000) - $this->queryStartTime;
        $this->queryStartTime = 0;
    }


    /**
     * @return float
     */
    public function getLastQueryExecutionTime()
    {
        return $this->lastQueryDuration;
    }
}
