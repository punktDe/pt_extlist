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
 * Class implementing factory for filter configuration
 * 
 * @package Domain
 * @subpackage Configuration\Filters
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_Configuration_Filters_FilterConfigFactory {
	
	public static function createInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $filterboxIdentifier, array $filterSettings) {
		$filterConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($configurationBuilder,$filterSettings,$filterboxIdentifier);
		$filterConfig = self::setAccessableFlag($filterConfig, $configurationBuilder);
		return $filterConfig;
	}
	
	
	
	/**
	 * Sets accessable flag for filter
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configBuilder
	 * @return Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig
	 */
	protected static function setAccessableFlag(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$security = Tx_PtExtlist_Domain_Security_SecurityFactory::getInstance();
		$accessable = $security->isAccessableFilter($filterConfig, $configurationBuilder);
		$filterConfig->setAccessable($accessable);
		
		return $filterConfig;
	}
	
}

?>