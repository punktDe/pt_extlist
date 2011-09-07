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
 * Class implements a factory for sorter.
 * 
 * @author Michael Knoll
 * @package pt_extlist
 * @subpackage Domain\Model\Sorting
 */
class Tx_PtExtlist_Domain_Model_Sorting_SorterFactory {
	
	/**
	 * Holds singleton instance of a sorter
	 *
	 * @var Tx_PtExtlist_Domain_Model_Sorting_Sorter
	 */
	private static $instance = null;
	
	
	
	/**
	 * Factory method for returning a singleton instance of sorter
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public static function getInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		if (self::$instance === null) {
			self::$instance = new Tx_PtExtlist_Domain_Model_Sorting_Sorter();
            self::$instance->injectSorterConfig($configurationBuilder->buildSorterConfiguration());
            // At the moment we have to build list header here, as it is not registered in sorter otherwise.
            // TODO where could we cache list headers?
            Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory::createInstance($configurationBuilder);
		}
		return self::$instance;
	}
	
}
?>