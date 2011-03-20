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
 * GPValueViewHelper
 * 
 * @author Daniel Lienert 
 * @package ViewHelpers
 * @subpackage NameSpace
 */
class Tx_PtExtlist_ViewHelpers_Namespace_GPArrayViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {
	
	/**
	 * render build key/value GET/POST-array within the namespace of the given object
	 * 
	 * @param string $arguments : list of arguments
	 * @param Tx_PtExtbase_State_IdentifiableInterface $object
	 * 	either as list of 'key : value' pairs 
	 *  or as list of properties wich are then recieved from the object
	 * @param string $nameSpace
	 * @return array GPArray of objects namespace
	 */
	public function render($arguments, $object = NULL, $nameSpace = '') {
		$GetPostValueArray = array();
		$argumentStringArray = $this->getArgumentArray($arguments);
		$argumentArray = array();
		
		foreach($argumentStringArray as $key => $value) {
			if($value === false) {
				$value = $this->getObjectValue($object, $key);
			}
			
			if(!$nameSpace) {
				$argumentArray = $this->buildObjectValueArray($object, $key, $value);
			} else {
				$argumentArray = $this->buildNamespaceValueArray($nameSpace, $key, $value);
			}
		}

		$this->addStateHash($argumentArray);
		return $argumentArray;
	}
	
	
	
	/**
	 * Add the stateHash to the argumentArray, used to identifiy the current state if the
	 * list operates in no-session-mode
	 * 
	 * @param array $argumentArray
	 */
	public function addStateHash(&$argumentArray) {
		$extBaseContext = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_PtExtlist_Extbase_ExtbaseContext');
		
		if($extBaseContext->isInCachedMode()) {
			$argumentArray['state'] = Tx_PtExtbase_State_Session_SessionPersistenceManagerFactory::getInstance()->getSessionDataHash();
		}
	}
	
	
	
	/**
	 * Use the objects getter to get the value
	 * 
	 * @param Tx_PtExtbase_State_IdentifiableInterface $object
	 * @param string $property
	 * @return mixed value
	 */
	protected function getObjectValue(Tx_PtExtbase_State_IdentifiableInterface $object, $property) {
		$getterMethod = 'get'.ucfirst($property);
		Tx_PtExtbase_Assertions_Assert::isTrue(method_exists($object, $getterMethod), array('message' => 'The Object' . get_class($object) . ' has no getter method "'  . $getterMethod . '" ! 1280929630'));
		
		return $object->$getterMethod();
	}
	
	
	
	/**
	 * Get an argument array out of a string
	 * 
	 * @param string $argumentString
	 * @return array
	 */
	public function getArgumentArray($argumentString) {
		$argumentArray = array();
		$argumentChunks = t3lib_div::trimExplode(',', $argumentString);
		
		foreach($argumentChunks as $argument) {
			if(strstr($argument, ':')) {
				list($key, $value) = t3lib_div::trimExplode(':', $argument);
				$argumentArray[$key] = $value;	
			} else {
				$key = $argument;
				$argumentArray[$key] = false;
			}
		}
		
		return $argumentArray;
	}
	
	
	
	/**
	 * Get the valueArray with the right objectNamespace
	 * 
	 * @param Tx_PtExtbase_State_IdentifiableInterface $object
	 * @param string $key
	 * @param string $value
	 */
	public function buildObjectValueArray(Tx_PtExtbase_State_IdentifiableInterface $object, $key, $value) {
		$nameSpace = $object->getObjectNamespace();
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($nameSpace, array('message' => 'No ObjectNamespace returned from Obejct ' . get_class($object) . '! 1280771624'));
		
		return $this->buildNamespaceValueArray($nameSpace, $key, $value);
	}
	
	
	
	/**
	 * Building a namespace array filled with an value.
	 * 
	 * @param string $nameSpace 
	 * @param mixed $value 
	 * @return array The built array filled with the given value.
	 */
	public function buildNamespaceValueArray($nameSpace, $key, $value) {	
		$nameSpaceChunks =  t3lib_div::trimExplode('.', $nameSpace);
		
		$returnArray = array();
		$pointer = &$returnArray;
		
		// Build array
		foreach($nameSpaceChunks as $chunk) {
			$pointer = &$pointer[$chunk];
		}

		// Add value
		$pointer[$key] = $value;
		return $returnArray;
	}
}
?>