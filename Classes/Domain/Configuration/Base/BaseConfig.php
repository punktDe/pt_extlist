<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Base;

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

use PunktDe\PtExtlist\Domain\Configuration\AbstractExtlistConfiguration;
use PunktDe\PtExtlist\Extbase\ExtbaseContext;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class implements basic configuration parameters
 *
 * @package Domain
 * @subpackage Configuration\Base
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Base_BaseConfigTest
 */
class BaseConfig extends AbstractExtlistConfiguration
{
    /**
     * Session storage adapter for a uncached plugin
     * @var string
     */
    protected $uncachedSessionStorageAdapter;

    
    
    /**
     * Session storage adapter for a cached plugin
     *  
     * @var string
     */
    protected $cachedSessionStorageAdapter;



    /**
     * True, if we want to reset session data on empty submit
     *  
     * @var bool
     */
    protected $resetOnEmptySubmit = false;

    /**
     * True, if we want to make it possible to reset session data on GPVar 'resetSession'
     *
     * @var bool
     */
    protected $resetSessionOnResetParameter = false;
    
    
    
    /**
     * @var bool
     */
    protected $useSession;
    


    /**
     * Template method for initializing this config object by injected
     * TypoScript settings.
     * @throws \Exception
     */
    protected function init()
    {
        $this->setBooleanIfExistsAndNotNothing('resetOnEmptySubmit');
        $this->setBooleanIfExistsAndNotNothing('resetSessionOnResetParameter');
        $this->setRequiredValue('uncachedSessionStorageAdapter', 'No storage adapter for a uncached plugin has been given! 1302255094');
        $this->setRequiredValue('cachedSessionStorageAdapter', 'No storage adapter for a cached plugin has been given! 1302255109');
        $this->useSession = GeneralUtility::makeInstance(ObjectManager::class)->get(ExtbaseContext::class)->isInCachedMode() ? false : true;
    }
    
    
    
    /**
     * @return string
     */
    public function getCachedSessionStorageAdapter()
    {
        return $this->cachedSessionStorageAdapter;
    }
    
    
    
    /**
     * @return string
     */
    public function getUncachedSessionStorageAdapter()
    {
        return $this->uncachedSessionStorageAdapter;
    }
    
    
    
    /**
     * @return bool
     */
    public function getUseSession()
    {
        return $this->useSession;
    }



    /**
     * Returns true, if we want to reset session on empty submit
     *  
     * @return bool
     */
    public function getResetOnEmptySubmit()
    {
        return $this->resetOnEmptySubmit;
    }

    /**
     * @return boolean
     */
    public function getResetSessionOnResetParameter()
    {
        return $this->resetSessionOnResetParameter;
    }
}
