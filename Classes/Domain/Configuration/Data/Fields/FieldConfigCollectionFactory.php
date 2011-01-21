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
 *  FieldConfigCollection Factory
 *
 * @package Domain
 * @subpackage Configuration\Data\Fields
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollectionFactory {
	

	/**
	 * Returns an instance of a field config collection for given field settings
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	public static function getInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$fieldsSettings = $configurationBuilder->getSettingsForConfigObject('fields');
		$fieldConfigCollection = self::buildFieldConfigCollection($configurationBuilder, $fieldsSettings);
	    return $fieldConfigCollection;	
	}
	
	
	
	/**
	 * Builds a collection of field config objects for a given settings array
	 *
	 * @param array $fieldSettingsArray
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	protected static function buildFieldConfigCollection(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, array $fieldSettingsArray = null) {
		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		foreach ($fieldSettingsArray as $fieldIdentifier => $fieldSettings) {
			$fieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($configurationBuilder, $fieldIdentifier, $fieldSettings);
			$fieldConfigCollection->addFieldConfig($fieldConfig);
		}
		return $fieldConfigCollection;
	}
	
	
	
}

?>