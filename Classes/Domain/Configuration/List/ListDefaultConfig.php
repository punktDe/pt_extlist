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
 * Class implements configuration for list defaults
 *
 * @package Domain
 * @subpackage Configuration\List
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Configuration_List_ListDefaultConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration {

	/**
	 * ListIdentifier of the default sorting column
	 *
	 * @var string
	 */
	protected $sortingColumn;


	
	/**
	 * The sorting direction of default sorting column
	 * 
	 * @var integer
	 */
	protected $sortingDirection;


	
	/**
	 * Set the properties
	 */
	protected function init() {
		$this->setValueIfExistsAndNotNothing('sortingColumn');
		if($this->sortingColumn) {
			list($this->sortingColumn, $direction) = explode(' ', $this->sortingColumn);
			
			$this->sortingDirection = strtoupper($direction) == 'DESC' ? -1 : 1;
		}
	}
	
	
	
	/**
	 * @return string defaultSortingColumn
	 */
	public function getSortingColumn() {
		return trim($this->sortingColumn);
	}



	/**
	 * @return integer
	 */
	public function getSortingDirection() {
		return $this->sortingDirection;
	}
}
?>