<?php

class Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection extends tx_pttools_objectCollection {
	
	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig';
	
	
	/**
	 * Adds a field configuration object to collection
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfig
	 */
	public function addFieldConfig(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfig) {
		$this->addItem($fieldConfig, $fieldConfig->getIdentifier());
	}
	
	
	
	/**
	 * Returns a field configuration object for a given identifier
	 *
	 * @param string $identifier
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	public function getFieldConfigByIdentifier($identifier) {
		if ($this->hasItem($identifier)) {
			return $this->getItemById($identifier);
		} else {
			throw new Exception('Field configuration object for key ' . $identifier . 'does not exist!');
		}
	}
	
}

?>