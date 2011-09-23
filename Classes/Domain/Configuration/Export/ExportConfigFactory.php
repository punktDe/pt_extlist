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
 * Class implements factory for export configuration
 *
 * @package Domain
 * @subpackage Configuration\Export
 * @author Daniel Lienert 
 */

class Tx_PtExtlist_Domain_Configuration_Export_ExportConfigFactory {
	
	/**
	 * Returns a instance of a export configuration.
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_Configuration_Export_ExportConfig
	 */
	public static function getInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		
		$exportSettings = self::getExportSettingsForCurrentView($configurationBuilder);
		$exportConfig = new Tx_PtExtlist_Domain_Configuration_Export_ExportConfig($configurationBuilder, $exportSettings);
		
		return $exportConfig;
	}
	
	
	/**
	 * Get the settings 
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	protected static function getExportSettingsForCurrentView(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		
		$allExportSettings = $configurationBuilder->getSettingsForConfigObject('export');
		$controllerSettings = $configurationBuilder->getSettings('controller');
		$selectedViewSettingsKey = $controllerSettings['Export']['download']['view'];
		$exportSettingsPath = explode('.',$selectedViewSettingsKey);

		$exportSettings = Tx_Extbase_Utility_Arrays::getValueByPath($configurationBuilder->getSettings(), $exportSettingsPath);
		
		/* In this case we have to merge the prototype settings again because the prototype settings are filled from flexform....
		 * This smells ... 
		 * TODO: find a better way .... 
		 */
		
		return $configurationBuilder->getMergedSettingsWithPrototype($exportSettings, 'export');
	}
}
?>