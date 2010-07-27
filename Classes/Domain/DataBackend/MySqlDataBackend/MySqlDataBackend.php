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



class Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend extends Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend {

	protected $backendConfiguration;
	
	
	
	protected $dbObject;
	
	
	
	protected $pager;
	
	
	
	protected $filter = array();
	
	
	
	protected $columns = array();
	
	
	
	public function injectBackendConfiguration($backendConfiguration) {
		$this->backendConfiguration = $backendConfiguration;
	}
	
	
	
	public function injectDbObject($dbObject) {
		$this->dbObject = $dbObject;
	}
	
	
	
	public function injectPager($pager) {
		$this->pager = $pager;
	}
	
	
	
	public function injectFilters($filters) {
		$this->filter = $filters;
	}
	
	
	
	public function injectColumns($columns) {
		$this->columns = $columns;
	}

	
	
	public function getListData() {
		
	}
	
	
	
	protected function buildQuery() {
		$query = '';
		
		$query .= $this->buildSelectPart();
		$query .= $this->buildFromPart();
		$query .= $this->buildWherePart();
		$query .= $this->buildOrderByPart();
		$query .= $this->buildLimitPart();
		
		return $query;
	}
	
	
	
	protected function buildSelectPart() {
		$selectPart = '';
        // TODO implement me!		
		return $selectPart;
	}
	
	
	
	protected function buildFromPart() {
		$fromPart = '';
		// TODO implement me!
		return $fromPart;
	}
	
	
	
	protected function buildWherePart() {
		$wherePart = '';
		// TODO implement me!
		return $wherePart;
	}
	
	
	
	protected function buildOrderByPart() {
		$orderByPart = '';
		// TODO implement me!
		return $orderByPart;
	}
	
	
	
	protected function buildLimitPart() {
		$limitPart = '';
		// TODO implement me!
		return $limitPart;
	}
	
}

?>