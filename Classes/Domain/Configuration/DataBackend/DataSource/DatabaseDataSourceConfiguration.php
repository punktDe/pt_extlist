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
 * Class Dataconfiguration
 *
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration {

	protected $backendType;
	protected $host;
	protected $username;
	protected $password;
	protected $source;
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_QueryConfiguration
	 */
	protected $queryConfiguration;
	
	public function __construct(array $dataSourceConfiguration) {
		$this->backendType = $backendType;
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->source = $source;
	}
	
	public function getBackendType() {
		return $this->backendType;
	}
	
	public function getHost() {
		return $this->host;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function getSource() {
		return $this->source;
	}
	
	public function setQueryConfiguration(Tx_PtExtlist_Domain_Configuration_QueryConfiguration $queryConfiguration) {
		$this->queryConfiguration = $queryConfiguration;
	}
	
	public function getQueryConfiguration() {
		return $this->queryConfiguration;
	}
	
	
}

?>