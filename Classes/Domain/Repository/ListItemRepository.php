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
 * A repository for list items
 */
class Tx_PtExtlist_Domain_Repository_ListItemRepository extends Tx_Extbase_Persistence_Repository {
	
	/**
	 * Mapping configuration
	 *
	 * @var array
	 **/
	protected $settings;
	
	/**
	 * Setter for the mapping configuration
	 *
	 * @param array $mapping 
	 * @return void
	 */
	public function setSettings($settings) {
		$this->settings = $settings;
	}
	
	public function __construct() {
		parent::__construct();
		$this->listItemFactory = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Repository_ListItemFactory');
	}
	
	/**
	 * Finds and returns list items for a given class
	 *
	 * @param string $className The class name
	 * @param array $ordering The ordering
	 * @param string $limit The maximum number of items
	 * @param string $offset The offset to start off
	 * @return Tx_Extbase_Persistence_ObjectStorage An ObjectStorage containing the list items
	 */
	public function findListItemsFor($className, $ordering = NULL, $limit = NULL, $offset = NULL) {
		$query = $this->queryFactory->create($className);
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$query->matching();
		if (!empty($ordering)) $query->setOrderings($ordering);
		if (!empty($limit)) $query->setLimit((int)$limit);
		if (!empty($offset)) $query->setOffset((int)$offset);
		$queryResult = $query->execute();
		return $this->listItemFactory->createListItems($queryResult, $className);
	}
	
}
?>