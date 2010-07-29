<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * Class implements factory for pager classes for pt_extlist
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Model_Pager_PagerFactory {
	
	/**
	 * Holds an array with instances of pagers
	 *
	 * @var array
	 */
	protected static $instances = array();
	
	
	
	/**
	 * Returns an instance of pager for a given configuration builder and a pager configuration
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration $pagerConfiguration
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerInterface
	 */
	public static function getInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder,
	    Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration $pagerConfiguration) {
		if (!array_key_exists($configurationBuilder->getListIdentifier(), self::$instances)) {
			self::$instances[$configurationBuilder->getListIdentifier()] = self::createInstance($pagerConfiguration);
		}
		return self::$instances[$configurationBuilder->getListIdentifier()];
	}
	
	
	
	/**
	 * Returns pager object configured by given pager configuration
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration $pagerConfiguration
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerInterface
	 */
	private static function createInstance(Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration $pagerConfiguration) {
		$pagerClassName = $pagerConfiguration->getPagerClassName();
		tx_pttools_assert::isNotEmptyString($pagerClassName, array('message' => 'No filter class name given, check configuration! 1279541291'));
		tx_pttools_assert::isTrue(class_exists($pagerClassName), array('message' => 'Given pager class ' . $pagerClassName . ' does not exist or is not loaded! 1279541306'));
		
		
		$pager = new $pagerClassName();
        tx_pttools_assert::isTrue(is_a($pager, 'Tx_PtExtlist_Domain_Model_Pager_PagerInterface'), array('message' => 'Given pager class does not implement pager interface! 1279541488'));

		$pager->injectSettings($pagerConfiguration->getPagerSettings());
  
		// Inject settings from session.
        $sessionPersistenceManager = Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManagerFactory::getInstance();
        $sessionPersistenceManager->loadFromSession($pager);
        
        // Inject settings from gp-vars.
        $gpAdapter = Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory::getInstance();
        $gpAdapter->getParametersByObject($pager);
        
        // Save new state to session.
        $sessionPersistenceManager->persistToSession($pager);
        
        return $pager;
	}
	
}
?>