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
 * Class implements data source for typo3 databases
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Domain
 * @subpackage DataBackend\DataSource
 */
class Tx_PtExtlist_Domain_DataBackend_DataSource_Typo3DataSource extends Tx_PtExtlist_Domain_DataBackend_DataSource_AbstractDataSource {
	
	/**
	 * Holds an instance of typo3 db object
	 *
	 * @var t3lib_DB
	 */
	protected $connection;

	
	/**
	 * Injector for database connection object
	 *
	 * @param t3lib_DB $dataSource
	 */
	public function injectDbObject($dbObject) {
		$this->connection = $dbObject;
	}
	
	
	/**
	 * Executes given SQL query
	 * 
	 *
	 * @param string $query SQL query to be executed
	 */
	public function executeQuery($query) {
		
		try {
			$res = $this->connection->sql_query($query);
	        tx_pttools_assert::isMySQLRessource($res, $this->dbObj);
	        
	        $rows = array();
	        
	        while (($a_row = $this->connection->sql_fetch_assoc($res)) == true) {
	            $rows[] = $a_row;
	        }
	        
	        $this->connection->sql_free_result($res);
	        return $rows;
	        
		} catch(Exception $e) {
			throw new Exception('Error while retrieving data from database using typo3 db object.<br> 
							     Error: ' . $e->getMessage() . ' sql_error says: ' . $this->connection->sql_error() . ' 1280400023<br><br>
							     SQL QUERY: <br>
							     </strong><hr>' . nl2br($query) . '<hr><strong>');
		}
	}
	
}

?>