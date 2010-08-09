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
 * Class contains utility functions concerning database related stuff
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Utility_DbUtils {

	/**
	 * Creates an aliased select part for given table and field
	 * 
	 * <table>.<field> AS <table>_<field>
	 *
	 * @param string $table Tablename to select from
	 * @param string $field Fieldname to select from
	 * @return string <table>.<field> AS <table>_<field>
	 */
	public static function getAliasedSelectPartByTableAndField($table, $field) {
		return $table . '.' . $field . ' AS ' . self::getAliasByTableAndField($table, $field);
	}
	
	
	
	/**
	 * Creates an aliased select part for given field config
	 * 
	 * <table>.<field> as <table>_<field>
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfig Field config to get aliased select part for
	 * @return string <table>.<field> as <table>_<field>
	 */
	public static function getAliasedSelectPartByFieldConfig(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfig) {
		return self::getAliasedSelectPartByTableAndField($fieldConfig->getTable(), $fieldConfig->getField());
	}
	
	
	
	/**
	 * Returns select alias for given table and field
	 *
	 * <table>_<field>
	 * 
	 * @param string $table
	 * @param string $field
	 * @return string
	 */
	public static function getAliasByTableAndField($table, $field) {
		return $table . '_' . $field;
	}
	
	
	
	/**
	 * Returns select alias for given field configuration
	 * 
	 * <table>_<field>
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfig Field configuration to get select alias from
	 * @return string <table>_<field>
	 */
	public static function getAliasByFieldConfig(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfig) {
		return self::getAliasByTableAndField($fieldConfig->getTable(), $fieldConfig->getField());
	}
	
}