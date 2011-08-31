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
 * Class implements a collection of breadcrumbs
 *
 * @package Domain
 * @subpackage Model\BreadCrumbs
 * @author Michael Knoll
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollection extends Tx_PtExtbase_Collection_ObjectCollection 
	implements  Tx_PtExtbase_State_IdentifiableInterface,
	            Tx_PtExtbase_State_GpVars_GpVarsInjectableInterface {

	/**
	 * Restrict collection to breadcrumb class
	 *
	 * @var String
	 */
	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb';



	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;



	/**
	 * @var array
	 */
	protected $gpVarData;



	/**
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function injectConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->configurationBuilder = $configurationBuilder;
	}



	/**
	 * Inject the GPVarData
	 *
	 * @param array $gpVarData
	 */
	public function injectGPVars($gpVarData) {
		$this->gpVarData = $gpVarData;
	}



	/**
	 * Adds a breadcrumb to collection
	 *
	 * @param Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb $breadCrumb BreadCrumb to be added
	 */
	public function addBreadCrumb(Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb $breadCrumb) {
		$breadcrumbIdentifier = $breadCrumb->getFilter()->getFilterBoxIdentifier() . '.' . $breadCrumb->getFilter()->getFilterIdentifier();
		$this->addItem($breadCrumb,$breadcrumbIdentifier);
	}



	/**
	 * @see Tx_PtExtbase_State_IdentifiableInterface
	 */
	public function getObjectNamespace() {
		return $this->configurationBuilder->getListIdentifier() . '.' . 'breadcrumbs';
	}



	/**
	 * 
	 *
	 */
	public function resetFilters() {
		$breadCrumbIdentifier = $this->gpVarData['filterboxIdentifier'] . '.' . $this->gpVarData['filterIdentifier'];
		if ($this->hasItem($breadCrumbIdentifier)) {
			$this->getItemById($breadCrumbIdentifier)->getFilter()->reset();
		}
	}
}
?>