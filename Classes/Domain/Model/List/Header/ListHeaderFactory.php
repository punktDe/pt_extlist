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
 * Class implements factory for list header
 * 
 * @author Daniel Lienert
 * @author Michael Knoll
 * @package Domain
 * @subpackage Model\List\Header
 */
class Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory {

    /**
     * Holds an array of singleton instances for each list identifier
     * 
     * @var array
     */
    private static $instances = array();



	/**
	 * Build singleton instance of listHeader, a collection of header column objects
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param $resetListHeader boolean
	 * @return Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 */
	public static function createInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $resetListHeader = false) {
		$listIdentifier = $configurationBuilder->getListIdentifier();

		// Check whether singleton instance exists
		if (!array_key_exists($listIdentifier, self::$instances) || self::$instances[$listIdentifier] === null || $resetListHeader) {
			$defaultSortingColumn = $configurationBuilder->buildListDefaultConfig()->getSortingColumn();
			$columnConfigurationCollection = $configurationBuilder->buildColumnsConfiguration();
			$listHeader = new Tx_PtExtlist_Domain_Model_List_Header_ListHeader($configurationBuilder->getListIdentifier());
			$listIsSorted = false;

			foreach ($columnConfigurationCollection as $columnIdentifier => $singleColumnConfiguration) {
				$headerColumn = Tx_PtExtlist_Domain_Model_List_Header_HeaderColumnFactory::createInstance($singleColumnConfiguration);

				if ($singleColumnConfiguration->isAccessable()) {
					// We set list as sorted as soon as one column has sorting-status from user / session
					if ($headerColumn->isSortingActive()) {
						$listIsSorted = true;
					}

					$listHeader->addHeaderColumn($headerColumn, $singleColumnConfiguration->getColumnIdentifier());
				}
			}

			self::setVisibilityByColumnSelector($configurationBuilder, $listHeader);

			// Check whether we have a sorting from header columns (set by user)
			// or whether we have to set default sorting
			if (!$listIsSorted && $defaultSortingColumn && $listHeader->hasItem($defaultSortingColumn)) {
				$listHeader->getHeaderColumn($defaultSortingColumn)->setDefaultSorting($configurationBuilder->buildListDefaultConfig()->getSortingDirection());
				$listHeader->getHeaderColumn($defaultSortingColumn)->init();
			}

			// inject gpVarData
			$gpVarsAdapter = Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory::getInstance();
			$gpVarsAdapter->injectParametersInObject($listHeader);

			$listHeader->init();

			self::$instances[$listIdentifier] = $listHeader;
		}

		
		// We return singleton instance of listHeader
		return self::$instances[$listIdentifier];
	}


	/**
	 * @static
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader
	 */
	protected static function setVisibilityByColumnSelector(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader) {
		if($configurationBuilder->buildColumnSelectorConfiguration()->getEnabled()) {
			$columnSelector = Tx_PtExtlist_Domain_Model_ColumnSelector_ColumnSelectorFactory::getInstance($configurationBuilder);
			$columnSelector->setVisibilityOnListHeader($listHeader);
		}
	}
    
}
?>