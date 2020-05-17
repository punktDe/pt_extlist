<?php


namespace PunktDe\PtExtlist\Domain\Link;

use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderFactory;
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
 * Class implements a factory for the link manager
 *
 * @package Domain
 * @subpackage Link
 * @author Daniel Lienert 
 */
class LinkManagerFactory
{
    /**
     * Array of singleton instance of link manager object
     *
     * @var LinkManager
     */
    private static $instances;
    
    
    
    /**
     * Factory method for link manager 
     *  
     * @param string listIdentifier
     * @return LinkManager
     */
    public static function getInstance($listIdentifier)
    {
        if (self::$instances[$listIdentifier] == null) {

            // TODO resolve this properly with Dependency Injection once we have cascading container
            #$configurationBuilder = ConfigurationBuilderFactory::getInstance($listIdentifier);
            $configurationBuilderFactory = GeneralUtility::makeInstance(ObjectManager::class)->get(ConfigurationBuilderFactory::class); /* @var $configurationBuilderFactory ConfigurationBuilderFactory */
            $configurationBuilder = $configurationBuilderFactory->getInstance($listIdentifier);
            $getPostVarsAdapterFactory = GeneralUtility::makeInstance(ObjectManager::class)->get(GetPostVarAdapterFactory::class); /* @var $getPostVarsAdapterFactory Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory */

            self::$instances[$listIdentifier] = new LinkManager();
            self::$instances[$listIdentifier]->injectGetPostVarAdapater($getPostVarsAdapterFactory->getInstance());
            self::$instances[$listIdentifier]->injectListConfiguration($configurationBuilder->buildListConfiguration());
        }
        
        return self::$instances[$listIdentifier];
    }
}
