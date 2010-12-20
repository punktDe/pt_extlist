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

 * @author Daniel Lienert <lienert@punkt.de>
 * @package Domain
 * @subpackage StateAdapter
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
	 * Holds an merged array of get and post vars with Post Vars precedence 
	 * @var array
	 */
	protected $postGetVars = NULL;
	
	
	
	/**
	 * Holds an merged array of get and post vars with Get Vars precedence 
	 * @var array
	 */
	protected $getPostVars = NULL;
	
	
	/**
	 * Holds the extension namespace
	 * 
	 * @var string
	 */
	protected $extensionNameSpace ; 
	
	
	
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
		$object->injectGPVars($this->extractPgVarsByNamespace($object->getObjectNamespace()));
	}

	
	
	/**
	 * return parameters by given namespace
	 * 
	 * @param $namespace string
	 * @return array
	 */
	public function getParametersByNamespace($namespace) {
		return $this->extractPgVarsByNamespace($namespace);	
	}
	
	
	
	/**
	 * return getVares by Namspace
	 * 
	 * @param string $nameSpace
	 * @return array
	 */
	public function getGetVarsByNamespace($nameSpace) {
		return Tx_PtExtlist_Utility_NameSpaceArray::getArrayContentByArrayAndNamespace($this->getVars, $nameSpace);
	}
	
	
	
	/**
	 * return postVars by Namespace
	 * 
	 * @param string $nameSpace
	 * @return array
	 */
	public function getPostVarsByNamespace($nameSpace) {
		return Tx_PtExtlist_Utility_NameSpaceArray::getArrayContentByArrayAndNamespace($this->postVars, $nameSpace);
	}
	
	
	
	/**
	 * Extracts merged GP vars for a given namespace. Merges Get vars over Post vars
	 *
	 * @param string $namespace
	 * @return array Merged get and post vars for given namespace
	 */
	public function extractGpVarsByNamespace($namespace) {	
		return Tx_PtExtlist_Utility_NameSpaceArray::getArrayContentByArrayAndNamespace($this->getMergedGpVars(), $namespace); 
	}

	
	
	/**
	 * Extracts merged GP vars for a given namespace. Merges Post vars over Get vars
     *
     * @param string $namespace
     * @return array Merged get and post vars for given namespace
	 */
	public function extractPgVarsByNamespace($namespace) {	
		return Tx_PtExtlist_Utility_NameSpaceArray::getArrayContentByArrayAndNamespace($this->getMergedPgVars(), $namespace); 	
	}
	
	
	
	/**
	 * Merges Post vars over Get vars
	 * 
	 * @return array Merged get and post vars
	 */
	protected function getMergedPgVars() {
		if(!is_array($this->postGetVars)) {

			$this->postGetVars = $this->postVars;	
			if (is_array($this->getVars) && is_array($this->postVars)) {
				$this->postGetVars = t3lib_div::array_merge_recursive_overrule($this->getVars, $this->postVars);
			}
				
		}
		
		return $this->postGetVars;
	}
	
	
	
	
	/**
	 * Merges Get vars over Post vars
	 * 
	 * @return array Merged get and post vars
	 */
	protected function getMergedGpVars() {
		if(!is_array($this->getPostVars)) {
			
			$this->getPostVars = $this->getVars;	
			if (is_array($this->getVars) && is_array($this->postVars)) {
				$this->getPostVars = t3lib_div::array_merge_recursive_overrule($this->postVars, $this->getVars);
			}
				
		}
		
		return $this->getPostVars;
	}
	
	
	
	/**
	 * Extension Namespace for get/post vars
	 * 
	 * @param string $extensionNameSpace
	 */
	public function setExtensionNamespace($extensionNameSpace) {
		$this->extensionNameSpace = $extensionNameSpace;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getExtensionNameSpace() {
		return $this->extensionNameSpace;
	}	
}
?>