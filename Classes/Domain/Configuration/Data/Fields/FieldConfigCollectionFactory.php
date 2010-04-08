<?php

class Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollectionFactory {
	

	/**
	 * Returns an instance of a field config collection for given field settings
	 *
	 * @param array $fieldsSettings
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	public static function getFieldConfigCollection($fieldsSettings) {
		$fieldConfigCollection = self::buildFieldConfigCollection($fieldsSettings);
	    return $fieldConfigCollection;	
	}
	
	
	
	/**
	 * Builds a collection of field config objects for a given settings array
	 *
	 * @param array $fieldSettingsArray
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	protected static function buildFieldConfigCollection($fieldSettingsArray) {
		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		foreach ($fieldSettingsArray as $fieldIdentifier => $fieldSettings) {
			$fieldConfigCollection->addFieldConfig(new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($fieldIdentifier, $fieldSettings));
		}
		return $fieldConfigCollection;
	}
	
	
	
}

?>