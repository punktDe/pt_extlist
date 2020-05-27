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

use Doctrine\DBAL\Driver\Mysqli\MysqliStatement;
use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Statement;
use PDO;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use PunktDe\PtExtlist\Domain\Configuration\DataBackend\DataSource\DatabaseDataSourceConfiguration;


/**
 * Class implements data source for mysql databases
 *  
 * @author Michael Knoll 
 * @package Domain
 * @subpackage DataBackend\DataSource
 * @see Tx_PtExtlist_Tests_Domain_DataBackend_DataSource_MySqlDataSourceTest
 */
class MySqlDataSource extends AbstractDataSource implements IterationDataSourceInterface
{
    /**
     * @var Statement
     */
    protected $statement;

    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * Constructor for datasource
     *
     * @param DatabaseDataSourceConfiguration $configuration
     */
    public function __construct(DatabaseDataSourceConfiguration $configuration)
    {
        $this->dataSourceConfiguration = $configuration;
    }

    /**
     * Injector for database connection object
     *
     * @param PDO $dbObject
     */
    public function injectDbObject($dbObject)
    {
        $this->connection = $dbObject;
    }

    /**
     * Executes a query using current database connection
     *
     * @param $query
     * @return MySqlDataSource
     * @throws \Exception
     */
    public function executeQuery(QueryBuilder $queryBuilder)
    {
        try {
            $this->startTimeMeasure();
            $this->statement = $queryBuilder->execute();
            $this->stopTimeMeasure();
        } catch (\Exception $e) {
            throw new \Exception('Error while trying to execute query on database! SQL-Statement: ' . $queryBuilder->getSQL().
                ' - Error message from PDO: ' . $e->getMessage() .
                '. Further information from PDO_errorInfo: ' . $this->connection->errorInfo(), 1280322659);
        }

        return $this;
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function fetchAll()
    {
        if ($this->statement instanceof MysqliStatement) {
            return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
        }
        echo get_class($this->statement);
        throw new \Exception('No queryBuilder defined to fetch data from. You have to prepare a statement first!', 1347951370);
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function fetchRow()
    {
        if ($this->statement instanceof MysqliStatement) {
            return $this->statement->fetch(\PDO::FETCH_ASSOC);
        }
        throw new \Exception('No statement defined to fetch data from. You have to prepare a statement first!', 1347951371);
    }


    /**
     * @return integer
     */
    public function count()
    {
        return $this->statement->rowCount();
    }



    /**
     * @return mixed
     */
    public function rewind()
    {
        return $this->statement->fetch(\PDO::FETCH_ASSOC, \PDO::FETCH_ORI_FIRST);
    }
}
