<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
class Tx_PtExtlist_ViewHelpers_Namespace_FormElementNameViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {


	/**
	 * render a key/value GET/POST-string within the namespace of the given object
	 *
	 * @param Tx_PtExtbase_State_IdentifiableInterface $object
	 * @param string $property, single property or propertyPath separated by '.'
	 * @param boolean $addExtPrefix
	 * @return string
	 */
	public function render(Tx_PtExtbase_State_IdentifiableInterface $object, $property, $addExtPrefix = false) {

		$formElementNameSpace = '';

		if($addExtPrefix == true) {
			$formElementNameSpace .= 'tx_ptextlist_pi1.';
		}

		$formElementNameSpace .= $this->getObjectNameSpace($object) . '.';
		$formElementNameSpace .= $property;

		$nameChunks = explode('.', $formElementNameSpace);
		return $this->buildBracketedStringFromArray($nameChunks);
	}


	/**
	 * @param array $nameChunks
	 * @return mixed|string
	 */
	protected function buildBracketedStringFromArray(array $nameChunks) {
		$result = '';
		$result = array_shift($nameChunks);

		foreach($nameChunks as $nameChunk) {
			if($nameChunk) {
				$result .= '[' . $nameChunk . ']';
			}
		}

		return $result;
	}


	/**
	 * @param Tx_PtExtbase_State_IdentifiableInterface $object
	 * @return String
	 */
	public function getObjectNameSpace(Tx_PtExtbase_State_IdentifiableInterface $object) {
		$nameSpace = $object->getObjectNamespace();
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($nameSpace, array('message' => 'No ObjectNamespace returned from Obejct ' . get_class($object) . '! 1280771624'));

		return $nameSpace;
	}
}

?>