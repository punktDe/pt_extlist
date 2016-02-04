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
class Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManagerFactory
    extends Tx_PtExtlist_Domain_AbstractComponentFactoryWithState
    implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * Holds an array of instances for each list identifier
     *
     * @var array
     */
    protected $instances = array();



    /**
     * @var Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository
     */
    protected $bookmarkRepository;



    /**
     * @param Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository $bookmarkRepository
     */
    public function injectBookmarkRepository(Tx_PtExtlist_Domain_Repository_Bookmark_BookmarkRepository $bookmarkRepository)
    {
        $this->bookmarkRepository = $bookmarkRepository;
    }



    /**
     * Returns an instance of bookmark manager for a given configuration builder
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManager
     */
    public function getInstanceByConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder)
    {
        $listIdentifier = $configurationBuilder->getListIdentifier();
        if (!array_key_exists($listIdentifier, $this->instances) || $this->instances[$listIdentifier] === null) {
            $this->instances[$listIdentifier] = $this->createNewInstanceByConfigurationBuilder($configurationBuilder);
        }
        return $this->instances[$listIdentifier];
    }
    
    
    
    /**
     * Creates a new instance of bookmark manager for given configuration builder
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManager
     */
    protected function createNewInstanceByConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder)
    {
        $bookmarksConfiguration = $configurationBuilder->buildBookmarkConfiguration();

        $this->bookmarkRepository->setBookmarkStoragePid($bookmarksConfiguration->getBookmarkPid());
        
        $bookmarkManager = $this->objectManager->get('Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManager', $configurationBuilder->getListIdentifier()); /* @var $bookmarkManager Tx_PtExtlist_Domain_Model_Bookmark_BookmarkManager */
        $bookmarkManager->_injectConfigurationBuilder($configurationBuilder);
        $bookmarkManager->_injectSessionPersistenceManager($this->sessionPersistenceManagerBuilder->getInstance());
        $bookmarkManager->buildBookmarkConfig();
        $bookmarkManager->initFeUser();

        return $bookmarkManager;
    }
}
