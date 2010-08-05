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
 * Class implements list header collection
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Model_List_Header_ListHeader extends tx_pttools_objectCollection implements Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface {

	/**
	 * TODO add some comment!
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $columnHeader
	 * @param unknown_type $columnIdentifier
	 */
	public function addHeaderColumn(Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $columnHeader, $columnIdentifier) {	
		$this->addItem($columnHeader, $columnIdentifier);
	}
	
	/**
	 * Returns namespace for this object
	 * 
	 * @return string Namespace to identify this object
	 */
	public function getObjectNamespace() {
		return 'tx_ptextlist_pi1.' . $this->listIdentifier . '.headerColumns';
	}
	
	/**
	 * reset session of all list columnHeaders
	 * 
	 * @return unknown_type
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 04.08.2010
	 */
	public function reset() {
		foreach($this->itemsArr as $headerColumn) {
			$headerColumn->reset();
		}
	}
	
}


?>