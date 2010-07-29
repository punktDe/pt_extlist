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
 * Class implements a factory for a data source
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_DataBackend_DataSource_DataSourceFactory {
	
	/**
	 * Returns an instance of a data source for a given data source class name.
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return mixed
	 */
	public static function createDataSource(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$dataBackendSettings = $configurationBuilder->getBackendConfiguration();
		tx_pttools_assert::isNotEmptyString($dataBackendSettings['dataSourceClass'], array('message' => 'No dataSourceClass given in dataBackendSettings 1277889454'));	
		$dataSourceClassName = $dataBackendSettings['dataSourceClass'];
		
		// Check whether dataSource class exists
		if (!class_exists($dataSourceClassName)) {
			throw new Exception('Data Source class ' . $dataSourceClassName . ' does not exist! 1280400024');
		}
		$dataSource = new $dataSourceClassName($dataBackendSettings);
		
		return $dataSource;
	}
	
}

?>