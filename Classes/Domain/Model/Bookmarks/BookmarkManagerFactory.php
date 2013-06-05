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
 * Factory for bookmark manager
 *
 * @package Domain
 * @subpackage Model\Bookmarks
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Model_Bookmarks_BookmarkManagerFactoryTest
 */
class Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManagerFactory
	extends Tx_PtExtlist_Domain_AbstractComponentFactoryWithState
	implements t3lib_Singleton {
	
	/**
	 * Holds an array of instances for each list identifier
	 *
	 * @var array
	 */
	protected $instances = array();



	/**
	 * Returns an instance of bookmark manager for a given configuration builder
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager
	 */
	public function getInstanceByConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$listIdentifier = $configurationBuilder->getListIdentifier();
		if (!array_key_exists($listIdentifier, $this->instances) || $this->instances[$listIdentifier] === NULL) {
			$this->instances[$listIdentifier] = $this->createNewInstanceByConfigurationBuilder($configurationBuilder);
		}
		return $this->instances[$listIdentifier];
	}
	
	
	
	/**
	 * Creates a new instance of bookmark manager for given configuration builder
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager
	 */
	protected function createNewInstanceByConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$bookmarksConfiguration = $configurationBuilder->buildBookmarksConfiguration();

		$bookmarksRepository = $this->objectManager->get('Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository'); /* @var $bookmarksRepository Tx_PtExtlist_Domain_Repository_Bookmarks_BookmarkRepository */
		$bookmarksRepository->setBookmarksStoragePid($bookmarksConfiguration->getBookmarksPid());
		
		$bookmarkManager = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager', $configurationBuilder->getListIdentifier());
		$bookmarkManager->injectSessionPersistenceManager($this->sessionPersistenceManagerBuilder->getInstance());
		$bookmarkManager->injectBookmarkRepository($bookmarksRepository);

		return $bookmarkManager;
	}

}