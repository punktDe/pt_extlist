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
 * Class implements a filterbox which is a collection of filters
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Model_Filter_FilterBox extends tx_pttools_objectCollection {

	/**
	 * Filterbox identifier of this filterbox
	 *
	 * @var string
	 */
	protected $filterBoxIdentifier;
	
	
	
	/**
	 * List identifier of list to which this filterbox belongs to
	 *
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * Class name to restrict collection to
	 *
	 * @var string
	 */
	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Model_Filter_FilterInterface';
	
	
	
	/**
	 * Constructor for filterbox
	 *
	 * @param string $filterBoxIdentifier  Identifier of filterbox
	 */
	public function __construct($filterBoxIdentifier, $listIdentifier) {
		tx_pttools_assert::isNotEmptyString($filterBoxIdentifier, array('message' => 'Filterbox identifier was empty!'));
		tx_pttools_assert::isNotEmptyString($listIdentifier, array('message' => 'List identifier was empty!'));
		$this->listIdentifier = $listIdentifier;
		$this->filterBoxIdentifier = $filterBoxIdentifier;
	}
	
	
	
	/**
	 * Returns filterbox identifier
	 *
	 * @return string
	 */
	public function getFilterBoxIdentifier() {
		return $this->filterBoxIdentifier;
	}
	
	
	
	/**
	 * Returns list identifier to which this filterbox belongs to
	 *
	 * @return string
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
	
}

?>