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
 * Database Datasource configuration class. Holds configuration parameters for database data sources as MySql
 *
 * @package Domain
 * @subpackage Configuration\DataBackend\DataSource
 * @author Michael Knoll <knoll@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration {

    /**
     * Host name for database connection
     *
     * @var string
     */
	protected $host;
	
	
	
	/**
	 * Username for database connection
	 *
	 * @var string
	 */
	protected $username;
	
	
	
	/**
	 * Password for database connection
	 *
	 * @var string
	 */
	protected $password;
	
	
	
	/**
	 * Name of database to connect to
	 *
	 * @var string
	 */
	protected $databaseName;
	
	
	
	/**
	 * Port number of database to connect to
	 *
	 * @var string
	 */
	protected $port;
	
	
	
	/**
	 * Constructor for data source configuration
	 *
	 * @param array $dataSourceSettings dataBaseDataSourceSettings 
	 */
	public function __construct(array $dataSourceSettings) {
		$this->host = $dataSourceSettings['host'];
		$this->username = $dataSourceSettings['username'];
		$this->password = $dataSourceSettings['password'];
		$this->databaseName = $dataSourceSettings['databaseName'];
		$this->port = $dataSourceSettings['port'];
	}
	
	
	
	/**
	 * Returns database name to connect to
	 * 
	 * @return string
	 */
	public function getDatabaseName() {
		return $this->databaseName;
	}
	
	
	
	/**
	 * Returns host name or ip address to connect to
	 * 
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}
	
	
	
	/**
	 * Returns password for db connection
	 * 
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	
	
	/**
	 * Returns port to connect to
	 * 
	 * @return string
	 */
	public function getPort() {
		return $this->port;
	}
	
	
	
	/**
	 * Returns username for db connection
	 * 
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	
}

?>