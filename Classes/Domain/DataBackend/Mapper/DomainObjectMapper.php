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
 * Mapper for domain object data
 * 
 * @package Domain
 * @subpackage DataBackend\Mapper
 * @author Michael Knoll 
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
		
		Tx_PtExtbase_Assertions_Assert::isNotNull($this->mapperConfiguration, array('message' => 'No mapper configuration has been set for domain object mapper! 1281635601'));
		
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		foreach($domainObjects as $domainObject) {
			$listDataRow = new Tx_PtExtlist_Domain_Model_List_Row();
			foreach($this->mapperConfiguration as $fieldConfiguration) { /* @var $fieldConfiguration Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig */
				$property = $this->getPropertyNameByFieldConfig($fieldConfiguration);
				
				if($property == '__object__') {
					$value = $domainObject;
				} else {
					$value = $this->getObjectPropertyValueByProperty($domainObject, $property);
				}
				
				$listDataRow->createAndAddCell($value, $fieldConfiguration->getIdentifier());
			}
			$listData->addRow($listDataRow);
		}
		
		return $listData;
	}
	
	
	
	/**
	 * Returns property name for given fieldConfiguration
	 * 
	 * If __self__ is referenced, only field name is returned. 
	 * If another domain object is referenced, domain object name (table) is prepended
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfiguration
	 * @return string
	 */
	protected function getPropertyNameByFieldConfig(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfiguration) {
        if ($fieldConfiguration->getTable() != '__self__') {  // __self__ references current domain object
            $property = $fieldConfiguration->getTableFieldCombined();
        } else {
        	$property = $fieldConfiguration->getField();
        }
        return $property;
	}
	
	
	
	/**
	 * Returns value of a property for a given domain object and property name.
	 * Requires properties to be accessible via getters
	 *
	 * @param mixed $domainObject Object to get property value from
	 * @param string $property Name of property to get value from
	 * @return mixed Value of property
	 */
	public function getObjectPropertyValueByProperty($domainObject, $property) {
		// if property is aggregated object, resolve object path
		$resolvedObject = $this->resolveObjectPath($domainObject, $property);
		if (get_class($resolvedObject) == 'Tx_Extbase_Persistence_ObjectStorage') {  
			// property is collection of objects
	    	list($objectName, $propertyName) = explode('.', $property);
	    	$value = array();
	    	foreach($resolvedObject as $object) {
	    		$value[] = $this->getPropertyValueSafely($object, $propertyName);
	    	}
	    } else {
	    	// property is scalar value
			if (count(explode('.', $property)) > 1) {
				// check, whether we have '.' in property and get last part
				$property = array_pop(explode('.', $property));
			}
	    	$value = $this->getPropertyValueSafely($resolvedObject, $property);
	    }
	    return $value;
	}
	
	
	
	/**
	 * Returns property value for given object and property name.
	 * Throws exception on non-existing getter method for property.
	 *
	 * @param mixed $object
	 * @param string $property
	 * @return mixed
	 */
	protected function getPropertyValueSafely($object, $property) {
		$getterMethodName = 'get' . ucfirst($property);

		if (method_exists($object, $getterMethodName)) {
			return $object->$getterMethodName();
		} else {
			throw new Exception('Trying to get a property ' . $property . ' on a domain object of type ' . get_class($object) . ' that does not implement a getter for this property.
			Most likely the configuration for mapper is wrong (wrong data.field configuration) 1281636422');
		}
	}
	
	
	
	/**
	 * Returns an object for given object path.
	 * 
	 * Example: objectPath = object1.object2.object3.property will return object3
	 * 
	 * TODO refactor me!
	 *
	 * @param mixed $object
	 * @param string $objectPath
	 * @return mixed
	 */
	public function resolveObjectPath($object, $objectPath) {
		$objectPathParts = explode('.', $objectPath);
		if (count($objectPathParts) == 1) {
			return $object;
		}
	    $getterMethodName = 'get' . ucfirst($objectPathParts[0]);
		if (count($objectPathParts) > 2) {     // Recursive method call for resolving longer object paths
			if (method_exists($object, $getterMethodName)) {
				array_shift($objectPathParts);
				return $this->resolveObjectPath($object->$getterMethodName(), implode('.',$objectPathParts)); 
			} else {
				throw new Exception('Trying to call non-existing method ' . $getterMethodName . ' on ' . get_class($object) . ' ');
			}
		} else {      // Return last object in object path
			if (method_exists($object, $getterMethodName)) {
                return $object->$getterMethodName();
			} else {
				throw new Exception('Trying to call non-existing method ' . $getterMethodName . ' on ' . get_class($object) . ' ');
			}
        }
	}
	
}
?>