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
 * Factory to create configs for pager
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Daniel Lienert <lienert@punkt.de>
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 */

class Tx_PtExtlist_Domain_Configuration_Pager_PagerConfigurationFactory {
	
	/**
	 * Returns a instance of a pager configuration.
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param string $pagerId 
	 */
	public static function getInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $pagerId = 'default') {
		$settings = $configurationBuilder->getPagerSettings();

		tx_pttools_assert::isTrue(array_key_exists($pagerId, $settings['pagerConfigs']),  array(message => 'Pager Identifier \''.$pagerId.'\' not found. 1282132591'));
		
		$pagerSettings = $settings['pagerConfigs'][$pagerId];
		$pagerSettings['itemsPerPage'] = $settings['itemsPerPage'];
		
		$pagerConfig = new Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration($pagerSettings, $configurationBuilder->getListIdentifier(), $pagerId);
		
		return $pagerConfig;
	}
}
?>