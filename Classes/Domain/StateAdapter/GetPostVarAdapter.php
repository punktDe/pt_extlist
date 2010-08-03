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
 * Class implements adapter for GET and POST vars to be used by 
 * objects implementing the according interface
 *
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter {
		
	
	/**
	 * Holds array with post vars from current HTTP request
	 *
	 * @var array
	 */
	protected $postVars;
	
	
	
	/**
	 * Holds array with get vars from current HTTP request
	 *
	 * @var array
	 */
	protected $getVars;
		
	
	/**
	 * Injects array as post vars
	 *
	 * @param array $postVars
	 */
	public function injectPostVars(array $postVars = array()) {
		$this->postVars = $postVars;
	}
	

	/**
	 * Injects array as get vars
	 *
	 * @param array $getVars
	 */
	public function injectGetVars(array $getVars = array()) {
		$this->getVars = $getVars;
	}
	
		
	/**
	 * Fills a given object with parameters that correspond to namespace identified by object
	 *
	 * @param Tx_PtExtlist_Domain_StateAdapter_GetPostVarInjectableInterface $object
	 */
	public function injectParametersInObject(Tx_PtExtlist_Domain_StateAdapter_GetPostVarInjectableInterface $object) {
		$object->injectGPVars( $this->extractPgVarsByNamespace($object->getObjectNamespace()));
	}

	
	/**
	 * Extracts merged GP vars for a given namespace. Merges Get vars over Post vars
	 *
	 * @param string $namespace
	 * @return array Merged get and post vars for given namespace
	 */
	public function extractGpVarsByNamespace($namespace) {
		$getVars = $this->getGetVarsByNamespace($namespace);
		$postVars = $this->getPostVarsByNamespace($namespace);
		if (is_array($postVars) && is_array($getVars)) {
			$mergedArray = t3lib_div::array_merge_recursive_overrule(
	                $postVars,
	                $getVars
	            );
		    return $mergedArray;
		} else {
			return $getVars;
		}
	}
	
	
	
	/**
	 * Extracts merged GP vars for a given namespace. Merges Post vars over Get vars
     *
     * @param string $namespace
     * @return array Merged get and post vars for given namespace
	 */
	public function extractPgVarsByNamespace($namespace) {
		$getVars = $this->getGetVarsByNamespace($namespace);
		$postVars = $this->getPostVarsByNamespace($namespace);
		if (is_array($getVars) && is_array($postVars)) {
			$mergedArray = t3lib_div::array_merge_recursive_overrule(
	                $getVars,
	                $postVars
	            );
	        return $mergedArray;
		} else {
			return $postVars;
		}
	}
	
	
	
	/**
	 * Converts a namespace string into a array of namespace chunks
	 *
	 * @param string $namespaceString
	 * @return array
	 */
	private function getNamespaceArrayByNamespaceString($namespaceString) {
		return explode('.', $namespaceString);
	}
	
	
	
	/**
	 * Returns get vars for a given namespace
	 *
	 * @param string $namespace
	 * @return array
	 */
	public function getGetVarsByNamespace($namespace) {
		return $this->getArrayContentByArrayAndNamespace($this->getVars, $namespace);
	}
	
	
	
	/**
	 * Returns post vars for a given namespace
	 *
	 * @param string $namespace
	 * @return array
	 */
	public function getPostVarsByNamespace($namespace) {
		return $this->getArrayContentByArrayAndNamespace($this->postVars, $namespace);
	}
	
	
	
	/**
	 * Returns part of an array according to given namespace
	 *
	 * @param array $array
	 * @param string $namespace
	 * @return array
	 */
	private function getArrayContentByArrayAndNamespace($array, $namespace) {
		$namespaceArray = $this->getNamespaceArrayByNamespaceString($namespace);
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
	
}

?>