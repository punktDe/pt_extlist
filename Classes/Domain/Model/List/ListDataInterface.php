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
 * Interface for listData structure
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\List
 */
interface Tx_PtExtlist_Domain_Model_List_ListDataInterface {


	/**
	 * @param int $id
	 * @return Tx_PtExtlist_Domain_Model_List_Row $row   Row to be added to list data
	 */
	public function getRow($id);



	/**
	 * @return Tx_PtExtlist_Domain_Model_List_Row $row   Row to be added to list data
	 */
	public function getFirstRow();



	/**
	 * Getter for count of items in list data
	 *
	 * (Fluid-compatible getter for count() method in collection)
	 *
	 * @return int Count of objects in list data
	 */
	public function getCount();

}
