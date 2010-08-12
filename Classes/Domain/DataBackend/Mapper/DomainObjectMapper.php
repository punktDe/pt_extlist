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
 * Mapper for domain object data
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_DataBackend_Mapper_DomainObjectMapper extends Tx_PtExtlist_Domain_DataBackend_Mapper_AbstractMapper {

	/**
	 * Returns list data structure for given domain objects.
	 * Uses configuration currently set in mapper.
	 *
	 * @param mixed $domainObjects
	 * @return Tx_PtExtlist_Domain_Model_List_ListData List data generated for given mapping configuration
	 */
	public function getMappedListData($domainObjects) {
		tx_pttools_assert::isNotNull($this->mapperConfiguration, array('message' => 'No mapper configuration has been set for domain object mapper! 1281635601'));
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		foreach($domainObjects as $domainObject) {
			$listDataRow = new Tx_PtExtlist_Domain_Model_List_Row();
			foreach($this->mapperConfiguration as $fieldConfiguration) { /* @var $fieldConfiguration Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig */
				$property = $fieldConfiguration->getField();
				$value = $this->getObjectPropertyValueByProperty($domainObject, $property);
				$listDataRow->addCell($fieldConfiguration->getIdentifier(), $value);
			}
			$listData->addRow($listDataRow);
		}
		
		return $listData;
	}
	
	
	
	/**
	 * Returns value of a property for a given domain object and property name.
	 * Requires properties to be accessible via getters
	 *
	 * @param mixex $domainObject Object to get property value from
	 * @param string $property Name of property to get value from
	 * @return mixed Value of property
	 */
	public function getObjectPropertyValueByProperty($domainObject, $property) {
		$getterMethodName = 'get' . ucfirst($property);
		if (method_exists($domainObject, $getterMethodName)) {
		    $value = $domainObject->$getterMethodName();
			return $value;
		} else {
			throw new Exception('Trying to get a propert ' . $propery . ' on a domain object that does not implement a getter for this property: ' . get_class_vars($domainObject) . '. Most likely the configuration for mapper is wrong (wrong data.field configuration) 1281636422');
		}
	}
	
}