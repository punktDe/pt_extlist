<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * Class implements data source for mysql databases
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Domain
 * @subpackage DataBackend\DataSource
 */
class Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource extends Tx_PtExtlist_Domain_DataBackend_DataSource_AbstractDataSource {

	/**
	 * Holds an instance of PDO for database connection
	 *
	 * @var PDO
	 */
	protected $connection;
	
	
	
	
	/**
	 * Constructor for datasource
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_DataConfiguration $configuration
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration $configuration) {
		$this->dataSourceConfiguration = $configuration;
	}
	
	
	
	/**
	 * Injector for database connection object
	 *
	 * @param PDO $dbObject
	 */
	public function injectDbObject($dbObject) {
		$this->connection = $dbObject;
	}
	
	
	
	/**
	 * Executes a query using current database connection
	 *
	 * @param string $query SQL query string
	 * @return array Associative array of query result
	 */
	public function executeQuery($query) {
		
		try {
			/* @var $statement PDOStatement */
		    $statement = $this->connection->prepare($query);
		    $statement->execute();
		    $result = $statement->fetchall();
		} catch(Exception $e) {
			throw new Exception('Error while trying to execute query on database! SQL-Statement: ' . $query . 
			    ' 1280322659 - Error message from PDO: ' . $e->getMessage() . 
			    '. Further information from PDO_errorInfo: ' . $statement->errorInfo());
		}
		return $result;
	}
	
	
}

?>