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
 * Database Datasource configuration class. Holds configuration parameters for database data sources as MySql
 *
 * @package Domain
 * @subpackage Configuration\DataBackend\DataSource
 * @author Michael Knoll 
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration {


	/**
	 * @var array
	 */
	protected $settings;


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
		$this->settings = $dataSourceSettings;

		$this->host = $dataSourceSettings['host'];
		$this->username = $dataSourceSettings['username'];
		$this->password = $dataSourceSettings['password'];
		$this->databaseName = $dataSourceSettings['databaseName'];
		$this->port = $dataSourceSettings['port'];
	}



	/**
	 * Returns sub array of settings for given array namespace
	 * (e.g. key1.key2.key3 returns settings['key1']['key2']['key3'])
	 *
	 * If no key is given, whole settings array is returned.
	 *
	 * If key does not exist, empty array is returned.
	 *
	 * @param string $key Key of settings array to be returned
	 * @return array
	 */
	public function getSettings($key = '') {
		if ($key != '' ) {
			return Tx_PtExtbase_Utility_NameSpace::getArrayContentByArrayAndNamespace($this->settings, $key);
		} else {
			return $this->settings;
		}
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