<?php
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
 * Class implements data source for mysql databases
 * 
 * @author Michael Knoll 
 * @package Domain
 * @subpackage DataBackend\DataSource
 */
class Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource extends Tx_PtExtlist_Domain_DataBackend_DataSource_AbstractDataSource
	implements Tx_PtExtlist_Domain_DataBackend_DataSource_IterationDataSourceInterface {

	/**
	 * Holds an instance of PDO for database connection
	 *
	 * @var PDO
	 */
	protected $connection;


	/**
	 * @var PDOStatement
	 */
	protected $statement;

	
	
	/**
	 * Constructor for datasource
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration $configuration
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
	 * @param $query
	 * @return Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource
	 * @throws Exception
	 */
	public function executeQuery($query) {
		
		try {
			/* @var $statement PDOStatement */
		    $this->statement = $this->connection->prepare($query);
		    $this->statement->execute();
		} catch(Exception $e) {
			throw new Exception('Error while trying to execute query on database! SQL-Statement: ' . $query . 
			    ' - Error message from PDO: ' . $e->getMessage() .
			    '. Further information from PDO_errorInfo: ' . $this->statement->errorInfo(), 1280322659);
		}

		return $this;
	}


	/**
	 * @return array
	 * @throws Exception
	 */
	public function fetchAll() {

		if($this->statement instanceof PDOStatement) {
			return $this->statement->fetchall(PDO::FETCH_ASSOC);
		} else {
			Throw new Exception('No statement defined to fetch data from. You have to prepare a statement first!', 1347951370);
		}
	}


	/**
	 * @return mixed
	 * @throws Exception
	 */
	public function fetchRow() {

		if($this->statement instanceof PDOStatement) {
			return $this->statement->fetch(PDO::FETCH_ASSOC);
		} else {
			Throw new Exception('No statement defined to fetch data from. You have to prepare a statement first!', 1347951371);
		}

	}


	/**
	 * @return int
	 */
	public function count() {
		return $this->statement->rowCount();
	}



	/**
	 * @return mixed
	 */
	public function rewind() {
		return $this->statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_FIRST);
	}


}

?>