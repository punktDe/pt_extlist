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
 * Class contains utility functions concerning database related stuff
 * 
 * @author Daniel Lienert 
 * @author Michael Knoll 
 * @package Utility
 */
class Tx_PtExtlist_Utility_DbUtils {


	/**
	 * Creates an aliased select part for given field config
	 * 
	 * <table>.<field> as <fieldIdentifier>
	 * 
	 * Or: if a special mysql string is given
	 * <special mysql string> as <fieldIdentifier>
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfiguration
	 * @return string
	 */
	public static function getAliasedSelectPartByFieldConfig(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfiguration) {
		return self::getSelectPartByFieldConfig($fieldConfiguration) . ' AS ' . $fieldConfiguration->getIdentifier();
	}
	
	
	/**
	  * Creates the select part for given field config
	 * 
	 * <table>.<field>
	 * 
	 * Or: if a special mysql string is given
	 * <special mysql string>
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfiguration
	 */
	public static function getSelectPartByFieldConfig(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfiguration) {
		if($fieldConfiguration->getSpecial()) {
			$selectPart = '(' . $fieldConfiguration->getSpecial() . ')';
		} else {
			$selectPart = $fieldConfiguration->getTableFieldCombined();
		}
		
		return $selectPart;
	}



	/**
	 * Turns a fieldConfigCollection into a list of comma separated selectParts
	 *
	 * @static
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection $fieldConfigCollection
	 * @return string
	 */
	public static function getSelectPartByFieldConfigCollection(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection $fieldConfigCollection) {
		$selectParts = array();

		foreach($fieldConfigCollection as $field) {
			$selectParts[] = self::getSelectPartByFieldConfig($field);
		}

		return implode(', ', $selectParts);
	}
}