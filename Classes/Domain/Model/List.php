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


class Tx_PtExtlist_Domain_Model_List extends Tx_PtExtlist_Domain_Model_ComplexListItem {
	
	/**
	 * The Title of the List
	 *
	 * @var string
	 **/
	protected $title;

	public function __construct($listItems) {
		$this->setListItems($listItems);
	}

	/**
	 * Sets the 
	 *
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the 
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * Returns the names of the list top level list items
	 *
	 * @return void
	 */
	public function getListItemNames() {
		$listItemNames = array();
		$firstListItem = current(current($this->getListItems()));
		foreach ($firstListItem->getListItems() as $listItem) {
			$listItemNames[] = $listItem->getName();
		}
		return $listItemNames;
	}
		
}
?>