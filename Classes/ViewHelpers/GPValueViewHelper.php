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
class Tx_PtExtlist_ViewHelpers_GPValueViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {
	
	/**
	 * render a key/value GET/POST-string within the namespace of the given object
	 * @param Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface $object
	 * @param string $property
	 * @param string $value 
	 * @return string
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 02.08.2010
	 */
	public function render(Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface $object, $property, $value=NULL) {
		
		$getPostProperty = '';
	
		$getPostProperty .= $this->renderNamespacePart($object);
		
		/*
		if(!$value) {
			$getMethodName = 'get' . ucfirst($property);
			tx_pttools_assert::isTrue(method_exists($object, $getMethodName), array('message' => 'No value is given and the object ' . get_class($object) . 'has no method ' . $getMethodName . 'to get the value! 1280767124'));
			$value = $object->$getMethodName();
		}
		*/
		if($value === NULL) {
			$getPostProperty .= '['.$property.']';
		} else {
			$getPostProperty .= '['.$property.']=' . $value;
		}
		
		return $getPostProperty;
	}
	
	/**
	 * Render the namespacepart
	 * 
	 * @param $object Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface
	 * @return string namespacepart
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 02.08.2010
	 */
	public function renderNamespacePart(Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface $object) {
		$nameSpace = $object->getObjectNamespace();
		tx_pttools_assert::isNotEmptyString($nameSpace, array('message' => 'No ObjectNamespace returned from Obejct ' . get_class($object) . '! 1280771624'));
		
		$identChunks =  t3lib_div::trimExplode('.', $nameSpace);
		
		$nameSpacePart = array_shift($identChunks);
		
		foreach($identChunks as $chunk) {
			$nameSpacePart .= '['.$chunk.']';
		}
		
		return $nameSpacePart;
	}
	
}
?>