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
 * Class implements a filterbox which is a collection of filters
 * 
 * @author Michael Knoll 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_Filterbox extends Tx_PtExtbase_Collection_ObjectCollection
    implements Tx_PtExtbase_State_IdentifiableInterface {

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
	 * Holds an instance of the configuration
	 * 
	 * @var Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig
	 */
	protected $filterBoxConfig;
	
	
	
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
	public function __construct(Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig $filterboxConfiguration = NULL) {
		if($filterboxConfiguration != NULL) {
			$this->injectFilterboxConfiguration($filterboxConfiguration);
		}
	}
	
	
	
	/**
	 * Injects filterbox configuration
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig $filterboxConfiguration
	 */
	public function injectFilterboxConfiguration(Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig $filterboxConfiguration) {
		$this->filterBoxConfig = $filterboxConfiguration;
		$this->listIdentifier = $filterboxConfiguration->getListIdentifier();
		$this->filterboxIdentifier = $filterboxConfiguration->getFilterboxIdentifier();
		$this->filterValidationErrors = new Tx_PtExtlist_Domain_Model_Messaging_MessageCollectionCollection();
	}
	
	
	
	/**
	 * Returns the filterbox configuration
	 * 
	 * @return Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig
	 */
	public function getFilterboxConfiguration() {
		return $this->filterBoxConfig;
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
	 * Returns a new filterbox with accessable filters only.
	 * 
	 * @return Tx_PtExtlist_Domain_Model_Filter_Filterbox
	 */
	public function getAccessableFilterbox() {
		return Tx_PtExtlist_Domain_Model_Filter_FilterboxFactory::createAccessableInstance($this);
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
		return  $this->listIdentifier . '.filters.' . $this->filterboxIdentifier;
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
	
	
	
	/**
	 * Add Filter to Filterbox 
	 * 
	 * @param Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter
	 * @param string $filteridentifier
	 */
	public function addFilter(Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter, $filterIdentifier) {
		$this->addItem($filter, $filterIdentifier);
	}
	
	
	
	/**
	 * Returns filter by given filter identifier
	 *
	 * @param unknown_type $filterIdentifier
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterInterface
	 */
	public function getFilterByFilterIdentifier($filterIdentifier) {
		if ($this->hasItem($filterIdentifier)) {
			return $this->getItemById($filterIdentifier);
		} else {
			return null;
		}
	}
	
}

?>