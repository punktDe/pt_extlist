<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert , Michael Knoll 
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
 * Link manager provides all links used in this plugin
 * TODO: implement shortlinks
 *
 * @package Domain
 * @subpackage Link
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Link_LinkManager {
	
	/**
	 * @var Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter
	 */
	protected $getPostVarAdapter;
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_List_ListConfig
	 */
	protected $listConfiguration;
	
	
	/**
	 * Inkect the get post var adapter
	 * @param Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter $getPostVarAdapater
	 */
	public function injectGetPostVarAdapter(Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter $getPostVarAdapater) {
		$this->getPostVarAdapter = $getPostVarAdapater;
	}
	
	
	
	/**
	 * Inject the List configuration
	 * @param $listConfig
	 */
	public function injectListConfiguration(Tx_PtExtlist_Domain_Configuration_List_ListConfig $listConfig) {
		$this->listConfiguration = $listConfig;
	}
	
	
	
	/**
	 * Build and return the argument array for the given object
	 * 
	 * @param Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface $object
	 * @param array $properties
	 */
	public function buildArgumentArrayForObject(Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface $object, array $properties) {
		
	}
	
	
	
	
}
?>