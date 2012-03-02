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
 * Class implements pager collection factory
 *
 * @package Domain
 * @subpackage Model\Pager
 * @author Daniel Lienert 
 * @author Christoph Ehscheidt 
 */
class Tx_PtExtlist_Domain_Model_Pager_PagerCollectionFactory {

	
	/**
	 * Returns a instance of the pager collection.
	 * 
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerCollection
	 */
	public static function getInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		
		$listIdentifier = $configurationBuilder->getListIdentifier();

		return self::buildPagerCollection($configurationBuilder, $listIdentifier);
	}
	
	
	/**
	 * Build the Pager Collection
	 * 
	 * @param $configurationBuilder
	 * @param $listIdentifier
	 */
	protected static function buildPagerCollection(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $listIdentifier) {
			$pagerConfigurationCollection = $configurationBuilder->buildPagerConfiguration();	

			$pagerCollection = new Tx_PtExtlist_Domain_Model_Pager_PagerCollection($configurationBuilder);
			
			$sessionPersistenceManager = Tx_PtExtbase_State_Session_SessionPersistenceManagerFactory::getInstance();
			$sessionPersistenceManager->registerObjectAndLoadFromSession($pagerCollection);
			$pagerCollection->injectSessionPersistenceManager($sessionPersistenceManager);
			
			Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory::getInstance()->injectParametersInObject($pagerCollection);
			
			// Create pagers and add them to the collection
			foreach($pagerConfigurationCollection as $pagerIdentifier => $pagerConfig) {
				$pager = Tx_PtExtlist_Domain_Model_Pager_PagerFactory::getInstance($pagerConfig);
				$pagerCollection->addPager($pager);
			}
			
			return $pagerCollection;
	}
}

?>