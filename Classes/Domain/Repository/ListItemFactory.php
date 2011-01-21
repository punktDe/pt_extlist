<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
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
 * A factory for list items
 */
class Tx_PtExtlist_Domain_Repository_ListItemFactory {
	
	public function createListItems(Tx_Extbase_Persistence_QueryResult $queryResult, $className) {
		$extbaseFrameworkConfiguration = Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration();
		$mappingConfiguration = $extbaseFrameworkConfiguration['persistence']['classes'][$className]['mapping'];
		$result = new Tx_Extbase_Persistence_ObjectStorage();
		$columnNames = array_keys($mappingConfiguration['columns']);
		foreach ($queryResult->getRows() as $row) {
			$complexListItem = new Tx_PtExtlist_Domain_Model_ComplexListItem();
			foreach ($columnNames as $columnName) {
				$propertyName = $mappingConfiguration['columns'][$columnName]['mapOnProperty'];
				$propertyValue = $row->getValue($columnName);
				$complexListItem->addListItem(new Tx_PtExtlist_Domain_Model_SimpleListItem($propertyName, $propertyValue));				
			}
			$result->attach($complexListItem);
		}
		return $result;
	}
	
}
?>