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
 * Class FilterboxConfig Collection
 *
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Configuration\Filters
 */
class Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfigCollection extends Tx_PtExtbase_Collection_ObjectCollection {
	
	protected $listIdentifier;
	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig';
	
	
	/**
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
	}
	
	
	/**
	 * Add a filterbox config
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig $filterBox
	 * @param string $filterBoxIdentifier
	 */
	public function addFilterBoxConfig(Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig $filterBox, $filterBoxIdentifier) {
		$this->addItem($filterBox, $filterBoxIdentifier);
	}


	/**
	 * @param $filterBoxIdentifier
	 * @return Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig
	 */
	public function getFilterBoxConfig($filterBoxIdentifier) {
		return $this->getItemById($filterBoxIdentifier);
	}

}
?>