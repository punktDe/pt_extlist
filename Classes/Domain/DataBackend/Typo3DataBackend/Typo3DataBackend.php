<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Data backend for TYPO3 database
 *
 * @author Daniel Lienert
 * @author Michael Knoll
 * @package Domain
 * @subpackage DataBackend\Typo3DataBackend
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
		$dataSourceConfiguration = new Tx_PtExtlist_Domain_Configuration_DataBackend_DataSource_DatabaseDataSourceConfiguration($configurationBuilder->buildDataBackendConfiguration()->getDataSourceSettings());
		$dataSource =  Tx_PtExtlist_Domain_DataBackend_DataSource_Typo3DataSourceFactory::createInstance($configurationBuilder->buildDataBackendConfiguration()->getDataSourceClass(), $dataSourceConfiguration);
		return $dataSource;
	}
	


	/**
	 * Builds where part of query from all parts of plugin
	 *
	 * @return string WHERE part of query without 'WHERE'
	 * @param array $excludeFilters Define filters from which no where clause should be returned (array('filterboxIdentifier' => array('filterIdentifier')))
	 */
	public function buildWherePart($excludeFilters = array()) {
		$baseWhereClause = $this->getBaseWhereClause();
		$whereClauseFromFilterBoxes = $this->getWhereClauseFromFilterboxes($excludeFilters);
		
		if($baseWhereClause && $whereClauseFromFilterBoxes) {
			$wherePart = '(' . $baseWhereClause . ') AND ' . $whereClauseFromFilterBoxes;
		} else {
			$wherePart = $baseWhereClause.$whereClauseFromFilterBoxes;			
		}
	 
		if($this->backendConfiguration->getDataBackendSettings('useEnableFields')) {
			$wherePart .= $wherePart ? $this->getTypo3SpecialFieldsWhereClause() : substr($this->getTypo3SpecialFieldsWhereClause(),5);	
		}
		
		return $wherePart;
	}
	
	
	/**
	 * Build and return whereClause part with TYPO3 enablefields criterias
	 * for all tables which are defined in backendConfig.tables and in TCA
	 * 
	 * @return string whereClause part with TYPO3 enablefields criterias
	 */
	protected function getTypo3SpecialFieldsWhereClause() {
		$typo3Tables = t3lib_div::trimExplode(',', $this->backendConfiguration->getDataBackendSettings('tables'), true);
		$specialFieldsWhereClause = '';

		foreach($typo3Tables as $typo3Table) {
			list($table, $alias) = t3lib_div::trimExplode(' ', $typo3Table, true);
			$alias = trim($alias);

			if (is_array($GLOBALS['TCA'][$table])) {
				$specialFieldsWhereClauseSnippet = Tx_PtExtlist_Utility_RenderValue::getCobj()->enableFields($table);

				if($alias) {
					// Make sure not to replace parts of table names with wrong aliases! So check for ' ' to come before and '.' to come after
					$specialFieldsWhereClauseSnippet = str_replace(' ' . $table . '.',  ' ' . $alias . '.', $specialFieldsWhereClauseSnippet);
                    // Make sure not to replace parts of table names with wrong aliases! So check for '(' to come before and '.' to come after
                    $specialFieldsWhereClauseSnippet = str_replace('(' . $table . '.',  '(' . $alias . '.', $specialFieldsWhereClauseSnippet);
                }

				$specialFieldsWhereClause .= $specialFieldsWhereClauseSnippet;

			}
		}
		
		return $specialFieldsWhereClause;
	}
}

?>