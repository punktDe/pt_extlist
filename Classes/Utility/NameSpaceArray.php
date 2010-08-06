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
 * Utility to manage NameSpaceStrings and resulting arrays
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Utility_NameSpaceArray {
	
	/**
	 * Returns part of an array according to given namespace
	 *
	 * @param array $array
	 * @param string $namespace
	 * @return array
	 */
	public static function getArrayContentByArrayAndNamespace($array, $namespace) {
		$namespaceArray = self::getNamespaceArrayByNamespaceString($namespace);
		$returnArray = $array;
		foreach($namespaceArray as $namespaceChunk) {
			if (array_key_exists($namespaceChunk, $returnArray)) {
			    $returnArray = $returnArray[$namespaceChunk];
			} else {
				return array();
			}
		}
		return $returnArray;
	}
	
	
	
	/**
	 * Converts a namespace string into a array of namespace chunks
	 *
	 * @param string $namespaceString
	 * @return array
	 */
	protected static function getNamespaceArrayByNamespaceString($namespaceString) {
		return t3lib_div::trimExplode('.', $namespaceString);
	}
	
	/**
	 * Save a value on an array position identfied by namespace
	 * 
	 * @param string $nameSpace
	 * @param array $array array to save the data
	 * @param mixed $data
	 * @return array
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 04.08.2010
	 */
	public static function saveDataInNamespaceTree($nameSpace, array $array, $data) {
		
		$nameSpaceChunks =  t3lib_div::trimExplode('.', $nameSpace);		
		$key = array_pop($nameSpaceChunks);
		$pointer = &$array;
		
		foreach($nameSpaceChunks as $chunk) {		
			$pointer = &$pointer[$chunk];
		}

		$pointer[$key] = $data;
		return $array;
	}
}
?>
