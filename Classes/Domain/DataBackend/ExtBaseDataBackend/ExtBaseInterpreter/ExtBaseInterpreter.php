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
 * Query interpreter for ExtBase data backend. Translates queries into extbase query objects
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_ExtBaseInterpreter extends Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter  {
	
    /**
	 * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::getCriterias()
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 */
	public static function getCriterias(Tx_PtExtlist_Domain_QueryObject_Query $query) {
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::getLimit()
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 */
	public static function getLimit(Tx_PtExtlist_Domain_QueryObject_Query $query) {
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::getSorting()
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 */
	public static function getSorting(Tx_PtExtlist_Domain_QueryObject_Query $query) {
	}
	
	
	
	/**
	 * @see Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter::interpretQuery()
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 */
	static function interpretQuery(Tx_PtExtlist_Domain_QueryObject_Query $query) {
	}
	
	
	
	/**
	 * Translates group by part of query
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Query $query
	 */
	static public function getGroupBy(Tx_PtExtlist_Domain_QueryObject_Query $query) {
	}

}