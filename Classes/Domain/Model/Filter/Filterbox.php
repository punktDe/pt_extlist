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
class Tx_PtExtlist_Domain_Model_Filter_Filterbox extends tx_pttools_objectCollection
    implements Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface {

	/**
	 * filterbox identifier of this filterbox
	 *
	 * @var string
	 */
	protected $filterboxIdentifier;
	
	
	
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
	 * @param string $filterboxIdentifier  Identifier of filterbox
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig $filterboxConfiguration) {
		$this->listIdentifier = $filterboxConfiguration->getListIdentifier();
		$this->filterboxIdentifier = $filterboxConfiguration->getFilterboxIdentifier();
		$this->filterValidationErrors = new Tx_PtExtlist_Domain_Model_Messaging_MessageCollectionCollection();
	}
	
	
	
	/**
	 * Returns filterbox identifier
	 *
	 * @return string
	 */
	public function getfilterboxIdentifier() {
		return $this->filterboxIdentifier;
	}
	
	
	
	/**
	 * Returns list identifier to which this filterbox belongs to
	 *
	 * @return string
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
	
	
	
	/**
	 * Resets all filters in this filterbox
	 *
	 * @return void
	 */
	public function reset() {
		foreach($this->itemsArr as $filter) { /* @var $filter Tx_PtExtlist_Domain_Model_Filter_FilterInterface */
			$filter->reset();
		}
	}
	
	
	
	/**
	 * Returns a object namespace for filterbox
	 *
	 * @return string Namespace of filterbox
	 */
	public function getObjectNamespace() {
		return 'tx_ptextlist_pi1.' . $this->listIdentifier . '.filters.' . $this->filterboxIdentifier;
	}
	
	
	
	/**
	 * Checks whether all filters in filterbox are validating.
	 *
	 * @return bool True, if all filters are validating
	 */
	public function validate() {
		$validates = true;
		foreach($this->itemsArr as $filter) { /* @var $filter Tx_PtExtlist_Domain_Model_Filter_FilterInterface */
			if (!$filter->validate()) {
				$validates = false;
			}
		}
		return $validates;
	}
	
}

?>