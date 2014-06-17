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
 * Class implements data source for TYPO3 databases
 * 
 * @author Michael Knoll 
 * @package Domain
 * @subpackage DataBackend\DataSource
 */
class Tx_PtExtlist_Domain_DataBackend_DataSource_Typo3DataSource extends Tx_PtExtlist_Domain_DataBackend_DataSource_AbstractDataSource
	implements Tx_PtExtlist_Domain_DataBackend_DataSource_IterationDataSourceInterface {
	
	/**
	 * Holds an instance of typo3 db object
	 *
	 * @var t3lib_DB
	 */
	protected $connection;


	/**
	 * @var pointer		Result pointer / DBAL object
	 */
	protected $resource;

	
	/**
	 * Injector for database connection object
	 *
	 * @param t3lib_DB $dbObject
	 */
	public function injectDbObject($dbObject) {
		$this->connection = $dbObject;
	}


	/**
	 * @param $query
	 * @return Tx_PtExtlist_Domain_DataBackend_DataSource_Typo3DataSource
	 * @throws Exception
	 */
	public function executeQuery($query) {
		
		try {
			$this->resource = $this->connection->sql_query($query);
	        Tx_PtExtbase_Assertions_Assert::isMySQLRessource($this->resource, $this->dbObj);

		} catch(Exception $e) {
			throw new Exception('Error while retrieving data from database using typo3 db object.<br> 
							     Error: ' . $e->getMessage() . ' sql_error says: ' . $this->connection->sql_error() . ' 1280400023<br><br>
							     SQL QUERY: <br>
							     </strong><hr>' . nl2br($query) . '<hr><strong>', 1280400023);
		}

		return $this;
	}


	/**
	 * @return array
	 */
	public function fetchAll() {
		$rows = array();

		while (($a_row = $this->connection->sql_fetch_assoc($this->resource)) == true) {
			$rows[] = $a_row;
		}

		$this->connection->sql_free_result($this->resource);

		return $rows;
	}



	/**
	 * Return data row as array
	 *
	 * @return array
	 */
	public function fetchRow() {
		return $this->connection->sql_fetch_assoc($this->resource);
	}



	/**
	 * Return record count
	 *
	 * @return int
	 */
	public function count() {
		return $this->connection->sql_num_rows($this->resource);
	}



	/**
	 * Rewind the cursor to the first row
	 */
	public function rewind() {
		return $this->connection->sql_data_seek($this->resource, 0);
	}
}
?>