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
 * Class implements factory for array aggregator
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\List\Aggregates
 */
class Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregatorFactory {

	/**
	 *  build the arrayAgregator
	 * 
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 * @return Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator
	 */
	public static function createInstance(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend) {
		$arrayAgregator = new Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregator();
		$arrayAgregator->injectDataBackend($dataBackend);	
	
		return $arrayAgregator;
	}
}
?>