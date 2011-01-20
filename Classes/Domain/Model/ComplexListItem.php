<?php
/***************************************************************
*  Copyright notice
*
*  (c)  TODO - INSERT COPYRIGHT
*  All rights reserved
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
 * 
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */


class Tx_PtExtlist_Domain_Model_ComplexListItem extends Tx_PtExtlist_Domain_Model_ListItem {
	
	/**
	 * 
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_PtExtlist_Domain_Model_ListItem>
	 */
	protected $listItems;
	

	/**
	 * Constructor. Initializes all Tx_Extbase_Persistence_ObjectStorage instances.
	 */
	public function __construct() {
		$this->listItems = new Tx_Extbase_Persistence_ObjectStorage();
	}
	
	/**
	 * Getter for listItems
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_PtExtlist_Domain_Model_ListItem> 
	 */
	public function getListItems() {
		return $this->listItems;
	}

	/**
	 * Setter for listItems
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_PtExtlist_Domain_Model_ListItem> $listItems 
	 * @return void
	 */
	public function setListItems(Tx_Extbase_Persistence_ObjectStorage $listItems) {
		$this->listItems = $listItems;
	}
	
	/**
	 * Add a ListItem
	 *
	 * @param Tx_PtExtlist_Domain_Model_ListItem The ListItem to add
	 * @return void
	 */
	public function addListItem(Tx_PtExtlist_Domain_Model_ListItem $listItem) {
		$this->listItems->attach($listItem);
	}

}
?>