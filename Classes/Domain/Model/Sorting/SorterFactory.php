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
 * Class implements a factory for sorter.
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package pt_extlist
 * @subpackage Domain\Model\Sorting
 */
class Tx_PtExtlist_Domain_Model_Sorting_SorterFactory implements t3lib_Singleton {


	/**
	 * @var Tx_Extbase_Object_ObjectManager
	 */
	private $objectManager;



	/**
	 * @param Tx_Extbase_Object_ObjectManager $objectManager
	 */
	public function injectObjectManager(Tx_Extbase_Object_ObjectManager $objectManager) {
		$this->objectManager = $objectManager;
	}



	/**
	 * Holds singleton instances of sorters for each list
	 *
	 * @var array<Tx_PtExtlist_Domain_Model_Sorting_Sorter>
	 */
	private $instances = array();



	/**
	 * Factory method for returning a singleton instance of sorter
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_Model_Sorting_Sorter
	 */
	public function getInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {

		$listIdentifier = $configurationBuilder->getListIdentifier();

		if ($this->instances[$listIdentifier] === null) {
			$this->instances[$listIdentifier] = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Sorting_Sorter');
			$this->instances[$listIdentifier]->_injectSorterConfig($configurationBuilder->buildSorterConfiguration());

			// At the moment we have to build list header here, as it is not registered in sorter otherwise.
			// TODO refactor this! We can register list header after sorter is build!
			$listHeaderFactory = $this->objectManager->get('Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory'); /* @var $listHeaderFactory Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory */
			$listHeaderFactory->createInstance($configurationBuilder);
		}

		return $this->instances[$listIdentifier];
	}

}