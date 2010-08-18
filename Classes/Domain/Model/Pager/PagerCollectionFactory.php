<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de>
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

class Tx_PtExtlist_Domain_Model_Pager_PagerCollectionFactory {

	/**
	 * Holds an instance of the pager collection.
	 * 
	 * @var Tx_PtExtlist_Domain_Model_Pager_PagerCollection
	 */
	protected static $pagerCollection = NULL;
	
	/**
	 * Returns a instance of the pager collection.
	 * 
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerCollection
	 */
	public static function createInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder,
	    Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration $pagerConfiguration) {
	    	
	    $pagerSettings = $configurationBuilder->getPagerSettings();
		
		if(self::$pagerCollection == NULL) {
			self::$pagerCollection = new Tx_PtExtlist_Domain_Model_Pager_PagerCollection();
		}
		
		foreach($pagerSettings['pagerConfigs'] as $pagerId => $pagerConfig) {
			
		}
		
		
		return self::$pagerCollection;
	}
	
}

?>