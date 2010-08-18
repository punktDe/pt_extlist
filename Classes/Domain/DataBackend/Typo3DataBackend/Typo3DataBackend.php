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
*  This copyright notice MUST APPEAR in all copies of the script
***************************************************************/

/**
 * Data backend for typo3 database
 * 
 * @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 *
 * TODO implement T3 db object methods for query (hidden fields, deleted rows etc...)
 *  
 */
class Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend extends Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend {

	/**
	 * Factory method for data source
	 * 
	 * Only DataBackend knows, which data source to use and how to instantiate it.
	 * So there cannot be a generic factory for data sources and data backend factory cannot instantiate it either!
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_DataBackend_DataSource_Typo3DataSource Data source object for this data backend
	 */
	public static function createDataSource(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$dataSourceConfiguration = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($configurationBuilder->buildDataBackendConfiguration());
		$dataSource =  Tx_PtExtlist_Domain_DataBackend_DataSource_Typo3DataSourceFactory::createInstance($dataSourceConfiguration);
		return $dataSource;
	}
	
	
	/**
	 * Builds where part of query from all parts of plugin
	 *
	 * @return string WHERE part of query without 'WHERE'
	 * @param array $excludeFilters Define filters from which no where clause should be returned (array('filterboxIdentifier' => array('filterIdentifier')))
	 */
	public function buildWherePart($excludeFilters = array()) {
		$wherePart = '';
		$baseWhereClause = $this->getBaseWhereClause();
		$whereClauseFromFilterBoxes = $this->getWhereClauseFromFilterboxes($excludeFilters);
		
		if($baseWhereClause && $whereClauseFromFilterBoxes) {
			$wherePart = '(' . $baseWhereClause . ') AND ' . $whereClauseFromFilterBoxes;
		} else {
			$wherePart = $baseWhereClause.$whereClauseFromFilterBoxes;			
		}
	 
		$wherePart .= $this->getTypo3SpecialFieldsWhereClause();
		
		return $wherePart;
	}
	
	
	/**
	 * Build and return whereclause part with TYPO3 enablefields criterias
	 * for all tables wich are defined in backendConfig.tables and in TCA
	 * 
	 * @return string whereclause part with TYPO3 enablefields criterias
	 */
	protected function getTypo3SpecialFieldsWhereClause() {
		$typo3Tables = t3lib_div::trimExplode(',', $this->backendConfiguration->getDataBackendSettings('tables'), true);
		$specialFieldsWhereClause = '';

		foreach($typo3Tables as $typo3Table) {
			list($table, $alias) = t3lib_div::trimExplode(' ', $table, true);
			
			if (is_array($GLOBALS['TCA'][$typo3Table])) {
	        	$specialFieldsWhereClause .= $GLOBALS['TSFE']->cObj->enableFields($typo3Table);
			}
		}
		
		return $specialFieldsWhereClause;
	}
	
}

?>