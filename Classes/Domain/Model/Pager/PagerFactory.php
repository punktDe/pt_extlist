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
 * Class implements factory for pager classes for pt_extlist
 *
 * @package Domain
 * @subpackage Model\Pager
 * @author Michael Knoll 
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Model_Pager_PagerFactory {
	
	
	/**
	 * Returns an instance of pager for a given configuration builder and a pager configuration
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig $pagerConfiguration
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerInterface
	 */
	public static function getInstance(Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig $pagerConfiguration) {
		
		return  self::createInstance($pagerConfiguration);
	
	}
	
	
	/**
	 * Returns pager object configured by given pager configuration
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig $pagerConfiguration
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerInterface
	 */
	private static function createInstance(Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig $pagerConfiguration) {
		$pagerClassName = $pagerConfiguration->getPagerClassName();
        		
		$pager = new $pagerClassName($pagerConfiguration);
        Tx_PtExtbase_Assertions_Assert::isTrue(is_a($pager, 'Tx_PtExtlist_Domain_Model_Pager_PagerInterface'), array('message' => 'Given pager class does not implement pager interface! 1279541488'));
        
        return $pager;
	}
	
}
?>