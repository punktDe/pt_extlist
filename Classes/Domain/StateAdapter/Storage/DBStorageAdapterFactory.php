<?php
namespace PunktDe\PtExtlist\Domain\StateAdapter\Storage;
use PunktDe\PtExtlist\Domain\StateAdapter\GetPostVarAdapterFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
 * Factory for the DB storage adapter
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage StateAdapter\Storage
 */
class DBStorageAdapterFactory
{
    /**
     *
     * @var DBStorageAdapter
     */
    protected static $instance = null;


    
    /**
     * Create a single instance of the db storage adapter
     * 
     * @return DBStorageAdapter
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DBStorageAdapter();

            self::$instance->setStateHash(self::getStateHash());
            self::$instance->init();
        }
        
        return self::$instance;
    }



    /**
     * Build TYPO3 Caching Framework Cache
     * @throws Exception
     */
    protected function buildStateCache()
    {
        
            // Create the cache
            try {
                $GLOBALS['typo3CacheFactory']->create(
                    'tx_ptextlist',
                    't3lib_cache_frontend_VariableFrontend',
                    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tx_ptextlist']['backend'],
                    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tx_ptextlist']['options']
                );
            } catch (\TYPO3\CMS\Core\Cache\Exception\DuplicateIdentifierException $e) {
                // do nothing, the cache already exists
            }
            
            // Initialize the cache
            try {
                $cache = $GLOBALS['typo3CacheManager']->getCache('tx_ptextlist');
            } catch (\TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException $e) {
                throw new \Exception('Unable to load Cache! 1299942198');
            }
        
        
        return $cache;
    }
    
    
    
    /**
     * Get the state hash from GPVars
     * 
     * @return string hash 
     */
    protected static function getStateHash()
    {
        $getPostVarsAdapterFactory = GeneralUtility::makeInstance(ObjectManager::class)->get(GetPostVarAdapterFactory::class); /* @var $getPostVarsAdapterFactory GetPostVarAdapterFactory */
        $getPostVarAdapter = $getPostVarsAdapterFactory->getInstance();
        $stateHash = $getPostVarAdapter->getParametersByNamespace('state');
        return $stateHash;
    }
}
