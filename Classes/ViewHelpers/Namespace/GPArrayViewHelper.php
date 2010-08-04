<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  
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
 * GPValueViewHelper
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_ViewHelpers_Namespace_GPArrayViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {
	
	/**
	 * render build key/value GET/POST-array within the namespace of the given object
	 * @param Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface $object
	 * @param string $arguments
	 * @return array
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 02.08.2010
	 */
	public function render(Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface $object, $arguments) {
		$GetPostValueArray = array();
		$argumentStringArray = $this->getArgumentArray($arguments);
		
		$nameSpace = $object->getObjectNamespace();
		tx_pttools_assert::isNotEmptyString($nameSpace, array('message' => 'No ObjectNamespace returned from Obejct ' . get_class($object) . '! 1280771624'));
		
		foreach($argumentStringArray as $key => $value) {
			if(!$value) {
				$value = $this->getObjectValue($object, $key);
			}
			 
			$argumentArray[] = $this->buildNamespaceValueArray($nameSpace, $key, $value);
		}

		return count($argumentArray) == 1 ? $argumentArray[0] : $argumentArray;
	}
	
	/**
	 * Use the objects getter to get the value
	 * 
	 * @param Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface $object
	 * @param string $property
	 * @return mixed value
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 04.08.2010
	 */
	protected function getObjectValue(Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface $object, $property) {
		$getterMethod = 'get'.ucfirst($property);
		tx_pttools_assert::isTrue(method_exists($object, $getterMethod), array('message' => 'The Object' . get_class($object) . ' has no getter method '  . $getterMethod . '! 1280929630'));
		
		return $object->$getterMethod();
	}
	
	
	/**
	 * Get an argument array out of a string
	 * 
	 * @param string $argumentString
	 * @return array
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 04.08.2010
	 */
	public function getArgumentArray($argumentString) {
		$argumentArray = array();
		$argumentChunks = t3lib_div::trimExplode(',', $argumentString);
		foreach($argumentChunks as $argument) {
			list($key, $value) = t3lib_div::trimExplode(':', $argument);
			$argumentArray[$key] = $value;
		}
		
		return $argumentArray;
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
		
		array_shift($nameSpaceChunks);
		
		// Build array
		foreach($nameSpaceChunks as $chunk) {
			$pointer = &$pointer[$chunk];
		}

		// Add value
		$pointer[$key] = $value;
		
		return $returnArray;
	}
	
}