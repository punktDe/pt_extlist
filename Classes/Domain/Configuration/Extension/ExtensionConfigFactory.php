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
 * Factory to create configs that affect the whole extension
 *
 * @package Domain
 * @subpackage Configuration\Extension
 * @author Daniel Lienert <lienert@punkt.de>
 */

class Tx_PtExtlist_Domain_Configuration_Extension_ExtensionConfigFactory {
	
	/**
	 * Returns a instance of the extension configuration.
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_Configuration_Extension_ExtensionConfig
	 */
	public static function getInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		return new Tx_PtExtlist_Domain_Configuration_Extension_ExtensionConfig($configurationBuilder, array());
	}
}
?>