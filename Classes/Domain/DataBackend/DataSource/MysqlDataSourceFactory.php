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
 * @author Daniel Lienert
 * @package Domain
 * @subpackage DataBackend\DataSource
 */
class Tx_PtExtlist_Domain_DataBackend_DataSource_MysqlDataSourceFactory {


	/**
	 * Create instance of mysql data source
	 *
	 * @static
	 * @param string $datSourceClassName
	 * @param Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration $dataSourceConfiguration
	 * @return Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource
	 */
	public static function createInstance($datSourceClassName, Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration $dataSourceConfiguration) {
		$dataSource = new $datSourceClassName($dataSourceConfiguration);
		$dataSource->injectDbObject(self::createDataObject($dataSourceConfiguration));
		return $dataSource;
	}


	/**
	 * Create Database Object
	 *
	 * @static
	 * @param Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration $dataSourceConfiguration
	 * @return PDO
	 * @throws Exception
	 */
	protected static function createDataObject(Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration $dataSourceConfiguration) {

		$dsn = sprintf('mysql:dbname=%s;host=%s;port=%s',
				$dataSourceConfiguration->getDatabaseName(),
				$dataSourceConfiguration->getHost(),
				$dataSourceConfiguration->getPort());

		try {
			$pdo = new PDO($dsn,
					$dataSourceConfiguration->getUsername(),
					$dataSourceConfiguration->getPassword(),
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
			);
		} catch (Exception $e) {
			throw new Exception('Unable to establish MYSQL Databse Connection: ' . $e->getMessage(),  1281215132);
		}



		return $pdo;
	}
}
?>